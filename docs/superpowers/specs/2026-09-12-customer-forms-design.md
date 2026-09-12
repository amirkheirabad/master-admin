# Customer Forms Design

## Goal

Add one reusable customer intake form system for defining versioned forms, assigning a form to a store through a secure public link, collecting one editable current response, and preserving historical responses against the exact published question structure they used.

The first release includes two seeded forms: «فرم اطلاعات اولیه مشتری» and «فرم طراحی گرافیک دیزاین». Future forms such as SEO intake use the same tables and module.

## Existing Project Conventions

- Laravel 12 and PHP 8.2.
- Feature code lives under `modules/<Feature>` and is registered in `bootstrap/providers.php`.
- Web routes are loaded by each module service provider.
- Admin access is enforced with `check.login` and `check.role:admin`.
- Controllers delegate persistence to module repositories and use FormRequests for validation.
- Admin views extend `layouts.admin.master` and live under `resources/views/templates`.
- Navigation is defined in `resources/views/layouts/admin/sections/sidebar.blade.php`.
- No CodeGraph index exists in this repository.

## Scope

The system includes:

- Admin CRUD for forms, draft questions, and draft question options.
- Publishing immutable whole-form versions.
- Question ordering with accessible move-up and move-down controls.
- Assigning a published form to a store.
- A reusable public link that needs no customer login.
- One editable current submission for each assignment.
- Admin lists and detail views for current and historical submissions.
- Explicit assignment version upgrades that preserve the previous submission as history.
- Initial published versions for the two approved Persian forms.

The first release excludes conditional questions, file uploads, automatic link expiry, drag-and-drop ordering, and polymorphic customer ownership. These are not required by the current forms and can be added without replacing the data model.

## Module Structure

```text
modules/CustomerForm/
├── Controllers/Web/
│   ├── FormController.php
│   ├── QuestionController.php
│   ├── AssignmentController.php
│   ├── SubmissionController.php
│   └── PublicFormController.php
├── Models/
│   ├── Form.php
│   ├── FormVersion.php
│   ├── FormQuestion.php
│   ├── FormQuestionOption.php
│   ├── FormAssignment.php
│   ├── FormSubmission.php
│   └── FormAnswer.php
├── Repositories/
│   ├── InterfaceCustomerForm.php
│   └── CustomerFormRepo.php
├── Requests/
│   ├── StoreFormRequest.php
│   ├── QuestionRequest.php
│   ├── AssignmentRequest.php
│   ├── ReorderQuestionsRequest.php
│   └── PublicSubmissionRequest.php
├── CustomerFormServiceProvider.php
└── web.php
```

The repository owns transactional lifecycle operations: creating a draft, cloning a published version, publishing, assigning, token rotation, public submission, and explicit version upgrade. Controllers remain responsible for HTTP concerns and rendering. A separate service layer is unnecessary because there is only one persistence implementation and one lifecycle boundary.

Views live under:

```text
resources/views/templates/customer-forms/
├── forms/index.blade.php
├── forms/form.blade.php
├── forms/builder.blade.php
├── questions/form.blade.php
├── assignments/index.blade.php
├── submissions/index.blade.php
├── submissions/show.blade.php
└── public/
    ├── show.blade.php
    └── success.blade.php
```

## Database Design

### `forms`

| Column | Type | Rules |
|---|---|---|
| `id` | bigint | primary key |
| `title` | string | required |
| `description` | text | nullable |
| `is_active` | boolean | default true |
| `published_version_id` | bigint | nullable FK to `form_versions.id` |
| `draft_version_id` | bigint | nullable FK to `form_versions.id` |
| timestamps | timestamps | required |

The two version pointers define the only current published version and the only current draft. A version referenced by neither pointer is historical. The pointer foreign keys use `nullOnDelete`; deleting a draft clears its pointer, while application rules prevent deleting published or referenced history.

### `form_versions`

| Column | Type | Rules |
|---|---|---|
| `id` | bigint | primary key |
| `form_id` | bigint | FK to `forms.id`, restrict delete |
| `version_number` | unsigned integer | required |
| `published_at` | timestamp | nullable |
| timestamps | timestamps | required |

Unique constraint: `(form_id, version_number)`.

`published_at !== null` makes a version immutable forever. The current status is derived from the parent form pointers rather than a mutable status column.

### `form_questions`

