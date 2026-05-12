<?php

namespace Modules\User\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\User\Models\User;

class AuthRepo implements InterfaceAuth
{
    /**
     * تلاش برای ورود کاربر
     */
    public function attemptLogin(array $credentials, bool $remember = false): bool
    {
        $credentials['is_active'] = true;
        return Auth::attempt($credentials, $remember);
    }

    /**
     * خروج کاربر از سیستم
     */
    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }

    /**
     * دریافت کاربر لاگین شده
     */
    public function getAuthenticatedUser(): ?User
    {
        return Auth::user();
    }

    /**
     * ریدایرکت بر اساس نقش کاربر
     */
    public function getRedirectUrlByRole(): string
    {
        $user = $this->getAuthenticatedUser();
        
        if (!$user) {
            return '/login';
        }

        if ($user->hasRole('admin')) {
            return route('user-list');
        } elseif ($user->hasRole('store_manager')) {
            return '/stores';
        } elseif ($user->hasRole('support')) {
            return '/tickets';
        }
            
        return '/';
    }
}