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
        ];
    }
}
