<?php

namespace Modules\Stores\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuickCreateSellerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'mobile' => $this->convertToEnglishNumber($this->mobile),
            'password' => $this->convertToEnglishNumber($this->password),
        ]);
    }

    private function convertToEnglishNumber($number)
    {
        if (!$number) return $number;

        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($persianNumbers, $englishNumbers, $number);
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'mobile'   => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile'],
            'password' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'وارد کردن نام الزامی است',
            'name.string'       => 'نام باید متن باشد',
            'name.max'          => 'نام نباید بیشتر از ۲۵۵ کاراکتر باشد',

            'mobile.required'   => 'شماره تماس الزامی است',
            'mobile.regex'      => 'شماره تماس باید با 09 شروع شود و 11 رقم باشد (مثال: 09123456789)',
            'mobile.unique'     => 'این شماره تماس قبلاً ثبت شده است',

            'password.required' => 'رمز عبور الزامی است',
            'password.min'      => 'رمز عبور باید حداقل ۶ کاراکتر باشد',
        ];
    }
}
