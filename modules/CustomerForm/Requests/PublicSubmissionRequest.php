<?php

namespace Modules\CustomerForm\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Modules\CustomerForm\Models\FormAssignment;
use Modules\CustomerForm\Repositories\InterfaceCustomerForm;

class PublicSubmissionRequest extends FormRequest
{
    private FormAssignment $resolvedAssignment;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->resolvedAssignment = app(InterfaceCustomerForm::class)
            ->resolveAssignment((string) $this->route('token'));
    }

    public function rules(): array
    {
        $rules = ['answers' => ['present', 'array']];

        foreach ($this->assignment()->formVersion->questions as $question) {
            $key = 'answers.'.$question->id;
            $presence = $question->is_required ? 'required' : 'nullable';

            $rules[$key] = match ($question->type) {
                'boolean' => [$presence, 'boolean'],
                'select', 'radio' => [$presence, Rule::in($question->options->pluck('id')->all())],
                'checkbox' => array_filter([$presence, 'array', $question->is_required ? 'min:1' : null]),
                default => [$presence, 'string', 'max:10000'],
            };

            if ($question->type === 'checkbox') {
                $rules[$key.'.*'] = ['distinct', Rule::in($question->options->pluck('id')->all())];
            }
        }

        return $rules;
    }

    public function after(): array
    {
        return [function (Validator $validator) {
            $allowed = $this->assignment()->formVersion->questions->modelKeys();
            $submitted = array_map('intval', array_keys((array) $this->input('answers', [])));

            if (array_diff($submitted, $allowed)) {
                $validator->errors()->add('answers', 'پاسخ‌های ارسال‌شده با این نسخه فرم مطابقت ندارند.');
            }
        }];
    }

    public function messages(): array
    {
        return [
            'answers.*.required' => 'لطفاً به این سؤال پاسخ دهید.',
            'answers.*.boolean' => 'پاسخ این سؤال باید بله یا نه باشد.',
            'answers.*.in' => 'گزینه انتخاب‌شده معتبر نیست.',
            'answers.*.*.in' => 'یکی از گزینه‌های انتخاب‌شده معتبر نیست.',
        ];
    }

    public function assignment(): FormAssignment
    {
        return $this->resolvedAssignment;
    }
}
