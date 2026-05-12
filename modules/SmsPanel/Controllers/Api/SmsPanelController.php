<?php

namespace Modules\SmsPanel\Controllers\Api;
use Modules\SmsPanel\Repositories\InterfaceSmsPanel;
use Modules\SmsPanel\Requests\StoreRequest;

class SmsPanelController
{
    private InterfaceSmsPanel $SmsPanel;
    public function __construct(InterfaceSmsPanel $SmsPanel)
    {
        $this->SmsPanel = $SmsPanel;
    }

    public function createFromToken(StoreRequest $request)
    {
        $this->SmsPanel->createFromToken($request->validated());
        return response()->json([
            'success' => true,
        ]);
    }
}
