<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\Stores\Models\Stores;
use Illuminate\Support\Facades\Cache;


class StoreTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. گرفتن توکن از هدر
        $token = $request->header('Authorization');

        // 2. چک کردن وجود توکن
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'لطفا توکن احراز هویت را ارسال کنید',
                'error_code' => 'MISSING_TOKEN'
            ], 401);
        }

        // 3. حذف Bearer prefix
        if (str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7);
        }

        // 4. چک کردن خالی نبودن توکن بعد از حذف Bearer
        if (empty($token)) {
            return response()->json([
                'status' => 'error',
                'message' => 'توکن ارسال شده معتبر نیست',
                'error_code' => 'INVALID_TOKEN'
            ], 401);
        }

        // 5. پیدا کردن فروشگاه با کش (برای کاهش بار دیتابیس)
        $store = $this->getStoreByToken($token);

        // 6. چک کردن وجود فروشگاه
        if (!$store) {

            return response()->json([
                'status' => 'error',
                'message' => 'توکن نامعتبر است',
                'error_code' => 'TOKEN_INVALID'
            ], 401);
        }

        // 7. اضافه کردن فروشگاه به ریکوئست برای استفاده در کنترلرها
        $request->merge([
            'authenticated_store' => $store,
            'store_id' => $store->id,
            'store_token' => $token
        ]);

        // 8. همچنین میتونی از macro استفاده کنی
        $request->setUserResolver(function () use ($store) {
            return $store;
        });

        return $next($request);
    }

    /**
     * گرفتن فروشگاه با توکن (با کش)
     */
    private function getStoreByToken($token)
    {
        // کش به مدت 60 دقیقه
        $cacheKey = 'store_token_' . md5($token);

        return Cache::remember($cacheKey, 3600, function () use ($token) {
            return Stores::where('token', $token)->first();
        });
    }
}
