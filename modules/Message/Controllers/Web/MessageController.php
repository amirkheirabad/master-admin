<?php

namespace Modules\Message\Controllers\Web;

use App\Http\Controllers\Controller;
use Modules\Message\Repositories\InterfaceMessage;
use Modules\Message\Requests\StoreMessageRequest;

class MessageController extends Controller
{
    private InterfaceMessage $messageRepo;

    public function __construct(InterfaceMessage $messageRepo)
    {
        $this->messageRepo = $messageRepo;
    }

    // صفحه لیست پیام‌ها (ادمین)
    public function index()
    {
        $messages = $this->messageRepo->getAll();
        return view('templates.message.list', compact('messages'));
    }

    // صفحه افزودن پیام جدید
    public function insert()
    {
        return view('templates.message.insert');
    }

    // ذخیره پیام جدید
    public function store(StoreMessageRequest $request)
    {
        $result = $this->messageRepo->create($request->validated());
        
        if ($result === true) {
            return response()->json([
                'success' => true,
                'redirect' => route('message.list'),
                'message' => 'پیام با موفقیت ایجاد شد'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'errors' => ['general' => [$result]]
        ], 422);
    }

    // صفحه ویرایش پیام
    public function edit($id)
    {
        $message = $this->messageRepo->findById($id);
        return view('templates.message.edit', compact('message'));
    }

    // بروزرسانی پیام
    public function update($id, StoreMessageRequest $request)
    {
        $result = $this->messageRepo->update($id, $request->validated());
        
        if ($result === true) {
            return response()->json([
                'success' => true,
                'redirect' => route('message.list'),
                'message' => 'پیام با موفقیت بروزرسانی شد'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'errors' => ['general' => [$result]]
        ], 422);
    }

    // حذف پیام
    public function destroy($id)
    {
        $this->messageRepo->delete($id);
        return response()->json([
            'success' => true,
            'redirect' => route('message.list'),
        ]);
    }

    // گرفتن پیام‌ها برای صفحه اصلی (عمومی)
    public function getMessagesForFront()
    {
        $messages = $this->messageRepo->getActive();
        return response()->json($messages);
    }
}