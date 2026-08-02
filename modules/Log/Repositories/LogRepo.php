<?php

namespace Modules\Log\Repositories;

use Illuminate\Http\Request;
use Modules\Log\Models\Log;
use Hekmatinasser\Verta\Verta;
use Modules\Stores\Models\Stores;

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
    public function getSmsPanels()
    {
        $logs = Log::query()
            ->with('user')
            ->where('log_name', 'smsPanel')
            ->whereNotNull('causer_id')
            ->latest()
            ->paginate(10);

        $logs->getCollection()->transform(function ($log) {
            $log->decoded_properties = json_decode($log->properties, true);

            return $log;
        });

        $storeIds = $logs->getCollection()
            ->pluck('decoded_properties.old.store_id')
            ->filter()
            ->unique();

        $stores = Stores::whereIn('id', $storeIds)
            ->get()
            ->keyBy('id');

        $logs->getCollection()->transform(function ($log) use ($stores) {
            $properties = $log->decoded_properties;

            $oldStatus = $properties['old']['status'] ?? null;
            $newStatus = $properties['attributes']['status'] ?? null;

            $storeId = $properties['old']['store_id'] ?? null;
            $campaignName = $properties['old']['campaign_name'] ?? null;

            $storeName = $stores->get($storeId)?->store_name;

            $statusLabels = [
                0 => 'در حال برسی',
                1 => 'رد شده',
                2 => 'تایید شده',
            ];

            $log->description = "کمپین «{$campaignName}» از فروشگاه {$storeName} وضعیتش از «{$statusLabels[$oldStatus]}» به «{$statusLabels[$newStatus]}» تغییر کرد.";

            $log->properties = [
                'status' => [
                    'old' => $oldStatus,
                    'new' => $newStatus,
                ],
                'store_id' => $storeName,
                'campaign_name' => $campaignName,
            ];

            unset($log->decoded_properties);

            return $log;
        });

        return $logs;
    }


}
