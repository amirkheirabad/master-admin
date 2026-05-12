<?php

namespace Modules\SmsPanel\Services;

use Illuminate\Http\Request;
use Modules\SmsPanel\Repositories\InterfaceSmsPanel;

class SmsPanelService
{
    private InterfaceSmsPanel $smsPanel;
    public function __construct(InterfaceSmsPanel $smsPanel)
    {
        $this->smsPanel = $smsPanel;
    }

    public function listForTable(Request $request)
    {
        return $this->smsPanel->filterSmsPanel($request);
    }

    public function getSms(int $id)
    {
        $sms = $this->smsPanel->getSms($id);

        return [
            'store_message' => $sms->store_message,
            'admin_message' => $sms->admin_message,
            'status' => $sms->status,
            'store_name' => $sms->store->store_name,
            'campein_name' => $sms->store->campein_name,
        ];
    }

}
