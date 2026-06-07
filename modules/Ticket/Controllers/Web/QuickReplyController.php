<?php

namespace Modules\Ticket\Controllers\Web;

use Illuminate\Http\Request;
use Modules\Ticket\Repositories\InterfaceQuickReply;
use Modules\Ticket\Repositories\QuickReplyRepo;
use Modules\Ticket\Requests\QuickReplyRequest;
use Modules\Ticket\Models\QuickReply;
class QuickReplyController
{
    private InterfaceQuickReply $repo;

    public function __construct(QuickReplyRepo $repo){
        $this->repo = $repo;
    }

    public function index(){
        $quickReplies = $this->repo->getAll();
        return view('templates.ticket.quick-replies.list', compact('quickReplies'));
    }
    
    public function store(QuickReplyRequest $request)
    {
        $this->repo->create($request->validated());
        return response()->json(['success' => true]);
    }
    
    public function update(QuickReplyRequest $request, $id)
    {
        $this->repo->update($id, $request->validated());
        return response()->json(['success' => true]);
    }
    public function destroy($id)
    {
        $this->repo->delete($id);
        return response()->json(['success' => true]);
    }

    // این route رو از show تیکت صدا میزنیم
    public function apiList()
    {
        $items = QuickReply::orderBy('title')->get(['id', 'title', 'body']);
        return response()->json($items);
    }
}    