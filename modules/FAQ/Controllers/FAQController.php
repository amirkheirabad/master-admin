<?php

namespace Modules\FAQ\Controllers;

use Illuminate\Http\Request;
use Modules\FAQ\Repositories\InterfaceFAQ;
use Modules\FAQ\Requests\FAQRequest;

class FAQController
{
    private InterfaceFAQ $faq;

    public function __construct(InterfaceFAQ $factor)
    {
        $this->faq = $factor;
    }
    public function insert()
    {
        return view('templates.faq.insert');
    }

    public function edit($id)
    {
        $faq = $this->faq->findById($id);
        return view('templates.faq.edit', compact('faq'));
    }

    public function index()
    {
        $faqs = $this->faq->getAll();
        return view('templates.faq.list', compact('faqs'));
    }

    public function create(FAQRequest $request)
    {
        $this->faq->create($request->validated());
        return response()->json([
            'success' => true,
            'redirect' => route('faq_list'),
        ]);
    }

    public function update($id, FAQRequest $request)
    {
        $this->faq->update($id, $request->validated());
        return response()->json([
            'success' => true,
            'redirect' => route('faq_list'),
        ]);
    }

    public function delete($id)
    {
        $this->faq->delete($id);
    }

    public function show()
    {
        $faqs = $this->faq->getAll();
        return view('templates.faq.show', compact('faqs'));
    }
}
