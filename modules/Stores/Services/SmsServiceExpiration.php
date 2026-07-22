<?php

namespace Modules\Stores\Services;

use Kavenegar\KavenegarApi;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

class SmsServiceExpiration
{
    private KavenegarApi $api;

    public function __construct()
    {
        $this->api = new KavenegarApi(config('services.kavenegar.api_key'));
    }

    public function sendSms(string $phone, string $token, string $template) :bool
    {
        try {
            $this->api->verifyLookup(
                $phone,
                $token,
                null,
                null,
                $template,
            );
            return true;
        }  catch (ApiException | HttpException $e) {

            \Log::error($e->getMessage());

            return false;
        }

    }

}
