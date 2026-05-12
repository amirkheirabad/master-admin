<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'permissions'=>'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'وارد کردن نام الزامی است.',

            'permissions.required' => 'انتخاب دسترسی ها الزامی است.',
        ];
    }
}
