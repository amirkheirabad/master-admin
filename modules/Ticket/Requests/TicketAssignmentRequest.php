<?php

namespace Modules\Ticket\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') === true;
    }

    public function rules(): array
    {
        return [
            'team_id' => [
                'required_if:status,5',
                'prohibited_unless:status,5',
                Rule::exists('teams', 'id'),
            ],
            'status' => ['sometimes', 'integer', Rule::in([5])],
            'assigned_to' => [
                'required_without:status',
                'prohibited_with:status',
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('type', 1)
                    ->whereNull('deleted_at')),
            ],
        ];
    }
}
