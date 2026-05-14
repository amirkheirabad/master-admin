<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users,mobile|regex:/^09[0-9]{9}$/',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'وارد کردن نام الزامی است',
            'mobile.required' => 'شماره تماس الزامی است',
            'mobile.unique' => 'این شماره تماس قبلاً ثبت شده است',
            'mobile.regex' => 'شماره تماس باید با 09 شروع شود و 11 رقم باشد',
            'password.required' => 'رمز عبور الزامی است',
            'password.min' => 'رمز عبور باید حداقل ۶ کاراکتر باشد',
            'role.required' => 'انتخاب نقش الزامی است',
            'role.exists' => 'نقش انتخاب شده معتبر نیست',
        ];
    }
}