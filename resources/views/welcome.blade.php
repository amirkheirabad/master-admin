@extends('layouts.admin.master')

@php
    use Hekmatinasser\Verta\Verta;
    use Modules\Factor\Models\Factor;
    use Modules\Message\Models\Message;
    use Modules\Stores\Models\Stores;
    use Modules\Ticket\Models\Ticket;
    use Modules\User\Models\User;

    $user = auth()->user();
    $isAdmin = $user->hasRole('admin');
    $isSeller = $user->hasRole('seller');

    $factorQuery = Factor::query();
    $ticketQuery = Ticket::query();
    $stats = [];
    $messages = collect();
    $recentTickets = collect();
    $chartLabels = [];
    $chartData = [];

    if ($isAdmin) {
        $stats = [
            ['label' => 'کاربران', 'value' => User::count(), 'icon' => 'fa-users', 'color' => 'blue'],
            ['label' => 'فروشگاه‌ها', 'value' => Stores::count(), 'icon' => 'fa-shopping-cart', 'color' => 'green'],
            ['label' => 'فاکتورها', 'value' => Factor::count(), 'icon' => 'fa-file-text', 'color' => 'orange'],
            ['label' => 'تیکت باز', 'value' => Ticket::whereIn('status', [0, 1])->count(), 'icon' => 'fa-life-ring', 'color' => 'purple'],
            ['label' => 'فاکتور پرداخت‌نشده', 'value' => Factor::where('price_status', 1)->count(), 'icon' => 'fa-exclamation-circle', 'color' => 'red'],
        ];
        $recentTickets = Ticket::with('store')->orderByDesc('updated_at')->limit(5)->get();
    } elseif ($isSeller) {
        $storeIds = $user->stores()->pluck('id')->filter();

        $factorQuery->where(function ($q) use ($storeIds, $user) {
            $q->whereIn('store_id', $storeIds)->orWhere('user_id', $user->id);
        });
        $ticketQuery->whereIn('store_id', $storeIds);

        $stats = [
            ['label' => 'فاکتورها', 'value' => (clone $factorQuery)->count(), 'icon' => 'fa-file-text', 'color' => 'blue'],
            ['label' => 'تیکت‌ها', 'value' => (clone $ticketQuery)->count(), 'icon' => 'fa-life-ring', 'color' => 'green'],
            ['label' => 'تیکت باز', 'value' => (clone $ticketQuery)->whereIn('status', [0, 1])->count(), 'icon' => 'fa-comments', 'color' => 'orange'],
            ['label' => 'پرداخت‌نشده', 'value' => (clone $factorQuery)->where('price_status', 1)->count(), 'icon' => 'fa-credit-card', 'color' => 'red'],
        ];

        $messages = Message::where('is_active', true)->orderBy('order')->get();
        $recentTickets = (clone $ticketQuery)->with('store')->orderByDesc('updated_at')->limit(5)->get();
    }

    if ($isAdmin || $isSeller) {
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartLabels[] = Verta::instance($date)->format('%B');
            $chartData[] = (clone $factorQuery)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
    }

    $ticketStatusLabels = [
        0 => ['text' => 'در حال بررسی', 'class' => 'open'],
        1 => ['text' => 'منتظر پاسخ', 'class' => 'waiting'],
        2 => ['text' => 'بسته شده', 'class' => 'closed'],
    ];
@endphp

