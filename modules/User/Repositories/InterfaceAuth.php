<?php

namespace Modules\User\Repositories;

interface InterfaceAuth 
{
    /**
     * تلاش برای ورود کاربر
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function attemptLogin(array $credentials, bool $remember = false): bool;

    /**
     * خروج کاربر از سیستم
     * @return void
     */
    public function logout(): void;

    /**
     * دریافت کاربر لاگین شده
     * @return \Modules\User\Models\User|null
     */
    public function getAuthenticatedUser();

    /**
     * ریدایرکت بر اساس نقش کاربر
     * @return string
     */
    public function getRedirectUrlByRole(): string;
}