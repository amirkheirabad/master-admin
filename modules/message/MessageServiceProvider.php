<?php
namespace Modules\Message;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class MessageServiceProvider extends ServiceProvider
{
    private $namespace = 'Modules\Message\Controllers';

    public function register()
    {
        $this->app->bind(
            'Modules\Message\Repositories\InterfaceMessage',
            'Modules\Message\Repositories\MessageRepo'
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