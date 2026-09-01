<?php

namespace Modules\Stores\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCheckListsStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'check_lists' => ['nullable', 'array'],
            'check_lists.*' => ['integer', 'distinct', 'exists:check_lists,id'],
            'comments' => ['nullable', 'array'],
            'comments.*' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
