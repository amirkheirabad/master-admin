<?php

namespace Modules\SmsPanel\Services;

use Kavenegar\KavenegarApi;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Illuminate\Support\Facades\Log;

class SendSms
{
    private KavenegarApi $api;

    public function __construct()
    {
        $this->api = new KavenegarApi(config('services.kavenegar.api_key'));
    }

    public function sendSmsPanelCreatedNotification(string $store_name, string $campaign_name) :bool
    {
        $phone = '09911307218';
        $template = 'sms-panel-created';
        try {
            $this->api->verifyLookup(
                $phone,
                $store_name,
                $campaign_name,
                null,
                $template,
            );
            return true;
        }  catch (ApiException | HttpException $e) {
            Log::error('Send SMS panel notification failed', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }

    }

}