| Column | Type | Rules |
|---|---|---|
| `id` | bigint | primary key |
| `form_version_id` | bigint | FK to `form_versions.id` |
| `label` | string | required |
| `type` | string | `text`, `textarea`, `select`, `radio`, `checkbox`, or `boolean` |
| `is_required` | boolean | default false |
| `sort_order` | unsigned integer | required |
| timestamps | timestamps | required |

Unique constraint: `(form_version_id, sort_order)`.

Questions cascade when an unpublished draft is deleted. Published versions cannot be deleted by application policy, and answer foreign keys protect referenced questions.

### `form_question_options`

| Column | Type | Rules |
|---|---|---|
| `id` | bigint | primary key |
| `form_question_id` | bigint | FK to `form_questions.id` |
| `label` | string | required |
| `sort_order` | unsigned integer | required |
| timestamps | timestamps | required |

Unique constraint: `(form_question_id, sort_order)`.

`boolean` questions use native boolean values and the Persian labels «بله» and «نه» without option rows. `select`, `radio`, and `checkbox` questions must have options before publish.

### `form_assignments`

| Column | Type | Rules |
|---|---|---|
| `id` | bigint | primary key |
| `form_id` | bigint | FK to `forms.id`, restrict delete |
| `form_version_id` | bigint | FK to `form_versions.id`, restrict delete |
| `store_id` | bigint | FK to `stores.id`, restrict delete |
| `token_hash` | char(64) | unique |
| `token_encrypted` | text | required |
| `current_submission_id` | bigint | nullable FK to `form_submissions.id` |
| `is_active` | boolean | default true |
| timestamps | timestamps | required |

Unique constraint: `(form_id, store_id)`.

The current version is pinned on the assignment. New assignments use the form's current published version. Publishing updates this value only for assignments whose `current_submission_id` is null.

`current_submission_id` is added after `form_submissions` is created. It provides one authoritative current response while allowing older submissions to remain as history after an explicit upgrade. Repository transactions ensure the pointed submission belongs to the same assignment and version.

### `form_submissions`

| Column | Type | Rules |
|---|---|---|
| `id` | bigint | primary key |
| `form_assignment_id` | bigint | FK to `form_assignments.id`, restrict delete |
| `form_version_id` | bigint | FK to `form_versions.id`, restrict delete |
| `submitted_at` | timestamp | required |
| timestamps | timestamps | required |

The first valid public POST creates a submission and assigns its ID to `form_assignments.current_submission_id` in the same transaction. Later public POSTs update that submission and its answers. A second submission is created only after an explicit version upgrade has cleared the current pointer.

### `form_answers`

| Column | Type | Rules |
|---|---|---|
| `id` | bigint | primary key |
| `form_submission_id` | bigint | FK to `form_submissions.id`, cascade delete |
| `form_question_id` | bigint | FK to `form_questions.id`, restrict delete |
| `value` | JSON | required |
| timestamps | timestamps | required |

Unique constraint: `(form_submission_id, form_question_id)`.

Value representation:

- `text` and `textarea`: JSON string.
- `boolean`: JSON boolean.
- `select` and `radio`: one option ID from that question.
- `checkbox`: an array of option IDs from that question.

Option labels are resolved through the immutable option rows from the submission version. Labels are not duplicated into answers.

## Version Lifecycle

### Create

Creating a form also creates version 1 and sets `forms.draft_version_id`. The form is not assignable until version 1 is published.

### Edit

If a draft exists, all structural edits target it. If only a published version exists, the first structural edit clones all questions and options into the next version number and sets `draft_version_id`. Further edits reuse that draft. Published rows are never updated.

Updating the form title, description, or active state changes form metadata and does not mutate versioned question structure.

### Publish

Publishing runs in a transaction with the form row locked:

1. Validate every draft question and its options.
2. Set the draft's `published_at` once.
3. Move the form's `published_version_id` to that version.
4. Clear `draft_version_id`.
5. Move assignments with no current submission to the new version.
6. Leave assignments with a current submission unchanged.

Previously published versions remain immutable historical rows.

### Assign

An admin selects one active published form and one store. The existing `(form_id, store_id)` assignment is reused if present; otherwise it is created with the published version and a new token. Assigning does not remove submissions or answers.

### Submit and Resubmit

The public request resolves the assignment by token hash, checks access, loads the assignment version, and validates answers against only that version. The write occurs in one transaction with the assignment locked.

