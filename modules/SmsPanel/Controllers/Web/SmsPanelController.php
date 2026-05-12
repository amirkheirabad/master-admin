<?php

namespace Modules\SmsPanel\Controllers\Web;

use Illuminate\Http\Request;
use Modules\SmsPanel\Repositories\InterfaceSmsPanel;
use Modules\SmsPanel\Requests\SmsPanelRequest;
use Modules\SmsPanel\Services\SmsPanelService;

class SmsPanelController
{
    private InterfaceSmsPanel $SmsPanel;
    private SmsPanelService $SmsPanelService;
    public function __construct(InterfaceSmsPanel $SmsPanel, SmsPanelService $SmsPanelService)
    {
        $this->SmsPanel = $SmsPanel;
        $this->SmsPanelService = $SmsPanelService;
    }

    public function index(Request $request)
    {
        $smsPanels = $this->SmsPanelService->listForTable($request);
        return view('templates.smsPanel', compact('smsPanels'));
    }

    public function store(SmsPanelRequest $request, int $id)
    {
        $this->SmsPanel->store($id, $request->validated());
        return response()->json([
            'success' => true,
        ]);
    }
    public function getSms($id)
    {
        $smsPanel = $this->SmsPanelService->getSms($id);

        return response()->json($smsPanel);
    }



}
