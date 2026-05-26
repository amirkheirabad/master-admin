<?php

namespace Modules\Ticket\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $captcha = str_replace($persianNumbers, $englishNumbers, $this->captcha);

        $this->merge([
            'captcha' => $captcha,
        ]);

        if ($captcha != session('captcha_result')) {
            $this->getValidatorInstance()->after(function ($validator) {
                $validator->errors()->add('captcha', 'کد امنیتی اشتباه است');
            });
        }
    }

    public function rules()
    {
        return [
            'recipient_type'  => 'required|in:store,user',

            'store_id' => 'required_if:recipient_type,store|nullable|exists:stores,id',
            'user_id'  => 'required_if:recipient_type,user|nullable|exists:users,id',

            'contact_name' => 'required|string|max:50',
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
            'recipient_type.required' => 'نوع گیرنده الزامی است',
            'recipient_type.in'       => 'نوع گیرنده نامعتبر است',
            'store_id.required_if'    => 'انتخاب فروشگاه الزامی است',
            'store_id.exists'         => 'فروشگاه انتخاب شده معتبر نیست',
            'user_id.required_if'     => 'انتخاب کاربر الزامی است',
            'user_id.exists'          => 'کاربر انتخاب شده معتبر نیست',
            'contact_name.required'   => 'فیلد نام تماس گیرنده الزامی است',
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
