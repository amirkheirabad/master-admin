<?php

namespace Modules\Ticket\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketAdminRequest extends FormRequest
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
            'store_id' => 'required', // یا store_id اگر اسمش فرق داره
            'contact_name' => 'required',
            'title' => 'required|string|max:255',
            'message' => 'required|string|min:3',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,png,pdf|max:2048',
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
        ];
    }
}
