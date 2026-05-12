<?php
namespace Modules\User;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\User\Repositories\InterfaceUser;
use Modules\User\Repositories\UserRepo;
use Modules\User\Repositories\InterfaceAuth;
use Modules\User\Repositories\AuthRepo;

class UserServiceProvider extends ServiceProvider
{
    private $namespace = 'Modules\User\Controllers';

    public function register()
    {
        $this->app->bind(InterfaceUser::class, UserRepo::class);
        
        $this->app->bind(InterfaceAuth::class, AuthRepo::class);
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