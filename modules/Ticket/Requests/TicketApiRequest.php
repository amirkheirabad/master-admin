<?php

namespace Modules\Ticket\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'store_id' => 'required|exists:stores,id',
            'contact_name' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'message' => 'required|string|min:3',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,png,pdf|max:2048',
            'priority' => 'required|in:1,2,3,4',
        ];
    }

    public function messages()
    {
        return [
            'store_id.required' => 'فیلد فروشگاه الزامی است',
            'store_id.exists' => 'فروشگاه انتخاب شده معتبر نیست',
            'contact_name.required' => 'فیلد نام تماس گیرنده الزامی است',
            'contact_name.string' => 'نام تماس گیرنده باید متن باشد',
            'contact_name.max' => 'نام تماس گیرنده نباید بیشتر از ۵۰ کاراکتر باشد',
            'title.required' => 'فیلد عنوان الزامی است',
            'title.string' => 'عنوان باید متن باشد',
            'title.max' => 'عنوان نباید بیشتر از ۲۵۵ کاراکتر باشد',
            'message.required' => 'فیلد پیام الزامی است',
            'message.string' => 'پیام باید متن باشد',
            'message.min' => 'پیام باید حداقل ۳ کاراکتر باشد',
            'attachments.max' => 'حداکثر می‌توانید ۵ فایل پیوست کنید',
            'attachments.*.file' => 'فایل پیوست شده معتبر نیست',
            'attachments.*.mimes' => 'فرمت فایل باید jpg, png, pdf باشد',
            'attachments.*.max' => 'حجم هر فایل نباید بیشتر از ۲ مگابایت باشد',
            'captcha.required' => 'کد امنیتی را وارد نکردید',
            'captcha.numeric' => 'کد امنیتی باید عدد باشد',
            'priority.required' => 'انتخاب اولویت الزامی است',
            'priority.in' => 'اولویت نامعتبر است',
        ];
    }
}
