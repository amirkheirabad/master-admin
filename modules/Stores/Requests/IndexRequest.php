<?php

namespace Modules\Stores\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'store_name' => 'required|string|max:255',
            'manager_name' => 'required|string|max:255',
            'link' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'code_posty' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'about' => 'nullable|string|max:1000',
            'token' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'store_name.required' => 'نام فروشگاه الزامی است.',
            'manager_name.required' => 'نام مدیر فروشگاه الزامی است.',
            'link.required' => 'لینک فروشگاه الزامی است.',
            'phone.required' => 'شماره تماس الزامی است.',
            'province.required' => 'استان الزامی است.',
            'city.required' => 'شهر الزامی است.',
            'location.required' => 'آدرس الزامی است.',
            'code_posty.required' => 'کد پستی الزامی است.',
            'latitude.required' => 'عرض جغرافیایی الزامی است.',
            'longitude.required' => 'طول جغرافیایی الزامی است.',
            'token.required' => 'توکن الزامی است.',
        ];
    }
}
