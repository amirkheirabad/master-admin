<?php

namespace Modules\FAQ\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FAQRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'question' => 'required|max:255',
            'answer' => 'required',
        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
