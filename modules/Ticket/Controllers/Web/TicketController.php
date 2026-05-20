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
        return response()->json([
            'success' => true,
        ]);
    }

    public function insert()
    {
        $stores = $this->store->getAll();
        // تولید سوال کپچا
        $num1 = rand(1, 20);
        $num2 = rand(1, 20);
        session(['captcha_result' => $num1 + $num2]);
        $captcha_question = "{$num1} + {$num2} = ?";

        return view('templates.ticket.insert', compact('stores', 'captcha_question'));
    }

    public function store(TicketAdminRequest $request)
    {
        if ($request->captcha != session('captcha_result')) {
            return response()->json([
                'success' => false,
                'errors' => ['captcha' => ['کد امنیتی اشتباه است']],
            ], 422);
        }

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
        if ($request->captcha != session('captcha_result')) {
            return response()->json([
                'success' => false,
                'errors' => ['captcha' => ['کد امنیتی اشتباه است']],
            ], 422);
        }

        $user = auth()->user();

        if ($user->hasRole('seller')) {
            $storeIds = $user->stores()->pluck('id')->toArray();

            if (!in_array($request->store_id, $storeIds)) {
                return response()->json([
                    'success' => false,
                    'error' => 'شما به این فروشگاه دسترسی ندارید'
                ], 403);
            }
        }

        $this->ticket->createTicketStore($request->validated());

        return response()->json([
            'success' => true,
            'redirect' => route('list_tickets'),
            'message' => __('factor created successfully!'),
        ]);
    }

    public function replyUser(TicketReplyRequest $request, $id)
    {
        $this->ticket->replyAsStore($id, $request->validated());
        return response()->json([
            'success' => true,
        ]);
    }

}
