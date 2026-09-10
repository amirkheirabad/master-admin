<?php

namespace Modules\Stores\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Stores\Models\Stores;

class UpdateCheckListsStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $siteType = Stores::whereKey($this->input('store_id'))->value('site_type');

        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'check_lists' => ['nullable', 'array'],
            'check_lists.*' => [
                'integer',
                'distinct',
                Rule::exists('check_lists', 'id')->where('site_type', $siteType),
            ],
            'comments' => ['nullable', 'array'],
            'comments.*' => ['nullable', 'string', 'max:2000'],
            'report' => ['nullable', 'string'],
        ];
    }
}
