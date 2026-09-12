# Customer Forms Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build one reusable, versioned customer form system with admin management, secure store-specific public links, one editable current response, and immutable historical responses.

**Architecture:** A single `CustomerForm` module owns form definitions, immutable whole-form versions, assignments, submissions, and answers. Forms point to their one current published version and one current draft; assignments pin a version and point to one current submission while retaining older submissions after explicit upgrades. Public tokens are looked up by SHA-256 hash and retained with Laravel encryption only so admins can copy the URL again.

**Tech Stack:** PHP 8.3.16, Laravel 12, Eloquent, Blade, PHPUnit 11, SQLite in-memory tests, existing Bootstrap/Gentelella assets.

**Spec:** `docs/superpowers/specs/2026-09-12-customer-forms-design.md`

## Global Constraints

- Published form versions, their questions, and their options are immutable.
- A form has at most one current published version and one current draft version.
- Publishing moves only unanswered assignments to the new version.
- Ordinary resubmission updates the existing current submission.
- Only explicit version upgrade creates historical submissions.
- Public URLs expose only a cryptographically random token, never internal IDs.
- Reusing an assignment never implicitly reactivates it.
- Conditional questions, uploads, automatic expiry, drag-and-drop, new dependencies, and unrelated refactors are out of scope.
- Public Persian copy must match the approved specification.

---

### Task 1: Database schema and Eloquent relationships

**Files:**
- Create: `database/migrations/2026_09_12_100000_create_customer_forms_tables.php`
- Create: `modules/CustomerForm/Models/Form.php`
- Create: `modules/CustomerForm/Models/FormVersion.php`
- Create: `modules/CustomerForm/Models/FormQuestion.php`
- Create: `modules/CustomerForm/Models/FormQuestionOption.php`
- Create: `modules/CustomerForm/Models/FormAssignment.php`
- Create: `modules/CustomerForm/Models/FormSubmission.php`
- Create: `modules/CustomerForm/Models/FormAnswer.php`
- Modify: `modules/Stores/Models/Stores.php`
- Test: `tests/Feature/CustomerForm/CustomerFormSchemaTest.php`

**Interfaces:**
- Produces Eloquent relations named `publishedVersion`, `draftVersion`, `versions`, `questions`, `options`, `form`, `formVersion`, `store`, `submissions`, `currentSubmission`, `assignment`, and `answers`.
- `FormAssignment::$casts` decrypts `token_encrypted` with Laravel's `encrypted` cast.
- `FormAnswer::$casts` maps `value` to JSON-compatible PHP values.

- [ ] **Step 1: Write the failing schema test**

```php
<?php

namespace Tests\Feature\CustomerForm;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Modules\CustomerForm\Models\Form;
use Tests\TestCase;

class CustomerFormSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_form_tables_and_version_relations_exist(): void
    {
        foreach (['forms', 'form_versions', 'form_questions', 'form_question_options', 'form_assignments', 'form_submissions', 'form_answers'] as $table) {
            $this->assertTrue(Schema::hasTable($table));
        }

        $form = Form::create(['title' => 'فرم تست']);
        $version = $form->versions()->create(['version_number' => 1]);
        $form->update(['draft_version_id' => $version->id]);

        $this->assertTrue($form->fresh()->draftVersion->is($version));
    }
}
```

- [ ] **Step 2: Run the test and verify the missing-table/class failure**

Run: `php artisan test tests/Feature/CustomerForm/CustomerFormSchemaTest.php`

Expected: FAIL because the CustomerForm models and tables do not exist.

- [ ] **Step 3: Create the migration with the approved constraints**

Create the seven tables in dependency order, then add the two circular pointer groups after their target tables exist:

```php
Schema::table('forms', function (Blueprint $table) {
    $table->foreign('published_version_id')->references('id')->on('form_versions')->nullOnDelete();
    $table->foreign('draft_version_id')->references('id')->on('form_versions')->nullOnDelete();
});

Schema::table('form_assignments', function (Blueprint $table) {
    $table->foreign('current_submission_id')->references('id')->on('form_submissions')->nullOnDelete();
});
```

Use unique constraints on `(form_id, version_number)`, `(form_version_id, sort_order)`, `(form_question_id, sort_order)`, `(form_id, store_id)`, `token_hash`, and `(form_submission_id, form_question_id)`. The `down()` method first removes circular foreign keys and then drops tables in reverse order.

- [ ] **Step 4: Add the seven minimal Eloquent models and store relation**

Use explicit `$fillable`, casts, and typed relations. Add this relation to `Stores`:

```php
public function formAssignments()
{
    return $this->hasMany(FormAssignment::class, 'store_id');
}
```

