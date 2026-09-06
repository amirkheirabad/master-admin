<?php

namespace Modules\Team;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TeamServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware('web')->group(__DIR__.'/web.php');
    }
}
