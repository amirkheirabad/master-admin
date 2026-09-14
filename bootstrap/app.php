<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Modules\CustomerForm\Repositories\InterfaceCustomerForm;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'check.login' => \App\Http\Middleware\CheckLogin::class,
            'check.role' => \App\Http\Middleware\CheckRole::class, 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ThrottleRequestsException $exception, Request $request) {
            if (! $request->routeIs('customer-forms.public.*')) {
                return null;
            }

            $token = (string) $request->route('token');
            $assignment = app(InterfaceCustomerForm::class)->resolveAssignment($token);
            $answers = $assignment->currentSubmission?->answers->pluck('value', 'form_question_id') ?? collect();

            return response()->view(
                'templates.customer-forms.public.show',
                compact('assignment', 'answers', 'token') + ['rateLimited' => true],
                429,
                $exception->getHeaders(),
            );
        });
    })->create();
