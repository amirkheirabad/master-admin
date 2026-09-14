<?php

namespace Modules\CustomerForm\Controllers\Web;

use Illuminate\Http\Request;
use Modules\CustomerForm\Models\Form;
use Modules\CustomerForm\Models\FormQuestion;
use Modules\CustomerForm\Repositories\InterfaceCustomerForm;
use Modules\CustomerForm\Requests\QuestionRequest;
use Modules\CustomerForm\Requests\ReorderQuestionsRequest;

class QuestionController
{
    public function __construct(private InterfaceCustomerForm $forms) {}

    public function create(Request $request)
    {
        $form = Form::findOrFail($request->integer('form'));
        $question = null;

        return view('templates.customer-forms.questions.form', compact('form', 'question'));
    }

    public function edit(FormQuestion $question)
    {
        $question->load(['options', 'formVersion.form']);
        $form = $question->formVersion->form;
        $currentVersionId = $form->draft_version_id ?? $form->published_version_id;
        abort_unless($question->form_version_id === $currentVersionId, 404);

        return view('templates.customer-forms.questions.form', compact('form', 'question'));
    }

    public function store(QuestionRequest $request, Form $form)
    {
        $this->forms->saveQuestion($form, $this->data($request));

        return redirect()->route('customer-forms.builder', $form)->with('success', 'سؤال اضافه شد.');
    }

    public function update(QuestionRequest $request, FormQuestion $question)
    {
        $form = $question->formVersion->form;
        $this->forms->saveQuestion($form, $this->data($request), $question);

        return redirect()->route('customer-forms.builder', $form)->with('success', 'سؤال به‌روزرسانی شد.');
    }

    public function destroy(FormQuestion $question)
    {
        $form = $question->formVersion->form;
        $this->forms->deleteQuestion($form, $question);

        return redirect()->route('customer-forms.builder', $form)->with('success', 'سؤال از پیش‌نویس حذف شد.');
    }

    public function reorder(ReorderQuestionsRequest $request, Form $form)
    {
        $this->forms->reorderQuestions($form, $request->validated('question_ids'));

        return redirect()->route('customer-forms.builder', $form);
    }

    private function data(QuestionRequest $request): array
    {
        return $request->safe()->merge(['is_required' => $request->boolean('is_required')])->all();
    }
}
