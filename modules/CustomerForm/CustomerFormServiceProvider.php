<?php

namespace Modules\CustomerForm;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\CustomerForm\Repositories\CustomerFormRepo;
use Modules\CustomerForm\Repositories\InterfaceCustomerForm;

class CustomerFormServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(InterfaceCustomerForm::class, CustomerFormRepo::class);
    }

    public function boot(): void
    {
        Route::middleware('web')->group(__DIR__.'/web.php');
    }
}
