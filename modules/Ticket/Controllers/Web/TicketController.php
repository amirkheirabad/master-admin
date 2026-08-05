<?php

namespace Modules\Ticket\Controllers\Web;

use Illuminate\Http\Request;
use Modules\Stores\Repositories\InterfaceStores;
use Modules\Ticket\Repositories\InterfaceTicket;
use Modules\User\Repositories\InterfaceUser;
use Modules\Ticket\Requests\TicketAdminRequest;
use Modules\Ticket\Requests\TicketReplyRequest;
use Modules\Ticket\Requests\TicketStoreRequest;
use Modules\Ticket\Export\TicketExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class TicketController
{
    private InterfaceTicket $ticket;
    private InterfaceUser $user;

    private InterfaceStores $store;

    public function __construct(InterfaceTicket $ticket, InterfaceStores $store, InterfaceUser $user)
    {
        $this->ticket = $ticket;
        $this->store = $store;
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $stores = $this->store->getAll();
        $tickets = $this->ticket->searchTicket($request);
        $assignedUsers = $this->user->assignedUser();

        if ($request->submit == "export")
        {
            $tickets = $this->ticket->exportTickets($request);
            return Excel::download(
                new TicketExport($tickets),
                'tickets_' . Carbon::now('Asia/Tehran')->format('Y-m-d_H-i-s') . '.xlsx'
            );
        }

        return view('templates.ticket.list', compact('tickets', 'stores', 'assignedUsers'));
    }

    public function show($id)
    {
        $ticket = $this->ticket->findById($id);
        $assignedUsers = $this->user->assignedUser();

        return view('templates.ticket.show', compact('ticket', 'assignedUsers'));
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
        return response()->json([
            'success' => true,
        ]);
    }

    public function insert()
    {
    $stores = $this->store->getAll();
    $users = \Modules\User\Models\User::select('id', 'name', 'mobile')->get();

    $num1 = rand(1, 20);
    $num2 = rand(1, 20);
    session(['captcha_result' => $num1 + $num2]);
    $captcha_question = "{$num1} + {$num2} = ?";

    return view('templates.ticket.insert', compact('stores', 'users', 'captcha_question'));
    }

    public function store(TicketAdminRequest $request)
    {
        $this->ticket->createTicketAdmin($request->validated());

        return response()->json([
            'success' => true,
            'redirect' => route('list_tickets'),
            'message' => __('factor created successfully!'),
        ]);
    }

    public function refreshCaptcha()
    {
        $num1 = rand(1, 20);
        $num2 = rand(1, 20);
        session(['captcha_result' => $num1 + $num2]);

        return response()->json(['question' => "{$num1} + {$num2} = ?"]);
    }


    public function storeUser(TicketStoreRequest $request)
    {
    $user = auth()->user();
    $store = $user->stores()->first();

    // اگه فروشگاه داره، recipient_type رو force میکنیم به store
    if ($store) {
        $request->merge([
            'recipient_type' => 'store',
            'store_id'       => $store->id,
            'user_id'        => null,
        ]);
    } else {
        // فروشگاه نداره، تیکت به اسم خودش
        $request->merge([
            'recipient_type' => 'user',
            'user_id'        => $user->id,
            'store_id'       => null,
        ]);
    }

    $this->ticket->createTicketStore($request->validated());

    return response()->json([
        'success'  => true,
        'redirect' => route('list_tickets'),
        'message'  => __('ticket created successfully!'),
    ]);
    }

    public function replyUser(TicketReplyRequest $request, $id)
    {
        $this->ticket->replyAsStore($id, $request->validated());
        return response()->json([
            'success' => true,
        ]);
    }

    public function updateMessage(Request $request, $id)
    {
        try {
        $this->ticket->updateMessage($id, $request);
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 403);
    }
    }

    public function assign($id, Request $request)
    {
        $this->ticket->assign($request, $id);
        return response()->json([
            'success' => true,
        ]);
    }

}
