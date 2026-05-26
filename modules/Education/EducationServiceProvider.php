<?php
namespace Modules\Education;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class EducationServiceProvider extends ServiceProvider
{
    private $namespace = 'Modules\Education\Controllers';

    public function register()
    {
        $this->app->bind(
            'Modules\Education\Repositories\InterfaceEducation',
            'Modules\Education\Repositories\EducationRepo'
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
