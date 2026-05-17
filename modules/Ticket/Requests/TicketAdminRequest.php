<?php

namespace Modules\Ticket\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // تبدیل اعداد فارسی به انگلیسی قبل از اعتبارسنجی
    protected function prepareForValidation()
    {
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        $this->merge([
            'captcha' => str_replace($persianNumbers, $englishNumbers, $this->captcha),
        ]);
    }

    public function rules()
    {
        return [
            'store_id' => 'required',
            'contact_name' => 'required',
            'title' => 'required|string|max:255',
            'message' => 'required|string|min:3',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2000',
            'captcha' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'store_id.required' => 'انتخاب فروشگاه الزامی است',
            'contact_name.required' => 'انتخاب تیم ارسال کننده الزامی است',
            'title.required' => 'عنوان تیکت الزامی است',
            'message.required' => 'متن تیکت الزامی است',
            'message.min' => 'متن تیکت باید حداقل ۳ کاراکتر باشد',
            'captcha.required' => 'کد امنیتی الزامی است',
            'captcha.numeric' => 'کد امنیتی باید عدد باشد',
        ];
    }
}