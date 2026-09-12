<?php

namespace Modules\CustomerForm\Controllers\Web;

use Modules\CustomerForm\Models\FormSubmission;

class SubmissionController
{
    public function index()
    {
        $submissions = FormSubmission::with(['assignment.form', 'assignment.store.user', 'formVersion'])->latest('submitted_at')->paginate(15);

        return view('templates.customer-forms.submissions.index', compact('submissions'));
    }

    public function show(FormSubmission $submission)
    {
        $submission->load([
            'assignment.form',
            'assignment.store.user',
            'formVersion.questions.options',
            'answers.question.options',
        ]);
        $answers = $submission->answers->keyBy('form_question_id');

        return view('templates.customer-forms.submissions.show', compact('submission', 'answers'));
    }
}
