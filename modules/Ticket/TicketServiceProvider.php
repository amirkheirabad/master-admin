<?php
namespace Modules\Ticket;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Modules\Ticket\Models\Ticket;
use Modules\Ticket\Policies\TicketPolicy;

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
        Gate::policy(Ticket::class, TicketPolicy::class);

        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(__DIR__.'/api.php');
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__.'/web.php');
    }
}