- [ ] **Step 5: Run the focused schema test**

Run: `php artisan test tests/Feature/CustomerForm/CustomerFormSchemaTest.php`

Expected: PASS.

- [ ] **Step 6: Commit the schema slice**

```bash
git add database/migrations/2026_09_12_100000_create_customer_forms_tables.php modules/CustomerForm/Models modules/Stores/Models/Stores.php tests/Feature/CustomerForm/CustomerFormSchemaTest.php
git commit -m "add customer forms schema"
```

---

### Task 2: Version, publishing, assignment, and upgrade lifecycle

**Files:**
- Create: `modules/CustomerForm/Repositories/InterfaceCustomerForm.php`
- Create: `modules/CustomerForm/Repositories/CustomerFormRepo.php`
- Create: `modules/CustomerForm/CustomerFormServiceProvider.php`
- Modify: `bootstrap/providers.php`
- Test: `tests/Feature/CustomerForm/CustomerFormLifecycleTest.php`

**Interfaces:**
- `createForm(array $data): Form`
- `draft(Form $form): FormVersion`
- `saveQuestion(Form $form, array $data, ?FormQuestion $question = null): FormQuestion`
- `deleteQuestion(Form $form, FormQuestion $question): void`
- `reorderQuestions(Form $form, array $questionIds): void`
- `publish(Form $form): FormVersion`
- `assign(Form $form, Stores $store): array{assignment: FormAssignment, token: ?string}`
- `toggleAssignment(FormAssignment $assignment): FormAssignment`
- `rotateToken(FormAssignment $assignment): string`
- `upgradeAssignment(FormAssignment $assignment): FormAssignment`

- [ ] **Step 1: Write failing lifecycle tests**

Cover these behaviors with real database rows:

```php
public function test_first_structural_edit_clones_published_version_only_once(): void
{
    $form = $this->publishedForm();
    $firstDraft = $this->repo->draft($form);
    $secondDraft = $this->repo->draft($form->fresh());

    $this->assertTrue($firstDraft->is($secondDraft));
    $this->assertSame(2, $firstDraft->version_number);
    $this->assertSame($form->publishedVersion->questions->count(), $firstDraft->questions->count());
}

public function test_publish_moves_only_assignments_without_a_response(): void
{
    [$answered, $unanswered, $newVersion] = $this->publishFixture();

    $this->assertNotSame($newVersion->id, $answered->fresh()->form_version_id);
    $this->assertSame($newVersion->id, $unanswered->fresh()->form_version_id);
}

public function test_reusing_assignment_does_not_reactivate_it(): void
{
    $assignment = $this->assignment(['is_active' => false]);
    $result = $this->repo->assign($assignment->form, $assignment->store);

    $this->assertTrue($result['assignment']->is($assignment));
    $this->assertFalse($assignment->fresh()->is_active);
    $this->assertNull($result['token']);
}

public function test_explicit_upgrade_preserves_old_submission_and_clears_current_pointer(): void
{
    [$assignment, $submission, $latestVersion] = $this->upgradeFixture();
    $this->repo->upgradeAssignment($assignment);

    $assignment->refresh();
    $this->assertNull($assignment->current_submission_id);
    $this->assertSame($latestVersion->id, $assignment->form_version_id);
    $this->assertDatabaseHas('form_submissions', ['id' => $submission->id]);
}
```

Also assert that published questions cannot be mutated/deleted and publishing rejects an empty form or an option-based question with no options.

- [ ] **Step 2: Run the lifecycle tests and verify repository failures**

Run: `php artisan test tests/Feature/CustomerForm/CustomerFormLifecycleTest.php`

Expected: FAIL because the repository and provider do not exist.

- [ ] **Step 3: Implement the repository contract and transaction boundaries**

Use `DB::transaction()` and `lockForUpdate()` for `draft`, `publish`, `assign`, `rotateToken`, and `upgradeAssignment`. Clone questions and options with direct Eloquent creates. Generate tokens with:

```php
$token = Str::random(64);
$attributes = [
    'token_hash' => hash('sha256', $token),
    'token_encrypted' => $token,
];
```

`assign()` uses `firstOrCreate` semantics keyed by `(form_id, store_id)` and returns `token => null` for an existing assignment. It must not update `is_active` on reuse.

`publish()` updates only assignments with `current_submission_id IS NULL`. `upgradeAssignment()` requires the form's current published version to differ from the assignment version and never deletes the old submission.

- [ ] **Step 4: Register the repository binding and module provider**

Bind `InterfaceCustomerForm` to `CustomerFormRepo`, load the module web routes, and add `Modules\CustomerForm\CustomerFormServiceProvider::class` to `bootstrap/providers.php`.