- No current submission: create one submission and its answers, then set `current_submission_id`.
- Current submission exists: update or create supplied answers on that same submission, delete omitted optional answers, and update `submitted_at`.
- Required questions must be present on every submission.
- The current submission version must equal the assignment version.

### Explicit Upgrade

Only an admin can upgrade an assignment. The operation requires a newer current published version and explicit UI confirmation. In one transaction it:

1. Leaves the old submission and answers unchanged.
2. Changes `form_version_id` on the assignment to the current published version.
3. Clears `current_submission_id`.

The next customer submission creates a new current submission. The previous submission remains visible as historical. No answers are automatically mapped between structurally different versions.

## Token Handling

- Generate a URL-safe cryptographically secure random token with at least 256 bits of entropy.
- Store `hash('sha256', $token)` in the unique `token_hash` column for lookup.
- Store the raw token only through Laravel's encrypted model cast in `token_encrypted`, allowing admins to copy the link later.
- Public URLs have the form `/f/{token}` and contain no store, form, assignment, or submission IDs.
- Rotating a token atomically replaces both token columns. The old URL immediately returns 404.
- Deactivating access sets `is_active = false`; it never changes the token or removes the assignment, submission, or answers.
- Reactivating access sets `is_active = true`, restoring the same public URL.
- Admin actions use the labels «غیرفعال‌کردن دسترسی» and «فعال‌کردن دسترسی». Token rotation is a separate action.

## Routes and Access Control

### Admin routes

All admin routes use `check.login` and `check.role:admin`.

```text
GET    /customer-forms
GET    /customer-forms/create
POST   /customer-forms
GET    /customer-forms/{form}/edit
PUT    /customer-forms/{form}
GET    /customer-forms/{form}/builder
POST   /customer-forms/{form}/publish

POST   /customer-forms/{form}/questions
PUT    /customer-form-questions/{question}
DELETE /customer-form-questions/{question}
POST   /customer-forms/{form}/questions/reorder

GET    /customer-form-assignments
POST   /customer-form-assignments
PATCH  /customer-form-assignments/{assignment}/toggle
POST   /customer-form-assignments/{assignment}/rotate-token
POST   /customer-form-assignments/{assignment}/upgrade

GET    /customer-form-submissions
GET    /customer-form-submissions/{submission}
```

The repository rejects draft edits unless the question belongs to the form's current draft. It also rejects cross-form publish, assignment, submission, and upgrade operations even when a valid internal ID is supplied.

### Public routes

```text
GET  /f/{token}
POST /f/{token}
```

These routes do not use login middleware. They use the `web` middleware group for CSRF protection and Laravel throttling to limit repeated requests. Invalid, inactive, malformed, or mismatched tokens return 404 without revealing whether an assignment exists.

The request rejects unknown question IDs, questions from another version, and option IDs that do not belong to the submitted question. Public pages use `noindex, nofollow` metadata.

## Admin UI

The admin sidebar receives one admin-only «فرم‌های مشتری» group with links to forms, assignments, and responses.

### Forms and builder

- Form list displays active state, published version, draft version, and actions.
- The create/edit screen manages title, description, and active state.
- The builder lists draft questions in order with type and required state.
- Move-up and move-down buttons update order without a new JavaScript dependency.
- Question create/edit manages applicable options in the same screen.
- Delete is offered only for draft questions and options.
- Publish displays validation errors and requires confirmation.

### Assignments

- Admin selects a published form and store.
- List displays form, version, store/customer, response status, and access status.
- Copy link decrypts the stored token for the authenticated admin.
- Access activation/deactivation, token rotation, and version upgrade are separate actions.
- Upgrade is offered only when a newer published version exists.

### Submissions

- List displays form, version, store, related customer, current/historical state, and last edit time.
- Detail renders labels and selected option labels from `form_submission.formVersion`.
- Historical submissions are read-only.

## Public UI

The public form uses a small RTL layout independent of the authenticated admin shell while reusing the project's existing Vite assets and Bootstrap-compatible classes. No global CSS changes are required.

- An unanswered assignment renders empty controls.
- An answered assignment renders the current saved values.
- Validation errors appear beside their questions.
- The submit label is «ذخیره و ارسال پاسخ‌ها».
- Success text is «پاسخ‌های شما ذخیره شد».
- Resubmission updates the same current response.

## Initial Published Forms

An idempotent `CustomerFormSeeder` creates these forms only when absent. Re-running it never mutates an existing published version.

### فرم اطلاعات اولیه مشتری

