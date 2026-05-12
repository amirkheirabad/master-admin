<?php
namespace Modules\User;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class UserServiceProvider extends ServiceProvider
{
    private $namespace = 'Modules\User\Controllers';

    public function register()
    {
        $this->app->bind(
            'Modules\User\Repositories\InterfaceUser',
            'Modules\User\Repositories\UserRepo'
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