- [ ] **Step 5: Run lifecycle and schema tests**

Run: `php artisan test tests/Feature/CustomerForm`

Expected: PASS.

- [ ] **Step 6: Commit the lifecycle slice**

```bash
git add modules/CustomerForm/Repositories modules/CustomerForm/CustomerFormServiceProvider.php bootstrap/providers.php tests/Feature/CustomerForm/CustomerFormLifecycleTest.php
git commit -m "add customer form lifecycle"
```

---

### Task 3: Secure public form and editable submission flow

**Files:**
- Create: `modules/CustomerForm/Requests/PublicSubmissionRequest.php`
- Create: `modules/CustomerForm/Controllers/Web/PublicFormController.php`
- Create: `modules/CustomerForm/web.php`
- Create: `resources/views/templates/customer-forms/public/show.blade.php`
- Create: `resources/views/templates/customer-forms/public/success.blade.php`
- Modify: `modules/CustomerForm/Repositories/InterfaceCustomerForm.php`
- Modify: `modules/CustomerForm/Repositories/CustomerFormRepo.php`
- Test: `tests/Feature/CustomerForm/PublicFormSubmissionTest.php`

**Interfaces:**
- `resolveAssignment(string $token): FormAssignment`
- `submit(FormAssignment $assignment, array $answers): FormSubmission`
- Public route parameter name: `{token}`.
- Request payload shape: `answers[<question-id>]` with scalar or array values based on question type.

- [ ] **Step 1: Write failing public flow tests**

```php
public function test_valid_token_creates_one_submission_then_updates_it(): void
{
    [$assignment, $token, $question] = $this->publicFixture();

    $this->post(route('customer-forms.public.submit', $token), [
        'answers' => [$question->id => 'پاسخ اول'],
    ])->assertRedirect();

    $submissionId = $assignment->fresh()->current_submission_id;

    $this->post(route('customer-forms.public.submit', $token), [
        'answers' => [$question->id => 'پاسخ ویرایش‌شده'],
    ])->assertRedirect();

    $this->assertSame(1, FormSubmission::where('form_assignment_id', $assignment->id)->count());
    $this->assertSame($submissionId, $assignment->fresh()->current_submission_id);
    $this->assertDatabaseHas('form_answers', ['form_submission_id' => $submissionId]);
}

public function test_inactive_rotated_and_unknown_tokens_return_not_found(): void
{
    [$assignment, $oldToken] = $this->inactiveAndRotatedFixture();

    $this->get('/f/not-a-token')->assertNotFound();
    $this->get('/f/'.$oldToken)->assertNotFound();
    $this->get('/f/'.$assignment->token_encrypted)->assertOk();
}
```

Add tests for prefilled saved answers, required Persian validation, cross-version question IDs, and foreign option IDs.

- [ ] **Step 2: Run the public tests and verify route/controller failures**

Run: `php artisan test tests/Feature/CustomerForm/PublicFormSubmissionTest.php`

Expected: FAIL because public routes and submission handling do not exist.

- [ ] **Step 3: Implement token resolution and dynamic validation**

Resolve with `FormAssignment::where('token_hash', hash('sha256', $token))`, eager-load the pinned version, questions, options, current answers, form, and store. Return 404 for inactive assignments or inactive forms.

`PublicSubmissionRequest::prepareForValidation()` resolves the route token through `InterfaceCustomerForm`, stores the resulting assignment on the request, and exposes it through `assignment(): FormAssignment`. `rules()` derives rules only from that assignment version. Use `Rule::in($question->options->pluck('id'))` for option values, `array` plus `answers.<id>.*` for checkbox questions, `boolean` for boolean questions, and `string|max:10000` for text values. An `after` validator rejects keys outside the version's question IDs. The controller passes `$request->assignment()` and `$request->validated('answers')` to the repository.

- [ ] **Step 4: Implement transactional create-or-update submission behavior**

Lock the assignment, recheck access/version, create the current submission only when its pointer is null, update or create non-empty answers, remove omitted optional answers, and update `submitted_at`. Never create a new submission during ordinary resubmission.

- [ ] **Step 5: Add the public controller, routes, and RTL views**

Use throttled GET/POST routes, standard CSRF, `noindex,nofollow`, normal Blade controls for all six question types, `old()` fallback to saved values, inline errors, and the approved Persian submit/success text. The URL and form action contain only `{token}`.

- [ ] **Step 6: Run public and lifecycle tests**

Run: `php artisan test tests/Feature/CustomerForm`

Expected: PASS.

- [ ] **Step 7: Commit the public flow slice**

