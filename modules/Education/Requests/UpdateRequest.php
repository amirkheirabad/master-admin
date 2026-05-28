<?php

namespace Modules\Education\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|exists:video_categories,id',
        ];
    }

    public function messages(): array
    {
        {
            return [
                'title.required' => 'وارد کردن عنوان ویدئو الزامی است',
                'description.max' => 'توضیحات ویدئو نباید بیشتر از ۱۰۰۰ کاراکتر باشد',
                'category_id.required' => 'انتخاب دسته‌بندی ویدئو الزامی است',
                'category_id.exists' => 'دسته‌بندی انتخاب شده معتبر نیست',
            ];
        }
    }

}
