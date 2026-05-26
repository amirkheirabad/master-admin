<?php

return [
    App\Providers\AppServiceProvider::class,
    Modules\SmsPanel\SmsPanelServiceProvider::class,
    Modules\Stores\StoresServiceProvider::class,
    Modules\Factor\FactorServiceProvider::class,
    Modules\Ticket\TicketServiceProvider::class,
    Modules\User\UserServiceProvider::class,
    Modules\message\MessageServiceProvider::class,
    Modules\Education\EducationServiceProvider::class,
];
