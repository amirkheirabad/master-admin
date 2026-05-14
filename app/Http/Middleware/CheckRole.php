<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (empty($roles)) {
            return $next($request);
        }

        if (!auth()->user()->hasAnyRole($roles)) {
            abort(403, 'شما دسترسی به این بخش ندارید.');
        }

        return $next($request);
    }
}