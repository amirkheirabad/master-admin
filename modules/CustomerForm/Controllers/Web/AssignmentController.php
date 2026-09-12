<?php

namespace Modules\CustomerForm\Controllers\Web;

use Modules\CustomerForm\Models\Form;
use Modules\CustomerForm\Models\FormAssignment;
use Modules\CustomerForm\Repositories\InterfaceCustomerForm;
use Modules\CustomerForm\Requests\AssignmentRequest;
use Modules\Stores\Models\Stores;

class AssignmentController
{
    public function __construct(private InterfaceCustomerForm $forms) {}

    public function index()
    {
        $assignments = FormAssignment::with(['form', 'formVersion', 'store.user', 'currentSubmission'])->latest()->paginate(15);
        $forms = Form::where('is_active', true)->whereNotNull('published_version_id')->orderBy('title')->get();
        $stores = Stores::with('user')->orderBy('store_name')->get();

        return view('templates.customer-forms.assignments.index', compact('assignments', 'forms', 'stores'));
    }

    public function store(AssignmentRequest $request)
    {
        $form = Form::findOrFail($request->integer('form_id'));
        $store = Stores::findOrFail($request->integer('store_id'));
        $result = $this->forms->assign($form, $store);
        $message = $result['token'] ? 'لینک فرم ساخته شد.' : 'این فرم قبلاً به فروشگاه تخصیص داده شده است.';

        return redirect()->route('customer-forms.assignments.index')->with('success', $message);
    }

    public function toggle(FormAssignment $assignment)
    {
        $assignment = $this->forms->toggleAssignment($assignment);
        $message = $assignment->is_active ? 'دسترسی عمومی فعال شد.' : 'دسترسی عمومی غیرفعال شد.';

        return redirect()->route('customer-forms.assignments.index')->with('success', $message);
    }

    public function rotate(FormAssignment $assignment)
    {
        $this->forms->rotateToken($assignment);

        return redirect()->route('customer-forms.assignments.index')->with('success', 'لینک عمومی جدید ساخته شد.');
    }

    public function upgrade(FormAssignment $assignment)
    {
        $this->forms->upgradeAssignment($assignment);

        return redirect()->route('customer-forms.assignments.index')->with('success', 'فرم تخصیص‌یافته به نسخه جدید ارتقا یافت.');
    }
}
