<?php

namespace Modules\CustomerForm\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') === true;
    }

    public function rules(): array
    {
        return [
            'form_id' => ['required', 'integer', 'exists:forms,id'],
            'store_id' => ['required', 'integer', 'exists:stores,id'],
        ];
    }
}
