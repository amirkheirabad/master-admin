<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        return [
            'mobile' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.required' => 'شماره موبایل الزامی است',
            'password.required' => 'رمز عبور الزامی است',
        ];
    }

    public function attributes(): array
    {
        return [
            'mobile' => 'شماره موبایل',
            'password' => 'رمز عبور',
        ];
    }
}