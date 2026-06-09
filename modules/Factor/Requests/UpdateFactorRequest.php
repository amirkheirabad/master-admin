<?php

namespace Modules\Factor\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFactorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'phone' => $this->convertToEnglishNumber($this->phone),
            'national_kod' => $this->convertToEnglishNumber($this->national_kod),
            'price' => $this->convertToEnglishNumber($this->price),

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
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'factor_date' => 'required|string',
            'show_status' => 'required|boolean',
            'price_status' => 'required',
            'description' => 'nullable|string|max:1000',
            'paid_factor_date' => 'required_if:price_status,3|string|nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ];
    }

    public function messages(): array
    {
        return [

            'category_id.required'          => 'انتخاب دسته‌بندی الزامی است.',

            'factor_date.required'          => 'انتخاب تاریخ الزامی است.',

            'price.required'                => 'وارد کردن قیمت الزامی است.',
            'price.integer'                 => 'قیمت باید یک عدد صحیح باشد.',
            'price.min'                     => 'قیمت نمی‌تواند کمتر از 0 باشد.',

            'show_status.required'          => 'وضعیت نمایش الزامی است.',

            'description.string'            => 'توضیحات باید رشته باشد.',
            'description.max'               => 'توضیحات نباید بیش از 1000 کاراکتر باشد.',

            'paid_factor_date.required_if' => 'تاریخ فاکتور پرداخت شده زمانی که وضعیت قیمت برابر کارت به کارت است، الزامی می‌باشد.',

        ];
    }
}
