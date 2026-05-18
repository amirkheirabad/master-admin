<?php

namespace Modules\SmsPanel\Repositories;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Modules\SmsPanel\Models\SmsPanel;
use Modules\Stores\Models\Stores;


class SmsPanelRepo implements InterfaceSmsPanel
{
    public function getSms($id)
    {
        return SmsPanel::with('store')->findOrfail($id);
    }
    public function filterSmsPanel(Request $request)
    {
        $searchQuery = $request->input('search_query');

        return SmsPanel::query()
            ->when($request->filled('search_query'), function ($q) use ($searchQuery) {
                $q->where(function ($query) use ($searchQuery) {
                    $query->where('id', 'LIKE', '%' . $searchQuery . '%');
                });
            })

            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })

            ->when($request->filled('store_name'), function ($q) use ($request) {
                $q->whereHas('store', function ($q2) use ($request) {
                    $q2->where('store_name', 'like', "%{$request->store_name}%");
                });
            })

            ->when($request->filled('created_at'), function ($q) use ($request) {
                    $q->whereDate('created_at', Verta::parse($request->created_at)->toCarbon());
            })

            ->latest()
            ->paginate(10);
    }
    public function store(int $id, array $data)
    {
        $smsPanel = SmsPanel::findOrFail($id);

        $smsPanel->update([
            'status' => $data['status'],
            'admin_message' => $data['admin_message'] ?? null,
        ]);

        return $smsPanel;
    }
    public function createFromToken(array $data)
    {
        SmsPanel::create([
            'store_id' => $data['store_id'],
            'status' => 0,
            'campaign_name' => $data['campaign_name'],
            'store_message' => $data['store_message'] ?? null,
        ]);
    }


}
