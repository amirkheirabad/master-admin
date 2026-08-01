<?php

namespace Modules\SmsPanel\Repositories;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Modules\Factor\Services\SmsService;
use Modules\SmsPanel\Models\SmsPanel;
use Modules\Stores\Models\Stores;
use Modules\SmsPanel\Services\SendSms;
use Modules\SmsPanel\Services\ChangeStatusService;


class SmsPanelRepo implements InterfaceSmsPanel
{
    public function getSms($id)
    {
        return SmsPanel::with('store')->findOrfail($id);
    }
    public function filterSmsPanel(Request $request)
    {
        $searchQuery = $request->input('search_query');

        return SmsPanel::query()->with('store')
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

            ->latest('updated_at')
            ->paginate(10);
    }
    public function store(int $id, array $data)
    {
        $smsPanel = SmsPanel::with('store')->findOrFail($id);
        $smsPanel->timestamps = false;
        $newStatus = $data['status'];
        $adminMessage = $data['admin_message'];

        (new ChangeStatusService())->sendStatusUpdate($smsPanel, $newStatus);

        $smsPanel->update([
            'status' => $newStatus,
            'admin_message' => $adminMessage,
        ]);

        $statusText = match ((int) $newStatus) {
            1 => 'رد شد',
            2 => 'تأیید شد',
            default => 'تغییر کرد',
        };

        $message = "کمپین «{$smsPanel->campaign_name}» شما {$statusText}.";

        if ($adminMessage)
        {
            $message .= "\nتوضیحات: {$adminMessage}";
        }

        (new SendSms())->sendSms(
            $smsPanel->store->phone,
            $message
        );


        return $smsPanel;
    }
    public function createFromToken(array $data)
    {
        $smsPanel = SmsPanel::create([
            'store_id' => $data['store_id'],
            'status' => 0,
            'campaign_name' => $data['campaign_name'],
            'store_message' => $data['store_message'] ?? null,
            'external_id' => $data['external_id'],
        ]);

        $smsPanel->load('store');

        (new SendSms())->sendSmsPanelCreatedNotification(
            store_name: $smsPanel->store->store_name,
            campaign_name: $smsPanel->campaign_name,
        );

        return $smsPanel;
    }

    public function updateFromToken(array $data)
    {
        $smsPanel = SmsPanel::updateOrCreate(
            [
                'store_id' => $data['store_id'],
                'external_id' => $data['external_id'],
            ],
            [
                'status' => 0,
                'campaign_name' => $data['campaign_name'],
                'store_message' => $data['store_message'] ?? null,
            ]
        );

        $smsPanel->load('store');

        (new SendSms())->sendSmsPanelCreatedNotification(
            store_name: $smsPanel->store->store_name,
            campaign_name: $smsPanel->campaign_name,
        );

        return $smsPanel;
    }


}
