<?php

namespace Modules\Ticket\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketReplyRequest extends FormRequest
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
            'message' => 'required|string',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,webp,svg,ico,pdf,zip|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'message.required' => 'فیلد پیام الزامی است',


            'attachments.max' => 'حداکثر می‌توانید ۵ فایل پیوست کنید',
            'attachments.*.file' => 'فایل پیوست شده معتبر نیست',
            'attachments.*.mimes' => 'فرمت فایل باید :values باشد',
            'attachments.*.max' => 'حجم هر فایل نباید بیشتر از ۲ مگابایت باشد',
        ];
    }
}
