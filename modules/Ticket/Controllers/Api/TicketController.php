<?php

namespace Modules\Ticket\Controllers\Api;

use Modules\Ticket\Requests\TicketApiRequest;
use Modules\Ticket\Requests\TicketStoreRequest;
use Modules\Ticket\Requests\TicketReplyRequest;
use Modules\Ticket\Repositories\InterfaceTicket;

class TicketController
{

    private InterfaceTicket $ticket;
    public function __construct(InterfaceTicket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function store(TicketApiRequest $request)
    {
        $store = $request->get('authenticated_store');
        $validated = $request->validated();
        $validated['store_id'] = $store->id;
        $ticket = $this->ticket->createTicketStore($validated);
        return response()->json([
            'success' => true,
            'message' => 'تیکت با موفقیت ایجاد شد',
            'data' => $ticket
        ], 201);    }

    public function reply(TicketReplyRequest $request, $id)
    {
        $store = $request->get('authenticated_store');
        $validated = $request->validated();
        $validated['store_id'] = $store->id;

        $message = $this->ticket->replyAsStore($id, $validated);
        return response()->json([
            'success' => true,
            'message' => 'پاسخ با موفقیت ثبت شد',
            'data' => $message
        ], 201);    }

}
