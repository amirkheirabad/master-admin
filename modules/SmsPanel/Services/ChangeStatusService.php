<?php

namespace Modules\SmsPanel\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ChangeStatusService
{
    public function sendStatusUpdate($smsPanel, $newStatus)
    {
        $smsPanel->loadMissing('store');

        if (!$smsPanel->store || !$smsPanel->store->link) {
            throw new RuntimeException('آدرس فروشگاه ثبت نشده است.');
        }

        $url = rtrim($smsPanel->store->link, '/')
            . '/api/sms-campaign/status';

        try {
            $response = Http::asJson()
                ->acceptJson()
                ->withToken('ewW3stla6LVZWIDMU0L5')
                ->post($url, [
                    'id' => $smsPanel->external_id,
                    'status' => $this->mapStatus($newStatus),
                ]);
        } catch (ConnectionException $exception) {
            report($exception);

            throw new RuntimeException(
                'اتصال به فروشگاه برقرار نشد. آدرس دامنه یا وضعیت سرور را بررسی کنید.'
            );
        }

        if ($response->failed()) {

            if (
                $response->status() === 404 &&
                str_contains($response->json('message', ''), 'No query results for model')
            ) {
                throw new RuntimeException(
                    'این کمپین دیگر در فروشگاه وجود ندارد.'
                );
            }

            throw new RuntimeException(
                $response->json('message')
                ?? 'تغییر وضعیت در فروشگاه ناموفق بود.'
            );
        }

        if ($response->json('success') === false) {
            throw new RuntimeException(
                $response->json('message')
                ?? 'تغییر وضعیت در فروشگاه ناموفق بود.'
            );
        }
    }

    private function mapStatus($status): string
    {
        return match ((int) $status) {
            0 => 'waiting',
            1 => 'inactive',
            2 => 'active',
            default => throw new RuntimeException('وضعیت نامعتبر است.'),
        };
    }
}
