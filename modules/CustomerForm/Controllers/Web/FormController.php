<?php

namespace Modules\CustomerForm\Controllers\Web;

use Illuminate\Http\Request;
use Modules\CustomerForm\Models\Form;
use Modules\CustomerForm\Models\FormQuestion;
use Modules\CustomerForm\Repositories\InterfaceCustomerForm;
use Modules\CustomerForm\Requests\StoreFormRequest;

class FormController
{
    public function __construct(private InterfaceCustomerForm $forms) {}

    public function index()
    {
        $forms = Form::with(['publishedVersion', 'draftVersion'])->latest()->paginate(15);

        return view('templates.customer-forms.forms.index', compact('forms'));
    }

    public function create()
    {
        return view('templates.customer-forms.forms.form');
    }

    public function store(StoreFormRequest $request)
    {
        $this->forms->createForm($request->safe()->merge(['is_active' => $request->boolean('is_active')])->all());

        return redirect()->route('customer-forms.index')->with('success', 'فرم ساخته شد.');
    }

    public function edit(Form $form)
    {
        return view('templates.customer-forms.forms.form', compact('form'));
    }

    public function update(StoreFormRequest $request, Form $form)
    {
        $form->update($request->safe()->merge(['is_active' => $request->boolean('is_active')])->all());

        return redirect()->route('customer-forms.index')->with('success', 'فرم به‌روزرسانی شد.');
    }

    public function builder(Request $request, Form $form)
    {
        $form->load(['draftVersion.questions.options', 'publishedVersion.questions.options']);
        $version = $form->draftVersion ?? $form->publishedVersion;
        $questions = $version?->questions ?? collect();
        $question = null;

        if ($request->filled('question')) {
            $question = FormQuestion::with('options')->findOrFail($request->integer('question'));
            abort_unless($question->form_version_id === $version?->id, 404);
        }

        return view('templates.customer-forms.forms.builder', compact('form', 'version', 'questions', 'question'));
    }

    public function publish(Form $form)
    {
        $this->forms->publish($form);

        return redirect()->route('customer-forms.builder', $form)->with('success', 'نسخه جدید فرم منتشر شد.');
    }
}
