<?php

namespace Modules\Dashboard;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    private $namespace = 'Modules\Dashboard\Controllers';

    public function register()
    {
    }

    public function boot()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__.'/web.php');
    }
}
