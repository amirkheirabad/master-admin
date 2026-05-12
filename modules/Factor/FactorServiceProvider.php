<?php
namespace Modules\Factor;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class FactorServiceProvider extends ServiceProvider
{
    private $namespace = 'Modules\Factor\Controllers';

    public function register()
    {
        $this->app->bind(
            'Modules\Factor\Repositories\InterfaceFactor',
            'Modules\Factor\Repositories\FactorRepo'
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
