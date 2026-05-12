<?php

namespace Modules\Ticket\Controllers\Api;

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

    public function store(TicketStoreRequest $request)
    {
        $ticket = $this->ticket->createTicketStore($request->validated());
        return response()->json(['data' => $ticket], 201);
    }

    public function reply(TicketReplyRequest $request, $id)
    {
        $message = $this->ticket->replyAsStore($id, $request->validated());
        return response()->json(['message' => 'Reply added', 'data' => $message], 201);
    }

}
