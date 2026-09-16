<?php

namespace Modules\Log\Repositories;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function getTicketStatusReport(Request $request): array
    {
        $rows = DB::table('tickets')
            ->where('status', '!=', 5)
            ->selectRaw('DATE(created_at) as date, status as series_key, COUNT(*) as total')
            ->when($request->filled('status_from'), fn ($query) => $query->where('created_at', '>=', Verta::parse($request->status_from)->toCarbon()->startOfDay()))
            ->when($request->filled('status_to'), fn ($query) => $query->where('created_at', '<=', Verta::parse($request->status_to)->toCarbon()->endOfDay()))
            ->groupBy(DB::raw('DATE(created_at)'), 'status')
            ->orderBy('date')
            ->get();

        return $this->chart(
            $rows,
            $request->input('status_group', 'daily'),
            [
                0 => 'در حال بررسی توسط ایندکس',
                1 => 'منتظر پاسخ فروشگاه',
                2 => 'بسته شده',
                3 => 'ارجاع به واحد فنی',
                4 => 'ارجاع به واحد گرافیک دیزاین',
            ]
        );
    }

    public function getAdminResponseReport(Request $request): array
    {
        $rows = DB::table('ticket_messages')
            ->join('users', 'users.id', '=', 'ticket_messages.sender_id')
            ->where('ticket_messages.sender_type', 1)
            ->whereExists(fn ($query) => $query
                ->selectRaw('1')
                ->from('ticket_messages as previous_messages')
                ->whereColumn('previous_messages.ticket_id', 'ticket_messages.ticket_id')
                ->whereColumn('previous_messages.id', '<', 'ticket_messages.id'))
            ->selectRaw('DATE(ticket_messages.created_at) as date, ticket_messages.sender_id as series_key, users.name, ticket_messages.ticket_id')
            ->when($request->filled('admin_from'), fn ($query) => $query->where('ticket_messages.created_at', '>=', Verta::parse($request->admin_from)->toCarbon()->startOfDay()))
            ->when($request->filled('admin_to'), fn ($query) => $query->where('ticket_messages.created_at', '<=', Verta::parse($request->admin_to)->toCarbon()->endOfDay()))
            ->groupBy(DB::raw('DATE(ticket_messages.created_at)'), 'ticket_messages.sender_id', 'users.name', 'ticket_messages.ticket_id')
            ->orderBy('date')
            ->get();

        return $this->chart(
            $rows,
            $request->input('admin_group', 'daily'),
            $rows->pluck('name', 'series_key')->all()
        );
    }

    public function getStoreChecklistLogs(Request $request)
    {
        $logs = Log::query()
            ->with('user')
            ->where('log_name', 'store_check_lists')
            ->when($request->filled('checklist_from'), fn ($query) => $query->where('created_at', '>=', Verta::parse($request->checklist_from)->toCarbon()->startOfDay()))
            ->when($request->filled('checklist_to'), fn ($query) => $query->where('created_at', '<=', Verta::parse($request->checklist_to)->toCarbon()->endOfDay()))
            ->when($request->filled('store_id'), fn ($query) => $query->where('properties->store_id', (int) $request->store_id))
            ->latest()
            ->paginate(20);

        $logs->getCollection()->transform(function (Log $log) {
            $log->properties = json_decode($log->properties, true);

            return $log;
        });

        return $logs;
    }

    public function getStoresForChecklistReport()
    {
        return Stores::orderBy('store_name')->get(['id', 'store_name']);
    }

    private function chart($rows, string $group, array $seriesNames): array
    {
        $buckets = [];
        $values = [];

        foreach ($rows as $row) {
            $period = $this->period($row->date, $group);
            $buckets[$period['key']] = $period;
            if (isset($row->ticket_id)) {
                $values[$row->series_key][$period['key']][$row->ticket_id] = true;
            } else {
                $values[$row->series_key][$period['key']] = ($values[$row->series_key][$period['key']] ?? 0) + (int) $row->total;
            }
        }

        uasort($buckets, fn ($left, $right) => $left['sort'] <=> $right['sort']);

        return [
            'categories' => array_column($buckets, 'label'),
            'series' => collect($seriesNames)->map(fn ($name, $key) => [
                'name' => $name,
                'data' => collect(array_keys($buckets))->map(function ($bucket) use ($values, $key) {
                    $value = $values[$key][$bucket] ?? 0;

                    return is_array($value) ? count($value) : $value;
                })->all(),
            ])->values()->all(),
        ];
    }

    private function period(string $date, string $group): array
    {
        $carbon = Carbon::parse($date);

        if ($group === 'weekly') {
            $start = $carbon->copy()->startOfWeek(Carbon::SATURDAY);

            return [
                'key' => $start->toDateString(),
                'label' => Verta($start)->format('Y/m/d').' - '.Verta($start->copy()->addDays(6))->format('Y/m/d'),
                'sort' => $start->timestamp,
            ];
        }

        if ($group === 'monthly') {
            return [
                'key' => Verta($carbon)->format('Y/m'),
                'label' => Verta($carbon)->format('Y/m'),
                'sort' => $carbon->timestamp,
            ];
        }

        return [
            'key' => $carbon->toDateString(),
            'label' => Verta($carbon)->format('Y/m/d'),
            'sort' => $carbon->timestamp,
        ];
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
