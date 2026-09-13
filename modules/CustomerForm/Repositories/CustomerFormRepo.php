<?php

namespace Modules\CustomerForm\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\CustomerForm\Models\Form;
use Modules\CustomerForm\Models\FormAnswer;
use Modules\CustomerForm\Models\FormAssignment;
use Modules\CustomerForm\Models\FormQuestion;
use Modules\CustomerForm\Models\FormSubmission;
use Modules\CustomerForm\Models\FormVersion;
use Modules\Stores\Models\Stores;

class CustomerFormRepo implements InterfaceCustomerForm
{
    private const OPTION_TYPES = ['select', 'radio', 'checkbox'];

    public function createForm(array $data): Form
    {
        return DB::transaction(function () use ($data) {
            $form = Form::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);
            $version = $form->versions()->create(['version_number' => 1]);
            $form->update(['draft_version_id' => $version->id]);

            return $form->fresh(['draftVersion', 'publishedVersion']);
        });
    }

    public function draft(Form $form): FormVersion
    {
        return DB::transaction(function () use ($form) {
            $form = Form::lockForUpdate()->findOrFail($form->id);

            if ($form->draft_version_id) {
                return $form->draftVersion()->with('questions.options')->firstOrFail();
            }

            $published = $form->publishedVersion()->with('questions.options')->firstOrFail();
            $draft = $form->versions()->create([
                'version_number' => $form->versions()->max('version_number') + 1,
            ]);

            foreach ($published->questions as $question) {
                $copy = $draft->questions()->create($question->only('label', 'type', 'is_required', 'sort_order'));
                foreach ($question->options as $option) {
                    $copy->options()->create($option->only('label', 'sort_order'));
                }
            }

            $form->update(['draft_version_id' => $draft->id]);

            return $draft->load('questions.options');
        });
    }

    public function saveQuestion(Form $form, array $data, ?FormQuestion $question = null): FormQuestion
    {
        return DB::transaction(function () use ($form, $data, $question) {
            $draft = $this->draft($form);

            if ($question) {
                $this->assertQuestionBelongsToForm($form, $question);
                if ($question->form_version_id !== $draft->id) {
                    $question = $draft->questions()->where('sort_order', $question->sort_order)->firstOrFail();
                }
            } else {
                $question = $draft->questions()->create([
                    'label' => $data['label'],
                    'type' => $data['type'],
                    'is_required' => $data['is_required'] ?? false,
                    'sort_order' => ($draft->questions()->max('sort_order') ?? 0) + 1,
                ]);
            }

            $question->update([
                'label' => $data['label'],
                'type' => $data['type'],
                'is_required' => $data['is_required'] ?? false,
            ]);
            $question->options()->delete();

            if (in_array($data['type'], self::OPTION_TYPES, true)) {
                foreach (array_values(array_filter($data['options'] ?? [], fn ($label) => trim((string) $label) !== '')) as $index => $label) {
                    $question->options()->create(['label' => trim($label), 'sort_order' => $index + 1]);
                }
            }

            return $question->load('options');
        });
    }

    public function deleteQuestion(Form $form, FormQuestion $question): void
    {
        DB::transaction(function () use ($form, $question) {
            $draft = $this->draft($form);
            $this->assertQuestionBelongsToForm($form, $question);

            if ($question->form_version_id !== $draft->id) {
                $question = $draft->questions()->where('sort_order', $question->sort_order)->firstOrFail();
            }

            $question->delete();
            $this->writeOrder($draft, $draft->questions()->pluck('id')->all());
        });
    }

    public function reorderQuestions(Form $form, array $questionIds): void
    {
        DB::transaction(function () use ($form, $questionIds) {
            $draft = $this->draft($form);
            $actual = $draft->questions()->pluck('id')->sort()->values();

            if ($actual->all() !== collect($questionIds)->map(fn ($id) => (int) $id)->sort()->values()->all()) {
                throw ValidationException::withMessages(['questions' => 'ترتیب سؤال‌ها معتبر نیست.']);
            }

            $this->writeOrder($draft, $questionIds);
        });
    }

    public function publish(Form $form): FormVersion
    {
        return DB::transaction(function () use ($form) {
            $form = Form::lockForUpdate()->findOrFail($form->id);
            $draft = $form->draftVersion()->with('questions.options')->firstOrFail();

            if ($draft->questions->isEmpty()) {
                throw ValidationException::withMessages(['form' => 'فرم باید حداقل یک سؤال داشته باشد.']);
            }

            $invalid = $draft->questions->first(fn ($question) => in_array($question->type, self::OPTION_TYPES, true) && $question->options->isEmpty());
            if ($invalid) {
                throw ValidationException::withMessages(['form' => "برای سؤال «{$invalid->label}» حداقل یک گزینه تعریف کنید."]);
            }

            $draft->update(['published_at' => now()]);
            $form->update(['published_version_id' => $draft->id, 'draft_version_id' => null]);
            $form->assignments()->whereNull('current_submission_id')->update(['form_version_id' => $draft->id]);

            return $draft->fresh('questions.options');
        });
    }

    public function assign(Form $form, Stores $store): array
    {
        return DB::transaction(function () use ($form, $store) {
            $form = Form::lockForUpdate()->findOrFail($form->id);
            $existing = $form->assignments()->where('store_id', $store->id)->first();

            if ($existing) {
                return ['assignment' => $existing, 'token' => null];
            }

            if (! $form->is_active || ! $form->published_version_id) {
                throw ValidationException::withMessages(['form_id' => 'این فرم برای تخصیص آماده نیست.']);
            }

            [$token, $tokenData] = $this->newToken();
            $assignment = $form->assignments()->create($tokenData + [
                'form_version_id' => $form->published_version_id,
                'store_id' => $store->id,
                'is_active' => true,
            ]);

            return ['assignment' => $assignment, 'token' => $token];
        });
    }

    public function toggleAssignment(FormAssignment $assignment): FormAssignment
    {
        $assignment->update(['is_active' => ! $assignment->is_active]);

        return $assignment->fresh();
    }

    public function rotateToken(FormAssignment $assignment): string
    {
        return DB::transaction(function () use ($assignment) {
            $assignment = FormAssignment::lockForUpdate()->findOrFail($assignment->id);
            [$token, $tokenData] = $this->newToken();
            $assignment->update($tokenData);

            return $token;
        });
    }

    public function upgradeAssignment(FormAssignment $assignment): FormAssignment
    {
        return DB::transaction(function () use ($assignment) {
            $assignment = FormAssignment::lockForUpdate()->with('form')->findOrFail($assignment->id);
            $publishedVersionId = $assignment->form->published_version_id;

            if (! $publishedVersionId || $publishedVersionId === $assignment->form_version_id) {
                throw ValidationException::withMessages(['assignment' => 'نسخه جدیدی برای ارتقا وجود ندارد.']);
            }

            $assignment->update([
                'form_version_id' => $publishedVersionId,
                'current_submission_id' => null,
            ]);

            return $assignment->fresh();
        });
    }

    public function resolveAssignment(string $token): FormAssignment
    {
        return FormAssignment::query()
            ->with([
                'form',
                'store.user',
                'formVersion.questions.options',
                'currentSubmission.answers',
            ])
            ->where('token_hash', hash('sha256', $token))
            ->where('is_active', true)
            ->whereHas('form', fn ($query) => $query->where('is_active', true))
            ->firstOrFail();
    }

    public function submit(FormAssignment $assignment, array $answers): FormSubmission
    {
        return DB::transaction(function () use ($assignment, $answers) {
            $assignment = FormAssignment::lockForUpdate()
                ->with(['form', 'formVersion.questions.options', 'currentSubmission'])
                ->findOrFail($assignment->id);

            abort_unless($assignment->is_active && $assignment->form->is_active, 404);

            $submission = $assignment->currentSubmission;
            if ($submission) {
                abort_unless($submission->form_version_id === $assignment->form_version_id, 409);
            } else {
                $submission = FormSubmission::create([
                    'form_assignment_id' => $assignment->id,
                    'form_version_id' => $assignment->form_version_id,
                    'submitted_at' => now(),
                ]);
                $assignment->update(['current_submission_id' => $submission->id]);
            }

            foreach ($assignment->formVersion->questions as $question) {
                $value = $answers[$question->id] ?? null;
                $empty = $value === null || $value === '' || $value === [];

                if ($empty) {
                    FormAnswer::where('form_submission_id', $submission->id)
                        ->where('form_question_id', $question->id)
                        ->delete();

                    continue;
                }

                FormAnswer::updateOrCreate(
                    ['form_submission_id' => $submission->id, 'form_question_id' => $question->id],
                    ['value' => $value]
                );
            }

            $submission->update(['submitted_at' => now()]);

            return $submission->fresh('answers');
        });
    }

    private function assertQuestionBelongsToForm(Form $form, FormQuestion $question): void
    {
        $version = $question->formVersion;
        if ($version->form_id !== $form->id || ! in_array($version->id, array_filter([$form->published_version_id, $form->draft_version_id]), true)) {
            abort(404);
        }
    }

    private function writeOrder(FormVersion $draft, array $questionIds): void
    {
        $draft->questions()->update(['sort_order' => DB::raw('sort_order + 100000')]);
        foreach (array_values($questionIds) as $index => $questionId) {
            $draft->questions()->whereKey($questionId)->update(['sort_order' => $index + 1]);
        }
    }

    private function newToken(): array
    {
        do {
            $token = Str::random(64);
            $hash = hash('sha256', $token);
        } while (FormAssignment::where('token_hash', $hash)->exists());

        return [$token, ['token_hash' => $hash, 'token_encrypted' => $token]];
    }
}
