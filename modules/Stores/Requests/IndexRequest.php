<?php

namespace Modules\Stores\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // تبدیل اعداد فارسی به انگلیسی قبل از اعتبارسنجی
        $this->merge([
            'phone' => $this->convertToEnglishNumber($this->phone),
            'code_posty' => $this->convertToEnglishNumber($this->code_posty),
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
            'store_name' => 'required|string|max:255',
            'user_id' => 'required',
            'link' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
            'province' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'code_posty' => 'required|numeric',
            'about' => 'nullable|string|max:1000',
            'token' => 'required',
            'logo_path' => 'nullable|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'store_name.required' => 'نام فروشگاه الزامی است.',
            'user_id.required' => 'نام مدیر فروشگاه الزامی است.',
            'link.required' => 'لینک فروشگاه الزامی است.',
            'phone.required' => 'شماره تماس الزامی است.',
            'phone.regex' => 'شماره تماس باید با 09 شروع شود و 11 رقم باشد.',
            'province.required' => 'استان الزامی است.',
            'city.required' => 'شهر الزامی است.',
            'location.required' => 'آدرس الزامی است.',
            'code_posty.required' => 'کد پستی الزامی است.',
            'code_posty.numeric' => 'کد پستی باید عدد باشد.',
            'token.required' => 'توکن الزامی است.',
        ];
    }
}