```bash
git add modules/CustomerForm/Requests/PublicSubmissionRequest.php modules/CustomerForm/Controllers/Web/PublicFormController.php modules/CustomerForm/web.php modules/CustomerForm/Repositories resources/views/templates/customer-forms/public tests/Feature/CustomerForm/PublicFormSubmissionTest.php
git commit -m "add public customer forms"
```

---

### Task 4: Admin CRUD, builder, assignments, and response views

**Files:**
- Create: `modules/CustomerForm/Requests/StoreFormRequest.php`
- Create: `modules/CustomerForm/Requests/QuestionRequest.php`
- Create: `modules/CustomerForm/Requests/AssignmentRequest.php`
- Create: `modules/CustomerForm/Requests/ReorderQuestionsRequest.php`
- Create: `modules/CustomerForm/Controllers/Web/FormController.php`
- Create: `modules/CustomerForm/Controllers/Web/QuestionController.php`
- Create: `modules/CustomerForm/Controllers/Web/AssignmentController.php`
- Create: `modules/CustomerForm/Controllers/Web/SubmissionController.php`
- Create: `resources/views/templates/customer-forms/forms/index.blade.php`
- Create: `resources/views/templates/customer-forms/forms/form.blade.php`
- Create: `resources/views/templates/customer-forms/forms/builder.blade.php`
- Create: `resources/views/templates/customer-forms/questions/form.blade.php`
- Create: `resources/views/templates/customer-forms/assignments/index.blade.php`
- Create: `resources/views/templates/customer-forms/submissions/index.blade.php`
- Create: `resources/views/templates/customer-forms/submissions/show.blade.php`
- Modify: `modules/CustomerForm/web.php`
- Modify: `modules/CustomerForm/Repositories/InterfaceCustomerForm.php`
- Modify: `modules/CustomerForm/Repositories/CustomerFormRepo.php`
- Test: `tests/Feature/CustomerForm/CustomerFormAdminTest.php`

**Interfaces:**
- Forms are paginated newest-first.
- Assignments eager-load `form`, `formVersion`, `store.user`, and `currentSubmission`.
- Submission detail eager-loads `assignment.store.user`, `formVersion.questions.options`, and `answers.question.options`.
- Admin writes redirect with Persian success/error flash messages.

- [ ] **Step 1: Write failing authorization and CRUD tests**

Create users with Spatie `admin` and `seller` roles inside tests. Assert guests redirect to login, sellers receive 403, and admins can:

```php
$this->actingAs($admin)
    ->post(route('customer-forms.store'), ['title' => 'فرم جدید', 'is_active' => 1])
    ->assertRedirect(route('customer-forms.index'));

$this->assertDatabaseHas('forms', ['title' => 'فرم جدید']);
$this->assertDatabaseHas('form_versions', ['version_number' => 1]);
```

Add tests for question/options CRUD on a draft, reorder, publish, assignment reuse without reactivation, access toggle, token rotation, explicit upgrade, and historical response rendering.

- [ ] **Step 2: Run admin tests and verify missing-route failures**

Run: `php artisan test tests/Feature/CustomerForm/CustomerFormAdminTest.php`

Expected: FAIL because the admin routes and controllers do not exist.

- [ ] **Step 3: Implement the four FormRequests**

Authorize only admin users. Validate form metadata, the six allowed question types, option labels for option-based types, assignment form/store existence, and reorder arrays whose IDs belong to the draft. Use Persian validation messages for admin-facing fields.

- [ ] **Step 4: Implement thin admin controllers and named routes**

Add all routes from the specification inside the admin middleware group. Controllers call the repository lifecycle methods, paginate reads, and redirect to list or builder views. Use route model binding for authenticated internal IDs.

- [ ] **Step 5: Build the admin Blade screens**

Reuse `layouts.admin.master`, existing `x_panel`, table, form-control, button, pagination, and SweetAlert conventions. Keep options on the question form. Use regular forms with CSRF/method fields for move, publish, access toggle, rotation, and upgrade; no new dependency or global CSS.

Assignment buttons must read «غیرفعال‌کردن دسترسی» or «فعال‌کردن دسترسی». «ساخت لینک جدید» is separate and invalidates the old token.

- [ ] **Step 6: Run all CustomerForm feature tests**

Run: `php artisan test tests/Feature/CustomerForm`

Expected: PASS.

- [ ] **Step 7: Commit the admin slice**

```bash
git add modules/CustomerForm/Controllers modules/CustomerForm/Requests modules/CustomerForm/web.php modules/CustomerForm/Repositories resources/views/templates/customer-forms tests/Feature/CustomerForm/CustomerFormAdminTest.php
git commit -m "add customer forms admin"
```

