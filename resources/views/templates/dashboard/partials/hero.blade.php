<div class="dashboard-hero">
    <h2>سلام، {{ $user->name }} 👋</h2>
    <p>به پنل مدیریت master-admin خوش آمدید. خلاصه وضعیت سیستم را در یک نگاه ببینید.</p>
    <div class="dashboard-hero-meta">
        <span class="dashboard-hero-badge">
            <i class="fa fa-calendar"></i>
            {{ $todayFormatted }}
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
