<?php

namespace Modules\Ticket\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Modules\Stores\Models\Stores;
use Modules\Ticket\Models\Ticket;
use Modules\Ticket\Models\TicketMessage;
use Modules\Ticket\Services\SmsService;
use Modules\User\Models\User;
use Illuminate\Http\Request;


class TicketRepo implements InterfaceTicket
{
    public function findById($id)
    {
        return Ticket::with(['store', 'messages'])->findOrFail($id);
    }

    public function getAllTickets()
    {
        return Ticket::paginate(10);
    }

    public function searchTicket(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $tickets = Ticket::query()->with('store');
        } elseif ($user->hasRole('seller')) {
            $storeIds = $user->stores()->pluck('id');
            $tickets = Ticket::where(function($q) use ($storeIds, $user) {
                $q->whereIn('store_id', $storeIds)
                  ->orWhere(function($q2) use ($user) {
                      $q2->where('recipient_type', 'user')
                         ->where('user_id', $user->id);
                  });
            })->with(['store', 'user']);
        } else {
            return collect();
        }

        $searchQuery = $request->input('search_query');

        return $tickets
            ->when($request->filled('search_query'), function ($q) use ($searchQuery) {
                $q->where(function ($query) use ($searchQuery) {
                    $query->where('id', 'LIKE', '%' . $searchQuery . '%')
                        ->orWhere('title', 'LIKE', '%' . $searchQuery . '%');
                });
            })

            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })

            ->when($request->filled('store_id'), function ($q) use ($request) {
                if (auth()->user()->hasRole('admin')) {
                    $q->where('store_id', $request->store_id);
                }
            })

            ->when($request->filled('contact_name'), function ($q) use ($request) {
                $q->where('contact_name', $request->contact_name);
            })

            ->when($request->filled('priority'), function ($q) use ($request) {
                $q->where('priority', $request->priority);
            })

            ->when($request->filled('sort'), function ($q) use ($request) {
                if ($request->sort === 'latest') {
                    $q->orderBy('updated_at', 'desc');
                } elseif ($request->sort === 'oldest') {
                    $q->orderBy('updated_at', 'asc');
                }
            }, function ($q) {
                $q->orderBy('updated_at', 'desc');
            })
            ->paginate(10);
    }

    public function createTicketStore(array $data)
    {
    $attachmentPaths = array_map(
        fn($file) => $file?->store("ticket-attachments", 'public'),
        (array)($data['attachments'] ?? [])
    );

    try {
        DB::beginTransaction();

        $ticketData = [
            'title'          => $data['title'],
            'contact_name'   => $data['contact_name'],
            'priority'       => $data['priority'],
            'recipient_type' => $data['recipient_type'],
            'status'         => 0,
            'store_id'       => null,
            'user_id'        => null,
        ];

        if ($data['recipient_type'] === 'store') {
            $ticketData['store_id'] = $data['store_id'];
        } else {
            $ticketData['user_id'] = $data['user_id'];
        }

        $ticket = Ticket::create($ticketData);

        TicketMessage::create([
            'ticket_id'   => $ticket->id,
            'messages'    => $data['message'],
            'sender_type' => 0,
            'attachments' => !empty($attachmentPaths) ? json_encode($attachmentPaths) : null,
        ]);

        DB::commit();

        return $ticket->load('messages');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());
        throw new \Exception($e->getMessage());
    }
    }

    public function createTicketAdmin(array $data)
    {
    $attachmentPaths = array_map(
        fn($file) => $file?->store("ticket-attachments", 'public'),
        (array)($data['attachments'] ?? [])
    );

    try {
        DB::beginTransaction();

        $ticketData = [
            'title'          => $data['title'],
            'contact_name'   => $data['contact_name'],
            'priority'       => $data['priority'],
            'recipient_type' => $data['recipient_type'],
            'status'         => 1,
            'store_id'       => null,
            'user_id'        => null,
        ];

        if ($data['recipient_type'] === 'store') {
            $ticketData['store_id'] = $data['store_id'];
        } else {
            $ticketData['user_id'] = $data['user_id'];
        }

        $ticket = Ticket::create($ticketData);

        TicketMessage::create([
            'ticket_id'   => $ticket->id,
            'messages'    => $data['message'],
            'sender_type' => 1,
            'attachments' => !empty($attachmentPaths) ? json_encode($attachmentPaths) : null,
        ]);

        DB::commit();

        $this->sendTicketSms($ticket);

        return $ticket->load('messages');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());
        throw new \Exception($e->getMessage());
    }
    }

    public function replyAsStore($id, array $data)
    {
        $attachmentPaths = array_map(fn($file) => $file?->store("ticket-attachments/{$id}", 'public'), (array)($data['attachments'] ?? []));

        $message = TicketMessage::create([
            'ticket_id' => $id,
            'messages' => $data['message'],
            'sender_type' => 0,
            'attachments' => !empty($attachmentPaths) ? json_encode($attachmentPaths) : null,
        ]);


        $ticket = Ticket::find($id);
        $ticket->touch();

        return $message;

    }

    public function replyAsAdmin($id, array $data)
    {
        $attachmentPaths = array_map(fn($file) => $file?->store("ticket-attachments/{$id}", 'public'), (array)($data['attachments'] ?? []));

        $message = TicketMessage::create([
            'ticket_id' => $id,
            'messages' => $data['message'],
            'sender_type' => 1,
            'attachments' => !empty($attachmentPaths) ? json_encode($attachmentPaths) : null,
        ]);

        $ticket = Ticket::find($id);
        $ticket->touch();

        $this->sendTicketSms($ticket);

        return $message;
    }


    public function updateTicketStatus($id, $request)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update(['status' => $request->status]);
        return $ticket;
    }

    private function resolveRecipientContact(Ticket $ticket): ?array
    {
        if ($ticket->recipient_type === 'store' && $ticket->store_id) {
            $store = Stores::with('user')->find($ticket->store_id);
            if ($store) {
                $phone = $store->phone ?? $store->user?->mobile;
                if ($phone) {
                    return [
                        'phone' => $phone,
                        'name'  => $store->store_name ?? $store->user?->name ?? 'فروشگاه',
                    ];
                }
            }
        }

        if ($ticket->recipient_type === 'user' && $ticket->user_id) {
            $user = User::find($ticket->user_id);
            if ($user?->mobile) {
                return [
                    'phone' => $user->mobile,
                    'name'  => $user->name ?? 'کاربر',
                ];
            }
        }

        return null;
    }

    private function sendTicketSms(Ticket $ticket): void
    {
        $contact = $this->resolveRecipientContact($ticket);

        if (!$contact) {
            return;
        }

        (new SmsService())->sendTicketNotification(
            $contact['phone'],
            $contact['name'],
            $ticket->id
        );
    }

}
