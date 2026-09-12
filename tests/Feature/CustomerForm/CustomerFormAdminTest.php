<?php

namespace Tests\Feature\CustomerForm;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\CustomerForm\Models\Form;
use Modules\CustomerForm\Models\FormAnswer;
use Modules\CustomerForm\Models\FormSubmission;
use Modules\CustomerForm\Repositories\InterfaceCustomerForm;
use Modules\Stores\Models\Stores;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CustomerFormAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_form_admin_routes_require_admin_role(): void
    {
        $this->get('/customer-forms')->assertRedirect('/login');

        $seller = $this->userWithRole('seller');
        $this->actingAs($seller)->get('/customer-forms')->assertForbidden();

        $admin = $this->userWithRole('admin');
        $this->actingAs($admin)->get('/customer-forms')->assertOk();
    }

    public function test_admin_can_create_form_manage_questions_and_publish(): void
    {
        $admin = $this->userWithRole('admin');
        $this->actingAs($admin)->post(route('customer-forms.store'), [
            'title' => 'فرم جدید',
            'description' => 'توضیح فرم',
            'is_active' => 1,
        ])->assertRedirect(route('customer-forms.index'));

        $form = Form::where('title', 'فرم جدید')->firstOrFail();
        $this->actingAs($admin)->post(route('customer-forms.questions.store', $form), [
            'label' => 'کدام گزینه را ترجیح می‌دهید؟',
            'type' => 'radio',
            'is_required' => 1,
            'options' => ['گزینه اول', 'گزینه دوم'],
        ])->assertRedirect(route('customer-forms.builder', $form));

        $question = $form->fresh()->draftVersion->questions->first();
        $this->actingAs($admin)->post(route('customer-forms.publish', $form))->assertRedirect();

        $this->assertNotNull($form->fresh()->published_version_id);
        $this->assertNull($form->fresh()->draft_version_id);
        $this->assertSame(['گزینه اول', 'گزینه دوم'], $question->options()->pluck('label')->all());
    }

    public function test_assignment_actions_reuse_without_reactivation_and_rotate_separately(): void
    {
        $admin = $this->userWithRole('admin');
        $repo = app(InterfaceCustomerForm::class);
        $form = $this->publishedForm($repo);
        $store = $this->store('مشتری');

        $this->actingAs($admin)->post(route('customer-forms.assignments.store'), [
            'form_id' => $form->id,
            'store_id' => $store->id,
        ])->assertRedirect(route('customer-forms.assignments.index'));

        $assignment = $form->assignments()->firstOrFail();
        $oldToken = $assignment->token_encrypted;
        $this->actingAs($admin)->patch(route('customer-forms.assignments.toggle', $assignment))->assertRedirect();

        $this->actingAs($admin)->post(route('customer-forms.assignments.store'), [
            'form_id' => $form->id,
            'store_id' => $store->id,
        ]);

        $this->assertFalse($assignment->fresh()->is_active);
        $this->assertSame($oldToken, $assignment->fresh()->token_encrypted);

        $this->actingAs($admin)->post(route('customer-forms.assignments.rotate', $assignment))->assertRedirect();
        $this->assertNotSame($oldToken, $assignment->fresh()->token_encrypted);
        $this->assertFalse($assignment->fresh()->is_active);
    }

    public function test_admin_can_view_historical_submission_with_original_labels(): void
    {
        $admin = $this->userWithRole('admin');
        $repo = app(InterfaceCustomerForm::class);
        $form = $this->publishedForm($repo);
        $assignment = $repo->assign($form, $this->store('فروشگاه تاریخی'))['assignment'];
        $question = $form->publishedVersion->questions->first();
        $submission = FormSubmission::create([
            'form_assignment_id' => $assignment->id,
            'form_version_id' => $assignment->form_version_id,
            'submitted_at' => now(),
        ]);
        FormAnswer::create([
            'form_submission_id' => $submission->id,
            'form_question_id' => $question->id,
            'value' => 'پاسخ تاریخی',
        ]);

        $this->actingAs($admin)
            ->get(route('customer-forms.submissions.show', $submission))
            ->assertOk()
            ->assertSee('سؤال اولیه')
            ->assertSee('پاسخ تاریخی')
            ->assertSee('فروشگاه تاریخی');
    }

    private function publishedForm(InterfaceCustomerForm $repo): Form
    {
        $form = $repo->createForm(['title' => 'فرم تست', 'is_active' => true]);
        $repo->saveQuestion($form, [
            'label' => 'سؤال اولیه',
            'type' => 'text',
            'is_required' => true,
            'options' => [],
        ]);
        $repo->publish($form->fresh());

        return $form->fresh('publishedVersion.questions');
    }

    private function userWithRole(string $role): User
    {
        $user = User::create([
            'name' => $role,
            'mobile' => '09'.fake()->unique()->numerify('#########'),
            'password' => bcrypt('password'),
        ]);
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        $user->assignRole($role);

        return $user;
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
