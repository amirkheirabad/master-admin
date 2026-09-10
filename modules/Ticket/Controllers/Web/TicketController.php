<?php

namespace Modules\Ticket\Controllers\Web;

use Illuminate\Http\Request;
use Modules\Stores\Repositories\InterfaceStores;
use Modules\Ticket\Repositories\InterfaceTicket;
use Modules\User\Repositories\InterfaceUser;
use Modules\Ticket\Requests\TicketAdminRequest;
use Modules\Ticket\Requests\TicketReplyRequest;
use Modules\Ticket\Requests\TicketStoreRequest;
use Modules\Ticket\Requests\TicketAssignmentRequest;
use Modules\Ticket\Export\TicketExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Modules\Ticket\Models\Ticket;
use Modules\Ticket\Models\TicketMessage;
use Modules\Team\Models\Team;

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

    public function index(Request $request, ?string $siteType = 'index')
    {
        Gate::authorize('viewAny', Ticket::class);

        if (! $request->user()->hasRole('admin') && ! $request->user()->stores()->exists()) {
            $siteType = null;
        }

        $stores = $this->store->getAll($siteType);
        $tickets = $this->ticket->searchTicket($request, $siteType);
        $assignedUsers = $this->user->assignedUser();
        $teams = Team::orderBy('name')->get();

        if ($request->submit == "export")
        {
            $tickets = $this->ticket->exportTickets($request, $siteType);
            return Excel::download(
                new TicketExport($tickets),
                'tickets_' . Carbon::now('Asia/Tehran')->format('Y-m-d_H-i-s') . '.xlsx'
            );
        }

        return view('templates.ticket.list', compact('tickets', 'stores', 'assignedUsers', 'teams', 'siteType'));
    }

    public function show($id)
    {
        $ticket = $this->ticket->findById($id);
        Gate::authorize('view', $ticket);
        $assignedUsers = $this->user->assignedUser();
        $teams = Team::orderBy('name')->get();

        return view('templates.ticket.show', compact('ticket', 'assignedUsers', 'teams'));
    }

    public function updateStatus(Request $request, $id)
    {
        Gate::authorize('view', Ticket::findOrFail($id));
        $request->validate([
            'status' => ['required', 'integer', 'in:0,1,2,3,4'],
        ]);
        $this->ticket->updateTicketStatus($id, $request);

        return response()->json([
            'success' => true,
        ]);
    }

    public function replyAsAdmin(TicketReplyRequest $request, $id)
    {
        Gate::authorize('view', Ticket::findOrFail($id));
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
        $ticket = $this->ticket->createTicketAdmin($request->validated());

        return response()->json([
            'success' => true,
            'redirect' => route($ticket->store?->site_type === 'wordpress' ? 'list_wordpress_tickets' : 'list_tickets'),
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

    $ticket = $this->ticket->createTicketStore($request->validated());

    return response()->json([
        'success'  => true,
        'redirect' => route($ticket->store?->site_type === 'wordpress' ? 'list_wordpress_tickets' : 'list_tickets'),
        'message'  => __('ticket created successfully!'),
    ]);
    }

    public function replyUser(TicketReplyRequest $request, $id)
    {
        Gate::authorize('view', Ticket::findOrFail($id));
        $this->ticket->replyAsStore($id, $request->validated());
        return response()->json([
            'success' => true,
        ]);
    }

    public function updateMessage(Request $request, $id)
    {
        Gate::authorize('view', TicketMessage::findOrFail($id)->ticket);

        try {
        $this->ticket->updateMessage($id, $request);
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 403);
    }
    }

    public function assign($id, TicketAssignmentRequest $request)
    {
        Gate::authorize('view', Ticket::findOrFail($id));
        $this->ticket->assign($request, $id);

        if (! $request->expectsJson()) {
            if ($request->user()->team_id) {
                return redirect()->route('list_tickets');
            }

            return redirect()->route('show_ticket', $id);
        }

        return response()->json([
            'success' => true,
        ]);
    }

}
