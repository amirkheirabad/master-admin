<?php
namespace Modules\SmsPanel;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class SmsPanelServiceProvider extends ServiceProvider
{
    private $namespace = 'Modules\SmsPanel\Controllers';

    public function register()
    {
        $this->app->bind(
            'Modules\SmsPanel\Repositories\InterfaceSmsPanel',
            'Modules\SmsPanel\Repositories\SmsPanelRepo'
        );
    }

    public function boot()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(__DIR__.'/api.php');
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__.'/web.php');
    }
}
