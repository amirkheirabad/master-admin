<?php

namespace Modules\Message\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
            'order' => 'integer|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان پیام الزامی است',
            'content.required' => 'متن پیام الزامی است',
        ];
    }
}