<?php

namespace Modules\Factor\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFactor extends FormRequest
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
            'store_id' => 'required_without_all:phone,name|nullable|exists:stores,id',
            'factor_date' => 'required|string',
            'phone' => 'required_without:store_id|nullable|regex:/(09)[0-9]{9}/|digits:11',
            'name' => 'required_without:store_id|nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'national_kod' => 'nullable|string|digits:10|regex:/^[0-9]{10}$/',
            'price' => 'required|integer|min:0',
            'show_status' => 'required|boolean',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'store_id.required_without_all' => 'فیلد فروشگاه یا شماره تماس و نام و نام خانوادگی باید حداقل یکی پر شود.',

            'phone.required_without'        => 'در صورت عدم انتخاب فروشگاه، وارد کردن شماره تماس الزامی است.',
            'phone.string'                  => 'شماره تماس باید رشته باشد.',
            'phone.regex' => 'شماره تماس باید با 09 شروع شود و 11 رقم باشد .',
            'phone.digits' => 'شماره تماس باید دقیقاً 11 رقم باشد.',

            'name.required_without'         => 'در صورت عدم انتخاب فروشگاه، وارد کردن نام الزامی است.',
            'name.string'                   => 'نام باید رشته باشد.',
            'name.max'                      => 'نام نباید بیش از 255 کاراکتر باشد.',

            'category_id.required'          => 'انتخاب دسته‌بندی الزامی است.',

            'factor_date.required'          => 'انتخاب تاریخ فاکتور الزامی است.',

            'national_kod.string'           => 'کد ملی باید رشته باشد.',
            'national_kod.digits' => 'کد ملی باید دقیقاً 10 رقم باشد.',
            'national_kod.regex' => 'کد ملی باید فقط شامل اعداد باشد.',

            'price.required'                => 'وارد کردن قیمت الزامی است.',
            'price.integer'                 => 'قیمت باید یک عدد صحیح باشد.',
            'price.min'                     => 'قیمت نمی‌تواند کمتر از 0 باشد.',

            'show_status.required'          => 'وضعیت نمایش الزامی است.',

            'description.string'            => 'توضیحات باید رشته باشد.',
            'description.max'               => 'توضیحات نباید بیش از 1000 کاراکتر باشد.',
        ];
    }

}
