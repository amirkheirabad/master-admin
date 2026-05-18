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
        $store = $request->get('authenticated_store');
        $validated = $request->validated();
        $validated['store_id'] = $store->id;

        $this->SmsPanel->createFromToken($validated);
        return response()->json([
            'success' => true,
            'message' => 'درخواست با موفقیت ثبت شد'
        ]);
    }
}
