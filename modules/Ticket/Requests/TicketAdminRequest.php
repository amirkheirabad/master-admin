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
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,png,pdf|max:2048',
            'captcha' => 'required|numeric',
            'priority' => 'required|in:1,2,3,4',

        ];
    }

    public function messages()
    {
        return [
            'store_id.required' => 'فیلد فروشگاه الزامی است',
            'contact_name.required' => 'فیلد نام تماس گیرنده الزامی است',
            'title.required' => 'فیلد عنوان الزامی است',
            'message.required' => 'فیلد پیام الزامی است',
            'attachments.max' => 'حداکثر می‌توانید ۵ فایل پیوست کنید',
            'attachments.*.file' => 'فایل پیوست شده معتبر نیست',
            'attachments.*.mimes' => 'فرمت فایل باید :values باشد',
            'attachments.*.max' => 'حجم هر فایل نباید بیشتر از ۲ مگابایت باشد',
            'captcha.required' => 'کد امنیتی را وارد نکردید',
             'priority.required' => 'انتخاب اولویت الزامی است',
             'priority.in' => 'اولویت نامعتبر است',

        ];
    }
}
