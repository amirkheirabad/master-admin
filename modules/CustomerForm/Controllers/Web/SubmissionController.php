<?php

namespace Modules\CustomerForm\Controllers\Web;

use Illuminate\Http\Request;
use Modules\CustomerForm\Models\Form;
use Modules\CustomerForm\Models\FormSubmission;
use Modules\CustomerForm\Repositories\InterfaceCustomerForm;
use Modules\Stores\Models\Stores;

class SubmissionController
{
    public function __construct(private InterfaceCustomerForm $forms) {}

    public function index(Request $request)
    {
        $submissions = $this->forms->filterSubmissions($request);
        $forms = Form::orderBy('title')->get();
        $stores = Stores::orderBy('store_name')->get();

        return view('templates.customer-forms.submissions.index', compact('submissions', 'forms', 'stores'));
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
