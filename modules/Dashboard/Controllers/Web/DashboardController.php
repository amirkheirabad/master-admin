<?php

namespace Modules\Dashboard\Controllers\Web;

use Modules\Factor\Models\Factor;
use Modules\Message\Models\Message;
use Modules\Stores\Models\Stores;
use Modules\Ticket\Models\Ticket;
use Modules\User\Models\User;

class DashboardController
{
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');
        $isSeller = $user->hasRole('seller');

        $factorQuery = Factor::query();
        $ticketQuery = Ticket::query();
        $stats = [];
        $messages = collect();
        $recentTickets = collect();
        $recentFactors = collect();

        if ($isAdmin) {
            $stats = [
                ['label' => 'کاربران', 'value' => User::count(), 'icon' => 'fa-users', 'color' => 'blue'],
                ['label' => 'فروشگاه‌ها', 'value' => Stores::count(), 'icon' => 'fa-shopping-cart', 'color' => 'green'],
                ['label' => 'فاکتورها', 'value' => Factor::count(), 'icon' => 'fa-file-text', 'color' => 'orange'],
                ['label' => 'تیکت باز', 'value' => Ticket::whereIn('status', [0, 1 ,3])->count(), 'icon' => 'fa-life-ring', 'color' => 'purple'],
                ['label' => 'فاکتور پرداخت‌نشده', 'value' => Factor::where('price_status', 1)->count(), 'icon' => 'fa-exclamation-circle', 'color' => 'red'],
            ];
            $recentTickets = Ticket::with('store')->orderByDesc('updated_at')->limit(4)->get();
            $recentFactors = Factor::with('store')->orderByDesc('created_at')->limit(4)->get();
        } elseif ($isSeller) {
            $storeIds = $user->stores()->pluck('id')->filter();

            $factorQuery->where(function ($q) use ($storeIds, $user) {
                $q->whereIn('store_id', $storeIds)->orWhere('user_id', $user->id);
            });
            $ticketQuery->where(function ($q) use ($storeIds, $user) {
                $q->whereIn('store_id', $storeIds)
                    ->orWhere(function ($q2) use ($user) {
                        $q2->where('recipient_type', 'user')
                            ->where('user_id', $user->id);
                    });
            });

            $stats = [
                ['label' => 'فاکتورها', 'value' => (clone $factorQuery)->count(), 'icon' => 'fa-file-text', 'color' => 'blue'],
                ['label' => 'تیکت‌ها', 'value' => (clone $ticketQuery)->count(), 'icon' => 'fa-life-ring', 'color' => 'green'],
                ['label' => 'تیکت باز', 'value' => (clone $ticketQuery)->whereIn('status', [0, 1 , 3])->count(), 'icon' => 'fa-comments', 'color' => 'orange'],
                ['label' => 'پرداخت‌نشده', 'value' => (clone $factorQuery)->where('price_status', 1)->count(), 'icon' => 'fa-credit-card', 'color' => 'red'],
            ];

            $messages = Message::where('is_active', true)->orderBy('order')->get();
            $recentTickets = (clone $ticketQuery)->with('store')->orderByDesc('updated_at')->limit(5)->get();
            $recentFactors = (clone $factorQuery)->with('store')->orderByDesc('created_at')->limit(5)->get();
        }

        $ticketStatusLabels = [
            0 => ['text' => 'در حال بررسی', 'class' => 'open'],
            1 => ['text' => 'منتظر پاسخ', 'class' => 'waiting'],
            2 => ['text' => 'بسته شده', 'class' => 'closed'],
            3 => ['text' => 'ارجاع به واحد فنی', 'class' => 'closed'],
        ];

        $priceStatusLabels = [
            0 => ['text' => 'در حال پرداخت', 'class' => 'waiting'],
            1 => ['text' => 'پرداخت نشده', 'class' => 'open'],
            2 => ['text' => 'پرداخت شده', 'class' => 'closed'],
            3 => ['text' => 'کارت به کارت', 'class' => 'waiting'],
            4 => ['text' => 'معلق شده', 'class' => 'open'],
        ];

        return view('templates.dashboard.index', compact(
            'user',
            'isAdmin',
            'isSeller',
            'stats',
            'messages',
            'recentTickets',
            'recentFactors',
            'ticketStatusLabels',
            'priceStatusLabels'
        ));
    }
}
