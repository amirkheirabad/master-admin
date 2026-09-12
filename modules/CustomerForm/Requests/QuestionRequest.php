<?php

namespace Modules\CustomerForm\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\CustomerForm\Models\FormQuestion;

class QuestionRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('options_text')) {
            $options = preg_split('/\r\n|\r|\n/', (string) $this->input('options_text'));
            $this->merge(['options' => array_values(array_filter(array_map('trim', $options)))]);
        }
    }

    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') === true;
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(FormQuestion::TYPES)],
            'is_required' => ['nullable', 'boolean'],
            'options' => ['nullable', 'array', 'max:50'],
            'options.*' => ['nullable', 'string', 'max:255'],
            'options_text' => ['nullable', 'string', 'max:10000'],
        ];
    }
}
