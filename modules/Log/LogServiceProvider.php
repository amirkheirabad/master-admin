<?php
namespace Modules\Log;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LogServiceProvider extends ServiceProvider
{
    private $namespace = 'Modules\Log\Controllers';

    public function register()
    {
        $this->app->bind(
            'Modules\Log\Repositories\InterfaceLog',
            'Modules\Log\Repositories\LogRepo'
        );
    }

    public function boot()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__.'/web.php');
    }
}
