<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * تبدیل اعداد فارسی به انگلیسی قبل از اعتبارسنجی
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'mobile' => $this->convertToEnglishNumber($this->mobile),
        ]);
    }

    /**
     * تبدیل اعداد فارسی به انگلیسی
     */
    private function convertToEnglishNumber($number)
    {
        if (!$number) return $number;
        
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        return str_replace($persianNumbers, $englishNumbers, $number);
    }

    public function rules(): array
    {
        $userId = $this->route('id');
        
        return [
            'name' => 'required|string|max:255',
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile,' . ($userId ?? 'NULL')],
            'password' => 'nullable|min:6',
            'role' => 'required|exists:roles,name'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'وارد کردن نام الزامی است',
            'name.string' => 'نام باید متن باشد',
            'name.max' => 'نام نباید بیشتر از ۲۵۵ کاراکتر باشد',

            'mobile.required' => 'شماره تماس الزامی است',
            'mobile.regex' => 'شماره تماس باید با 09 شروع شود و 11 رقم باشد (مثال: 09123456789)',
            'mobile.unique' => 'این شماره تماس قبلاً ثبت شده است',

            'password.min' => 'رمز عبور باید حداقل ۶ کاراکتر باشد',

            'role.required' => 'انتخاب نقش الزامی است',
            'role.exists' => 'نقش انتخاب شده معتبر نیست',
        ];
    }
}