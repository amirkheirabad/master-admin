<?php

namespace Modules\Ticket\Services;

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

    public function sendTicketNotification(string $phone, string $name, int $ticketId): bool
    {
        try {
            // ساخت لینک مستقیم تیکت با استفاده از آیدی آن
            $ticketUrl = config('app.url') . '/ticket/' . $ticketId;

            $this->api->VerifyLookup(
                $phone,
                $name,           // توکن اول: نام کاربر (%token1%)
                $ticketUrl,      // توکن دوم: لینک مستقیم تیکت (%token2%)
                null,            // توکن سوم نداریم
                'ticketAlert'    // نام قالب ثبت شده در کاوه نگار
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