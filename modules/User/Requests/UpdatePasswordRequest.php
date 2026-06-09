<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'password' => $this->convertToEnglishNumber($this->password),
            'password_confirmation' => $this->convertToEnglishNumber($this->password_confirmation),
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
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'رمز عبور الزامی است',
            'password.min' => 'رمز عبور باید حداقل ۶ کاراکتر باشد',
            'password.confirmed' => 'تکرار رمز عبور با رمز عبور جدید مطابقت ندارد',
        ];
    }
}
