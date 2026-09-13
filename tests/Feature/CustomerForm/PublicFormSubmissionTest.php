<?php

namespace Tests\Feature\CustomerForm;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\CustomerForm\Models\FormAnswer;
use Modules\CustomerForm\Models\FormSubmission;
use Modules\CustomerForm\Repositories\InterfaceCustomerForm;
use Modules\Stores\Models\Stores;
use Modules\User\Models\User;
use Tests\TestCase;

class PublicFormSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private InterfaceCustomerForm $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(InterfaceCustomerForm::class);
    }

    public function test_valid_token_creates_one_submission_then_updates_it(): void
    {
        [$assignment, $token, $question] = $this->publicFixture();

        $this->post(route('customer-forms.public.submit', $token), [
            'answers' => [$question->id => 'پاسخ اول'],
        ])->assertRedirect(route('customer-forms.public.show', $token));

        $submissionId = $assignment->fresh()->current_submission_id;

        $this->post(route('customer-forms.public.submit', $token), [
            'answers' => [$question->id => 'پاسخ ویرایش‌شده'],
        ])->assertRedirect(route('customer-forms.public.show', $token));

        $this->assertSame(1, FormSubmission::where('form_assignment_id', $assignment->id)->count());
        $this->assertSame($submissionId, $assignment->fresh()->current_submission_id);
        $this->assertSame('پاسخ ویرایش‌شده', FormAnswer::where('form_submission_id', $submissionId)->firstOrFail()->value);
    }

    public function test_public_form_loads_saved_answers(): void
    {
        [$assignment, $token, $question] = $this->publicFixture();
        $this->post(route('customer-forms.public.submit', $token), [
            'answers' => [$question->id => 'پاسخ ذخیره‌شده'],
        ]);

        $this->get(route('customer-forms.public.show', $token))
            ->assertOk()
            ->assertSee('پاسخ ذخیره‌شده');
    }

    public function test_inactive_rotated_and_unknown_tokens_return_not_found(): void
    {
        [$assignment, $token] = $this->publicFixture();
        $assignment->update(['is_active' => false]);
        $this->get(route('customer-forms.public.show', $token))->assertNotFound();

        $assignment->update(['is_active' => true]);
        $oldToken = $token;
        $newToken = $this->repo->rotateToken($assignment);

        $this->get(route('customer-forms.public.show', $oldToken))->assertNotFound();
        $this->get(route('customer-forms.public.show', $newToken))->assertOk();
        $this->get('/f/not-a-token')->assertNotFound();
    }

    public function test_public_submission_rejects_unknown_questions_and_foreign_options(): void
    {
        [$assignment, $token, $question] = $this->publicFixture('select');
        [, , $foreignQuestion] = $this->publicFixture('select', 'فرم دیگر');
        $foreignOption = $foreignQuestion->options->first();

        $this->post(route('customer-forms.public.submit', $token), [
            'answers' => [
                $question->id => $foreignOption->id,
                $foreignQuestion->id => $foreignOption->id,
            ],
        ])->assertSessionHasErrors(['answers', 'answers.'.$question->id]);

        $this->assertNull($assignment->fresh()->current_submission_id);
    }

    public function test_required_questions_use_persian_validation(): void
    {
        [, $token, $question] = $this->publicFixture();

        $this->post(route('customer-forms.public.submit', $token), ['answers' => []])
            ->assertSessionHasErrors('answers.'.$question->id);
    }

    private function publicFixture(string $type = 'text', string $title = 'فرم عمومی'): array
    {
        $form = $this->repo->createForm(['title' => $title, 'is_active' => true]);
        $question = $this->repo->saveQuestion($form, [
            'label' => 'پرسش مشتری',
            'type' => $type,
            'is_required' => true,
            'options' => $type === 'select' ? ['گزینه اول', 'گزینه دوم'] : [],
        ]);
        $this->repo->publish($form->fresh());
        $result = $this->repo->assign($form->fresh(), $this->store($title));

        return [$result['assignment'], $result['token'], $question->fresh('options')];
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
