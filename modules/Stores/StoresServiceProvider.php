<?php
namespace Modules\Stores;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class StoresServiceProvider extends ServiceProvider
{
    private $namespace = 'Modules\Stores\Controllers';

    public function register()
    {
        $this->app->bind(
            'Modules\Stores\Repositories\InterfaceStores',
            'Modules\Stores\Repositories\StoresRepo'
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