---

### Task 5: Initial Persian forms and sidebar navigation

**Files:**
- Create: `database/seeders/CustomerFormSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`
- Modify: `resources/views/layouts/admin/sections/sidebar.blade.php`
- Test: `tests/Feature/CustomerForm/CustomerFormSeederTest.php`

**Interfaces:**
- `CustomerFormSeeder::run(): void` creates both approved forms only when their exact title is absent.
- Each created form has version 1 published, no draft, and the exact approved ordered questions/options.

- [ ] **Step 1: Write the failing idempotent seeder test**

```php
public function test_seeder_creates_two_published_forms_without_mutating_them_on_rerun(): void
{
    $this->seed(CustomerFormSeeder::class);
    $form = Form::where('title', 'فرم اطلاعات اولیه مشتری')->firstOrFail();
    $versionId = $form->published_version_id;

    $this->seed(CustomerFormSeeder::class);

    $this->assertSame(2, Form::count());
    $this->assertSame($versionId, $form->fresh()->published_version_id);
    $this->assertSame(12, $form->publishedVersion->questions()->count());
}
```

Also assert the graphic design form has 12 questions and the approved option labels/order.

- [ ] **Step 2: Run the seeder test and verify the missing-class failure**

Run: `php artisan test tests/Feature/CustomerForm/CustomerFormSeederTest.php`

Expected: FAIL because `CustomerFormSeeder` does not exist.

- [ ] **Step 3: Implement the idempotent seeder**

Return immediately for each title already present. For an absent title, create the form, version 1, ordered questions/options, set `published_at`, and set `published_version_id` in one transaction. Add `CustomerFormSeeder::class` to `DatabaseSeeder` after permission/user seeders.

- [ ] **Step 4: Add admin-only sidebar navigation**

Add one «فرم‌های مشتری» group under the existing admin-only navigation with links to form, assignment, and submission indexes. Do not change global styles or unrelated menu items.

- [ ] **Step 5: Run CustomerForm tests**

Run: `php artisan test tests/Feature/CustomerForm`

Expected: PASS.

- [ ] **Step 6: Commit seed data and navigation**

```bash
git add database/seeders/CustomerFormSeeder.php database/seeders/DatabaseSeeder.php resources/views/layouts/admin/sections/sidebar.blade.php tests/Feature/CustomerForm/CustomerFormSeederTest.php
git commit -m "seed initial customer forms"
```

---

### Task 6: Full verification and cleanup

**Files:**
- Modify only files from Tasks 1–5 if verification reveals a defect.

**Interfaces:**
- The completed feature is accepted only when focused tests, the full suite, migrations, route registration, and formatting all pass.

- [ ] **Step 1: Run focused tests**

Run: `php artisan test tests/Feature/CustomerForm`

Expected: all CustomerForm tests PASS.

- [ ] **Step 2: Run the complete PHPUnit suite**

Run: `composer test`

Expected: all unit and feature tests PASS.

- [ ] **Step 3: Verify migration rollback and rerun in the test environment**

Run: `php artisan migrate:fresh --env=testing --force`

Expected: all migrations complete successfully, including circular foreign-key setup.

- [ ] **Step 4: Verify registered routes**

Run: `php artisan route:list --name=customer-forms`

Expected: every approved admin and public route appears with the intended middleware.

- [ ] **Step 5: Format changed PHP files**

Run: `vendor/bin/pint modules/CustomerForm database/migrations/2026_09_12_100000_create_customer_forms_tables.php database/seeders/CustomerFormSeeder.php tests/Feature/CustomerForm`

Expected: Pint exits successfully.

- [ ] **Step 6: Re-run focused and full tests after formatting**

Run: `php artisan test tests/Feature/CustomerForm`

Then run: `composer test`

Expected: both commands PASS with no warnings or errors.

- [ ] **Step 7: Inspect the final diff**

Run: `git diff --check`

Then run: `git status --short`

Expected: no whitespace errors and only planned CustomerForm, provider, store relation, seeder, sidebar, test, spec, and plan files are changed.

- [ ] **Step 8: Commit any verification-only fixes**

If Step 1–7 required a code correction, commit only planned paths with:

```bash
git add modules/CustomerForm database/migrations/2026_09_12_100000_create_customer_forms_tables.php database/seeders/CustomerFormSeeder.php database/seeders/DatabaseSeeder.php bootstrap/providers.php modules/Stores/Models/Stores.php resources/views/templates/customer-forms resources/views/layouts/admin/sections/sidebar.blade.php tests/Feature/CustomerForm
git commit -m "fix customer forms verification"
```