@section('css')
<style>
    .dashboard-wrap {
        padding: 20px 24px 40px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .dashboard-hero {
        background: linear-gradient(135deg, #133c6d 0%, #1e5a9e 55%, #2a7bc4 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 12px 32px rgba(19, 60, 109, 0.22);
        position: relative;
        overflow: hidden;
    }

    .dashboard-hero::after {
        content: '';
        position: absolute;
        left: -40px;
        top: -40px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 50%;
    }

    .dashboard-hero::before {
        content: '';
        position: absolute;
        right: -20px;
        bottom: -60px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 50%;
    }

    .dashboard-hero h2 {
        margin: 0 0 8px;
        font-size: 24px;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }

    .dashboard-hero p {
        margin: 0;
        opacity: 0.9;
        font-size: 14px;
        position: relative;
        z-index: 1;
    }

    .dashboard-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 18px;
        position: relative;
        z-index: 1;
    }

    .dashboard-hero-badge {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(4px);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .dashboard-stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        border: 1px solid #edf2f7;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .dashboard-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(19, 60, 109, 0.1);
    }

    .dashboard-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .dashboard-stat-icon.blue { background: rgba(19, 60, 109, 0.1); color: #133c6d; }
    .dashboard-stat-icon.green { background: rgba(72, 187, 120, 0.12); color: #38a169; }
    .dashboard-stat-icon.orange { background: rgba(237, 137, 54, 0.12); color: #dd6b20; }
    .dashboard-stat-icon.purple { background: rgba(128, 90, 213, 0.12); color: #805ad5; }
    .dashboard-stat-icon.red { background: rgba(229, 62, 62, 0.1); color: #e53e3e; }

    .dashboard-stat-value {
        font-size: 26px;
        font-weight: 700;
        color: #1a202c;
        line-height: 1.2;
    }

    .dashboard-stat-label {
        font-size: 13px;
        color: #718096;
        margin-top: 4px;
    }

    .dashboard-alert {
        background: linear-gradient(90deg, #fffbeb 0%, #fff 100%);
        border-right: 4px solid #f6ad55;
        padding: 14px 18px;
        margin-bottom: 12px;
        border-radius: 10px;
        border: 1px solid #fef3c7;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .dashboard-alert i {
        color: #dd6b20;
        font-size: 18px;
        margin-top: 2px;
    }

    .dashboard-alert-title {
        font-weight: 600;
        color: #744210;
        display: block;
        margin-bottom: 4px;
    }

    .dashboard-alert-text {
        color: #975a16;
        font-size: 13px;
        line-height: 1.6;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
    }

    @media (max-width: 991px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    .dashboard-panel {
        background: #fff;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        border: 1px solid #edf2f7;
        margin-bottom: 20px;
    }

    .dashboard-panel-title {
        font-size: 16px;
        font-weight: 600;
        color: #133c6d;
        margin: 0 0 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .dashboard-panel-title a {
        font-size: 12px;
        font-weight: 500;
        color: #718096;
    }

    .dashboard-panel-title a:hover {
        color: #133c6d;
        text-decoration: none;
    }

    .dashboard-quick-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 10px;
        color: #4a5568;
        text-decoration: none;
        transition: background 0.15s;
        margin-bottom: 6px;
    }

    .dashboard-quick-link:hover {
        background: #f7fafc;
        color: #133c6d;
        text-decoration: none;
    }

    .dashboard-quick-link i {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(19, 60, 109, 0.08);
        color: #133c6d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .dashboard-ticket-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #edf2f7;
    }

    .dashboard-ticket-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .dashboard-ticket-item:first-child {
        padding-top: 0;
    }

    .dashboard-ticket-title {
        font-weight: 500;
        color: #2d3748;
        font-size: 14px;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 220px;
    }

    .dashboard-ticket-meta {
        font-size: 12px;
        color: #a0aec0;
    }

    .dashboard-badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }

    .dashboard-badge.open { background: #ebf8ff; color: #2b6cb0; }
    .dashboard-badge.waiting { background: #fef3c7; color: #975a16; }
    .dashboard-badge.closed { background: #f0fff4; color: #276749; }

    .dashboard-empty {
        text-align: center;
        padding: 28px 16px;
        color: #a0aec0;
        font-size: 14px;
    }

    .dashboard-empty i {
        font-size: 32px;
        margin-bottom: 10px;
        display: block;
        opacity: 0.5;
    }

    .dashboard-chart-wrap {
        position: relative;
        height: 260px;
    }
</style>
@endsection

@section('nav')
    <div class="nav toggle hide-from-md">
        <h4>داشبورد</h4>
    </div>
@endsection

@section('content')
<div class="dashboard-wrap">
    <div class="dashboard-hero">
        <h2>سلام، {{ $user->name }} 👋</h2>
        <p>به پنل مدیریت master-admin خوش آمدید. خلاصه وضعیت سیستم را در یک نگاه ببینید.</p>
        <div class="dashboard-hero-meta">
            <span class="dashboard-hero-badge">
                <i class="fa fa-calendar"></i>
                {{ Verta::now()->format('%A %d %B %Y') }}
            </span>
            @if($isAdmin)
                <span class="dashboard-hero-badge">
                    <i class="fa fa-shield"></i>
                    نقش: مدیر سیستم
                </span>
            @elseif($isSeller)
                <span class="dashboard-hero-badge">
                    <i class="fa fa-store"></i>
                    نقش: فروشنده
                </span>
            @endif
        </div>
    </div>

    @if($isSeller && $messages->isNotEmpty())
        @foreach($messages as $message)
            <div class="dashboard-alert">
                <i class="fa fa-bullhorn"></i>
                <div>
                    <span class="dashboard-alert-title">{{ $message->title }}</span>
                    <span class="dashboard-alert-text">{{ $message->content }}</span>
                </div>
            </div>
        @endforeach
    @endif

    @if(!empty($stats))
        <div class="dashboard-stats">
            @foreach($stats as $stat)
                <div class="dashboard-stat-card">
                    <div class="dashboard-stat-icon {{ $stat['color'] }}">
                        <i class="fa {{ $stat['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="dashboard-stat-value">{{ number_format($stat['value']) }}</div>
                        <div class="dashboard-stat-label">{{ $stat['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="dashboard-grid">
        <div>
            @if($isAdmin || $isSeller)
                <div class="dashboard-panel">
                    <h3 class="dashboard-panel-title">
                        <span><i class="fa fa-bar-chart" style="margin-left: 8px;"></i> فاکتورهای ۶ ماه اخیر</span>
                    </h3>
                    <div class="dashboard-chart-wrap">
                        <canvas id="factorsChart"></canvas>
                    </div>
                </div>
            @endif

            <div class="dashboard-panel">
                <h3 class="dashboard-panel-title">
                    <span><i class="fa fa-life-ring" style="margin-left: 8px;"></i> آخرین تیکت‌ها</span>
                    @if($isAdmin || $isSeller)
                        <a href="{{ route('list_tickets') }}">مشاهده همه <i class="fa fa-angle-left"></i></a>
                    @endif
                </h3>

                @if($recentTickets->isNotEmpty())
                    @foreach($recentTickets as $ticket)
                        @php
                            $status = $ticketStatusLabels[$ticket->status] ?? ['text' => 'نامشخص', 'class' => 'open'];
                        @endphp
                        <a href="{{ route('show_ticket', $ticket->id) }}" class="dashboard-ticket-item" style="text-decoration: none;">
                            <div style="min-width: 0;">
                                <div class="dashboard-ticket-title">{{ $ticket->title }}</div>
                                <div class="dashboard-ticket-meta">
                                    @if($isAdmin && $ticket->store)
                                        {{ $ticket->store->store_name }} ·
                                    @endif
                                    {{ Verta($ticket->updated_at)->format('Y/m/d') }}
                                </div>
                            </div>
                            <span class="dashboard-badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                        </a>
                    @endforeach
                @else
                    <div class="dashboard-empty">
                        <i class="fa fa-inbox"></i>
                        تیکتی برای نمایش وجود ندارد
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="dashboard-panel">
                <h3 class="dashboard-panel-title">
                    <span><i class="fa fa-plus-circle" style="margin-left: 8px;"></i> افزودن سریع</span>
                </h3>

                @if($isAdmin)
                    <a href="{{ route('user-insert') }}" class="dashboard-quick-link">
                        <i class="fa fa-users"></i>
                        <span>افزودن کاربر</span>
                    </a>
                    <a href="{{ route('role-insert') }}" class="dashboard-quick-link">
                        <i class="fa fa-user-circle-o"></i>
                        <span>افزودن نقش</span>
                    </a>
                    <a href="{{ route('insert_store') }}" class="dashboard-quick-link">
                        <i class="fa fa-shopping-cart"></i>
                        <span>افزودن فروشگاه</span>
                    </a>
                    <a href="{{ route('factor-insert') }}" class="dashboard-quick-link">
                        <i class="fa fa-file-text"></i>
                        <span>افزودن فاکتور</span>
                    </a>
                    <a href="{{ route('insert_ticket') }}" class="dashboard-quick-link">
                        <i class="fa fa-life-ring"></i>
                        <span>تیکت جدید</span>
                    </a>
                    <a href="{{ route('message.insert') }}" class="dashboard-quick-link">
                        <i class="fa fa-bell-o"></i>
                        <span>پیام جدید</span>
                    </a>
                @elseif($isSeller)
                    <a href="{{ route('factor-list') }}" class="dashboard-quick-link">
                        <i class="fa fa-file-text"></i>
                        <span>مشاهده فاکتورها</span>
                    </a>
                    <a href="{{ route('insert_ticket') }}" class="dashboard-quick-link">
                        <i class="fa fa-plus-circle"></i>
                        <span>تیکت جدید</span>
                    </a>
                @else
                    <div class="dashboard-empty">
                        <i class="fa fa-info-circle"></i>
                        دسترسی سریعی تعریف نشده
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@if($isAdmin || $isSeller)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var canvas = document.getElementById('factorsChart');
        if (!canvas || typeof Chart === 'undefined') return;

        var ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'تعداد فاکتور',
                    data: @json($chartData),
                    borderColor: '#133c6d',
                    backgroundColor: 'rgba(19, 60, 109, 0.08)',
                    borderWidth: 2,
                    pointBackgroundColor: '#133c6d',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0,
                            fontFamily: 'inherit'
                        },
                        gridLines: { color: 'rgba(0,0,0,0.06)' }
                    }],
                    xAxes: [{
                        ticks: { fontFamily: 'inherit' },
                        gridLines: { display: false }
                    }]
                },
                tooltips: {
                    rtl: true,
                    callbacks: {
                        label: function (item) {
                            return ' تعداد: ' + item.yLabel;
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
