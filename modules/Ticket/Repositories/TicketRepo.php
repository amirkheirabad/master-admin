<?php

namespace Modules\Ticket\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Modules\Stores\Models\Stores;
use Modules\Ticket\Models\Ticket;
use Modules\Ticket\Models\TicketMessage;
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
            $tickets = Ticket::whereIn('store_id', $storeIds)->with('store');
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
        $attachmentPaths = array_map(fn($file) => $file?->store("ticket-attachments", 'public'), (array)($data['attachments'] ?? []));

        try {
            DB::beginTransaction();

            $ticket = Ticket::create([
            'store_id' => $data['store_id'],
            'title' => $data['title'],
            'contact_name' => $data['contact_name'],
            'priority' => $data['priority'],
            'status' => 0
        ]);

            TicketMessage::create([
            'ticket_id' => $ticket->id,
            'messages' => $data['message'],
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
        $attachmentPaths = array_map(fn($file) => $file?->store("ticket-attachments", 'public'), (array)($data['attachments'] ?? []));


        try {
            DB::beginTransaction();
            $ticket = Ticket::create([
                'store_id' => $data['store_id'],
                'title' => $data['title'],
                'contact_name' => $data['contact_name'],
                'priority' => $data['priority'],
                'status' => 1,
            ]);

            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'messages' => $data['message'],
                'sender_type' => 1,
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

        return $message;
    }


    public function updateTicketStatus($id, $request)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update(['status' => $request->status]);
        return $ticket;
    }

}
