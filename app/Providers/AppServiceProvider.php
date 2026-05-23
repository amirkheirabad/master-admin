<?php

namespace App\Providers;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('ticket_ratelimit', function (Request $request) {
            $key = $request->ip();
            return Limit::perMinute(5)->by($key)->response(function () {
                return response()->json(['error' => 'Too many request attempts.'], 429);
            });
        });
    }
}
