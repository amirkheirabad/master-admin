<?php

namespace Modules\Log\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChecklistLogsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'checklist_from' => ['nullable', 'jdate:Y/m/d'],
            'checklist_to' => array_filter(['nullable', 'jdate:Y/m/d', $this->filled('checklist_from') ? 'after_or_equal:checklist_from' : null]),
            'store_id' => ['nullable', 'integer', 'exists:stores,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'checklist_from.jdate' => 'تاریخ شروع باید یک تاریخ شمسی معتبر با فرمت سال/ماه/روز باشد.',
            'checklist_to.jdate' => 'تاریخ پایان باید یک تاریخ شمسی معتبر با فرمت سال/ماه/روز باشد.',
            'checklist_to.after_or_equal' => 'تاریخ پایان باید مساوی یا بعد از تاریخ شروع باشد.',
            'store_id.integer' => 'فروشگاه انتخاب‌شده معتبر نیست.',
            'store_id.exists' => 'فروشگاه انتخاب‌شده وجود ندارد.',
        ];
    }
}
