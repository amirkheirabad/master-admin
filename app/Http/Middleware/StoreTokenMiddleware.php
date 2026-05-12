<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\Stores\Models\Stores;

class StoreTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'لطفا توکن احراز هویت را ارسال کنید'
            ], 401);
        }

        if (str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7);
        }

        $store = Stores::where('token', $token)->first();

        if (!$store) {
            return response()->json([
                'message' => 'توکن نامعتبر است'
            ], 401);
        }
        return $next($request);
    }
}
