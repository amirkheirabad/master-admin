<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Stores\Models\Stores;
use Modules\Stores\Services\SmsServiceExpiration;

class CheckEnamadExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //enamad
        $stores = Stores::with('user')->whereDate('enamd_expiration_date', today())->get();

        $smsService = app(SmsServiceExpiration::class);

        foreach ($stores as $store) {

            if (!$store->user?->mobile) {
                continue;
            }

            $smsService->sendSms(
                $store->user->mobile,
                $store->user->name,
                'EnamadExpiration',
            );
        }

        //domain

        $domainStores = Stores::with('user')->whereDate('domain_expiration_date', today())->get();

        foreach ($domainStores as $store) {
            if (!$store->user?->mobile) {
                continue;
            }

            $smsService->sendSms(
                $store->user->mobile,
                $store->user->name,
                'DomainExpiration',
            );
        }

    }
}
