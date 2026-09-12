<?php

namespace Modules\CustomerForm\Controllers\Web;

use Modules\CustomerForm\Repositories\InterfaceCustomerForm;
use Modules\CustomerForm\Requests\PublicSubmissionRequest;

class PublicFormController
{
    public function __construct(private InterfaceCustomerForm $forms) {}

    public function show(string $token)
    {
        $assignment = $this->forms->resolveAssignment($token);
        $answers = $assignment->currentSubmission?->answers->pluck('value', 'form_question_id') ?? collect();

        return view('templates.customer-forms.public.show', compact('assignment', 'answers', 'token'));
    }

    public function submit(PublicSubmissionRequest $request, string $token)
    {
        $this->forms->submit($request->assignment(), $request->validated('answers', []));

        return redirect()->route('customer-forms.public.show', $token)
            ->with('success', 'پاسخ‌های شما ذخیره شد');
    }
}
