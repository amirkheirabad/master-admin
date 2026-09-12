<?php

namespace Tests\Feature\CustomerForm;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Modules\CustomerForm\Models\Form;
use Modules\CustomerForm\Models\FormSubmission;
use Modules\CustomerForm\Repositories\InterfaceCustomerForm;
use Modules\Stores\Models\Stores;
use Modules\User\Models\User;
use Tests\TestCase;

class CustomerFormLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private InterfaceCustomerForm $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(InterfaceCustomerForm::class);
    }

    public function test_first_structural_edit_clones_published_version_only_once(): void
    {
        $form = $this->publishedForm();

        $firstDraft = $this->repo->draft($form);
        $secondDraft = $this->repo->draft($form->fresh());

        $this->assertTrue($firstDraft->is($secondDraft));
        $this->assertSame(2, $firstDraft->version_number);
        $this->assertSame(1, $firstDraft->questions()->count());
    }

    public function test_editing_a_published_question_changes_only_its_draft_clone(): void
    {
        $form = $this->publishedForm();
        $publishedQuestion = $form->publishedVersion->questions->first();

        $draftQuestion = $this->repo->saveQuestion($form, [
            'label' => 'متن جدید',
            'type' => 'text',
            'is_required' => false,
            'options' => [],
        ], $publishedQuestion);

        $this->assertSame('سؤال اولیه', $publishedQuestion->fresh()->label);
        $this->assertSame('متن جدید', $draftQuestion->label);
        $this->assertNotSame($publishedQuestion->form_version_id, $draftQuestion->form_version_id);
    }

    public function test_publish_moves_only_assignments_without_a_response(): void
    {
        $form = $this->publishedForm();
        $answeredStore = $this->store('پاسخ داده');
        $unansweredStore = $this->store('بدون پاسخ');
        $answered = $this->repo->assign($form, $answeredStore)['assignment'];
        $unanswered = $this->repo->assign($form, $unansweredStore)['assignment'];
        $submission = FormSubmission::create([
            'form_assignment_id' => $answered->id,
            'form_version_id' => $answered->form_version_id,
            'submitted_at' => now(),
        ]);
        $answered->update(['current_submission_id' => $submission->id]);

        $this->repo->saveQuestion($form, [
            'label' => 'سؤال دوم',
            'type' => 'textarea',
            'is_required' => false,
            'options' => [],
        ]);
        $newVersion = $this->repo->publish($form->fresh());

        $this->assertNotSame($newVersion->id, $answered->fresh()->form_version_id);
        $this->assertSame($newVersion->id, $unanswered->fresh()->form_version_id);
    }

    public function test_reusing_assignment_does_not_reactivate_or_replace_its_token(): void
    {
        $form = $this->publishedForm();
        $store = $this->store('فروشگاه');
        $created = $this->repo->assign($form, $store);
        $assignment = $created['assignment'];
        $assignment->update(['is_active' => false]);

        $reused = $this->repo->assign($form, $store);

        $this->assertTrue($reused['assignment']->is($assignment));
        $this->assertFalse($assignment->fresh()->is_active);
        $this->assertNull($reused['token']);
        $this->assertSame($created['token'], $assignment->fresh()->token_encrypted);
    }

    public function test_explicit_upgrade_preserves_old_submission_and_clears_current_pointer(): void
    {
        $form = $this->publishedForm();
        $assignment = $this->repo->assign($form, $this->store('فروشگاه'))['assignment'];
        $submission = FormSubmission::create([
            'form_assignment_id' => $assignment->id,
            'form_version_id' => $assignment->form_version_id,
            'submitted_at' => now(),
        ]);
        $assignment->update(['current_submission_id' => $submission->id]);
        $this->repo->saveQuestion($form, [
            'label' => 'سؤال دوم',
            'type' => 'text',
            'is_required' => false,
            'options' => [],
        ]);
        $latestVersion = $this->repo->publish($form->fresh());

        $this->repo->upgradeAssignment($assignment->fresh());

        $assignment->refresh();
        $this->assertNull($assignment->current_submission_id);
        $this->assertSame($latestVersion->id, $assignment->form_version_id);
        $this->assertDatabaseHas('form_submissions', ['id' => $submission->id]);
    }

    public function test_publish_rejects_option_questions_without_options(): void
    {
        $form = $this->repo->createForm(['title' => 'فرم تست', 'is_active' => true]);
        $this->repo->saveQuestion($form, [
            'label' => 'انتخاب کنید',
            'type' => 'select',
            'is_required' => true,
            'options' => [],
        ]);

        $this->expectException(ValidationException::class);

        $this->repo->publish($form->fresh());
    }

    private function publishedForm(): Form
    {
        $form = $this->repo->createForm(['title' => 'فرم تست', 'is_active' => true]);
        $this->repo->saveQuestion($form, [
            'label' => 'سؤال اولیه',
            'type' => 'text',
            'is_required' => true,
            'options' => [],
        ]);
        $this->repo->publish($form->fresh());

        return $form->fresh('publishedVersion.questions.options');
    }

    private function store(string $name): Stores
    {
        $user = User::create([
            'name' => $name,
            'mobile' => '09'.fake()->unique()->numerify('#########'),
            'password' => bcrypt('password'),
        ]);

        return Stores::create([
            'store_name' => $name,
            'link' => 'https://example.com',
            'phone' => $user->mobile,
            'user_id' => $user->id,
            'province' => 'تهران',
            'city' => 'تهران',
            'location' => 'تهران',
            'code_posty' => '1234567890',
            'token' => fake()->uuid(),
            'site_type' => 'index',
            'is_active' => true,
        ]);
    }
}
