<?php
namespace Modules\Ticket;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class TicketServiceProvider extends ServiceProvider
{
    private $namespace = 'Modules\Ticket\Controllers';

    public function register()
    {
        $this->app->bind(
            'Modules\Ticket\Repositories\InterfaceTicket',
            'Modules\Ticket\Repositories\TicketRepo'
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
