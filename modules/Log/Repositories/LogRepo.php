<?php

namespace Modules\Log\Repositories;

use Illuminate\Http\Request;
use Modules\Log\Models\Log;
use Hekmatinasser\Verta\Verta;

class LogRepo implements InterfaceLog
{
    public function getFactors(Request $request)
    {
        $searchQuery = $request->input('search_query');

        return Log::query()
            ->with('user')
            ->where('log_name', 'factor')

            ->when($request->filled('search_query'), function ($q) use ($searchQuery) {
                $q->whereHas('user', function ($query) use ($searchQuery) {
                    $query->where('name', 'LIKE', '%'.$searchQuery.'%');
                });
            })

            ->when($request->filled('operation'), function ($q) use ($request) {
                $q->where('event', $request->operation);
            })

            ->when($request->filled('created_at'), function ($q) use ($request) {
                $q->whereDate('created_at', Verta::parse($request->created_at)->toCarbon());
            })
            ->latest()
            ->paginate(10);
    }

    public function getTickets()
    {
        $logs = Log::query()
            ->with('user')
            ->where('log_name', 'ticket_messages')
            ->latest()
            ->paginate(10);
        $logs->getCollection()->transform(function ($log) {
            $properties = json_decode($log->properties);
            $log->ticket_id = data_get($properties, 'attributes.ticket_id');

            return $log;
        });

        return $logs;
    }
}