1. Boolean, required: «فروشگاه‌تون الان سایت فعال داره؟»
2. Text, optional: «اگر سایت دارید، آدرسش رو اینجا بنویسید.»
3. Boolean, required: «دامنه‌ای که می‌خواهید استفاده کنید آماده است؟»
4. Text, optional: «اگر دامنه دارید، آدرس دامنه چیه؟»
5. Boolean, required: «برای کسب‌وکارتون اینماد فعال دارید؟»
6. Boolean, required: «الان درگاه پرداخت فعال دارید؟»
7. Checkbox, optional: «اگر درگاه دارید، از کدوم‌ها استفاده می‌کنید؟» Options: «درگاه مستقیم بانکی»، «زرین‌پال»، «زیبال»، «آیدی‌پی»، «نکست‌پی»، «هنوز انتخاب نکرده‌ام».
8. Checkbox, optional: «دوست دارید فروشگاه‌تون به کدوم سرویس‌ها وصل شود؟» Options: «ترب»، «ایمالز»، «دیجی‌پی»، «اسنپ‌پی»، «شاپینو»، «فعلاً مطمئن نیستم».
9. Select, required: «چطور با ما آشنا شدید؟» Options: «معرفی دوستان یا مشتریان»، «اینستاگرام»، «جست‌وجوی گوگل»، «تماس یا پیام همکاران ما»، «سایر».
10. Textarea, optional: «اگر از یک پلتفرم دیگه میاید، مهم‌ترین دلیل تغییرتون چیه؟»
11. Textarea, required: «مهم‌ترین انتظارتون از سایت جدید چیه؟»
12. Textarea, optional: «نکته دیگه‌ای هست که بهتره قبل از شروع بدونیم؟»

### فرم طراحی گرافیک دیزاین

1. Checkbox, required: «دقیقاً چه چیزی نیاز دارید طراحی شود؟» Options: «لوگو»، «هویت بصری»، «بنر سایت»، «قالب پست یا استوری»، «کاتالوگ یا بروشور»، «بسته‌بندی»، «سایر».
2. Textarea, required: «این طرح قرار است کجا استفاده شود؟»
3. Textarea, required: «هدف اصلی این طراحی چیه؟»
4. Textarea, required: «مخاطب اصلی این طرح چه کسانی هستند؟»
5. Textarea, optional: «چه متن‌ها یا اطلاعاتی باید داخل طرح قرار بگیرد؟»
6. Text, optional: «اگر لوگو یا فایل هویت بصری دارید، لینک دریافتش را بفرستید.»
7. Textarea, optional: «چه رنگ‌هایی را ترجیح می‌دهید یا نمی‌خواهید استفاده شوند؟»
8. Radio, required: «کدوم سبک به چیزی که می‌خواهید نزدیک‌تره؟» Options: «ساده و مینیمال»، «مدرن»، «رسمی»، «صمیمی و پرانرژی»، «لوکس»، «ترجیح مشخصی ندارم».
9. Textarea, optional: «اگر نمونه‌ای دوست دارید، لینک یا توضیحش را بفرستید.»
10. Text, optional: «ابعاد یا خروجی موردنیاز را می‌دانید؟»
11. Text, required: «چه زمانی به طرح نهایی نیاز دارید؟»
12. Textarea, optional: «نکته دیگه‌ای هست که طراح باید بداند؟»

## Error Handling

- FormRequests return normal Laravel validation errors.
- Public validation keeps entered values and displays per-question Persian errors.
- Publish refuses empty forms and option-based questions without options.
- Repository lifecycle writes use database transactions and row locks.
- Token rotation retries generation if the unique token hash collides.
- Invalid bearer tokens return 404 rather than authorization details.
- Database foreign keys and repository checks prevent destructive changes to historical data.

## Testing

Focused feature tests cover:

- Creating a form with draft version 1.
- Cloning the published version exactly once on first structural edit.
- Refusing mutation or deletion of published questions and options.
- Publishing and moving only unanswered assignments.
- Creating the first current submission through a valid public token.
- Updating the same submission on resubmission.
- Loading saved answers on the public form.
- Preserving the old submission and answers during explicit upgrade.
- Resolving historical labels through the old immutable version.
- Rejecting invalid, inactive, and rotated tokens.
- Rejecting questions and options from another version.
- Restricting all admin routes to admin users.

The focused CustomerForm feature suite runs first, followed by the full PHPUnit suite and Laravel Pint on changed PHP files.
