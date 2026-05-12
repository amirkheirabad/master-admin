<?php

namespace Modules\Ticket\Controllers\Web;

use Illuminate\Http\Request;
use Modules\Stores\Repositories\InterfaceStores;
use Modules\Ticket\Repositories\InterfaceTicket;
use Modules\Ticket\Requests\TicketAdminRequest;
use Modules\Ticket\Requests\TicketReplyRequest;
use Modules\Ticket\Requests\TicketStoreRequest;

class TicketController
{
    private InterfaceTicket $ticket;
    private InterfaceStores $store;
    public function __construct(InterfaceTicket $ticket, InterfaceStores $store)
    {
        $this->ticket = $ticket;
        $this->store = $store;
    }

    public function index(Request $request)
    {
        $stores = $this->store->getAll();
        $tickets = $this->ticket->searchTicket($request);

        return view('templates.ticket.list', compact('tickets', 'stores'));
    }

    public function show($id)
    {
        $ticket = $this->ticket->findById($id);

        return view('templates.ticket.show', compact('ticket'));
    }

    public function updateStatus(Request $request, $id)
    {
        $this->ticket->updateTicketStatus($id, $request);
        return response()->json([
            'success' => true,
        ]);
    }

    public function replyAsAdmin(TicketReplyRequest $request, $id)
    {
        $this->ticket->replyAsAdmin($id, $request->validated());
        return redirect()->back();
    }

    public function insert()
    {
        $stores = $this->store->getAll();
        return view('templates.ticket.insert', compact('stores'));
    }

    public function store(TicketAdminRequest $request)
    {
        $this->ticket->createTicketAdmin($request->validated());
        return response()->json([
            'success' => true,
            'redirect' => route('list_tickets'),
            'message' => __('factor created successfully!')
        ]);    }

}
