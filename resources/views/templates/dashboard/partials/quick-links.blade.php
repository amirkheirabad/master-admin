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
