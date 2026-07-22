<?php

namespace Modules\Factor\Services;

use Kavenegar\KavenegarApi;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

class SmsService
{
    private KavenegarApi $api;

    public function __construct()
    {
        $this->api = new KavenegarApi(config('services.kavenegar.api_key'));
    }

    public function sendFactorNotification(string $phone, string $name, int $factorId, int $price): bool
    {

        try {
            $factorUrl = config('app.url') . '/factor/' . $factorId;

            $this->api->VerifyLookup(
                $phone,
                $name,
                (string)$price,
                $factorUrl,
                'factorAlert',

            );

            return true;
        } catch (ApiException $e) {
            \Log::error('Kavenegar API Error: ' . $e->getMessage());
            return false;
        } catch (HttpException $e) {
            \Log::error('Kavenegar HTTP Error: ' . $e->getMessage());
            return false;
        }
    }
}
