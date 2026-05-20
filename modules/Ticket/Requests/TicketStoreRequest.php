<?php

namespace Modules\Ticket\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketStoreRequest extends FormRequest
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
    public function rules()
    {
        return [
            'store_id' => 'required|exists:stores,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'contact_name' => 'required',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,png,pdf|max:2048',
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
