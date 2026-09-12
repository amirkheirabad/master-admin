<?php

namespace Modules\CustomerForm\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderQuestionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') === true;
    }

    public function rules(): array
    {
        return [
            'question_ids' => ['required', 'array'],
            'question_ids.*' => ['required', 'integer', 'distinct'],
        ];
    }
}
