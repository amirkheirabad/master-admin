<?php
namespace Modules\FAQ;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class FAQServiceProvider extends ServiceProvider
{
    private $namespace = 'Modules\FAQ\Controllers';

    public function register()
    {
        $this->app->bind(
            'Modules\FAQ\Repositories\InterfaceFAQ',
            'Modules\FAQ\Repositories\FAQRepo'
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
