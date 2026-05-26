@extends('layouts.admin.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/dashboard.css') }}">
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
                        <span><i class="fa fa-file-text" style="margin-left: 8px;"></i> فاکتورهای اخیر</span>
                        <a href="{{ route('factor-list') }}">مشاهده همه <i class="fa fa-angle-left"></i></a>
                    </h3>

                    @if($recentFactors->isNotEmpty())
                        @foreach($recentFactors as $factor)
                            @php
                                $priceStatus = $priceStatusLabels[$factor->price_status] ?? ['text' => 'نامشخص', 'class' => 'open'];
                            @endphp
                            <a href="{{ route('factor-show', $factor->id) }}" class="dashboard-ticket-item" style="text-decoration: none;" target="_blank">
                                <div style="min-width: 0;">
                                    <div class="dashboard-ticket-title">
                                        {{ $factor->name ?: 'فاکتور #' . $factor->id }}
                                    </div>
                                    <div class="dashboard-ticket-meta">
                                        @if($factor->store)
                                            {{ $factor->store->store_name }} ·
                                        @endif
                                        {{ number_format($factor->price) }} تومان ·
                                        {{ Verta($factor->factor_date)->format('Y/m/d') }}
                                    </div>
                                </div>
                                <span class="dashboard-badge {{ $priceStatus['class'] }}">{{ $priceStatus['text'] }}</span>
                            </a>
                        @endforeach
                    @else
                        <div class="dashboard-empty">
                            <i class="fa fa-inbox"></i>
                            فاکتوری برای نمایش وجود ندارد
                        </div>
                    @endif
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
