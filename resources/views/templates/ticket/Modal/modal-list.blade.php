<div class="filters-data" style="display: none;">
    <div class="filter-group" data-type="store" data-title="نام فروشگاه">
        <span data-value="">همه</span>
    @foreach($stores as $store)
            <span data-value="{{ $store->id }}">{{ $store->store_name }}</span>
        @endforeach
    </div>

    <div class="filter-group" data-type="status" data-title="وضعیت">
        <span data-value="">همه</span>
        <span data-value="0">در حال برسی توسط ایندکس</span>
        <span data-value="1">منتظر پاسخ فروشگاه</span>
        <span data-value="2">بسته شده</span>
        <span data-value="3">ارجاع به واحد فنی</span>
    </div>

    <div class="filter-group" data-type="team" data-title="تیم مخاطب">
        <span data-value="">همه</span>
        <span data-value="0">درخواست ماژول با فیچر جدید</span>
        <span data-value="1">تیم فنی و عملیات</span>
        <span data-value="2">تیم پشتیبانی و سفارشات</span>
        <span data-value="3">گزارش خطا</span>
    </div>

    <div class="filter-group" data-type="priority" data-title="اولویت">
        <span data-value="">همه</span>
        <span data-value="1">کم</span>
        <span data-value="2">متوسط</span>
        <span data-value="3">زیاد</span>
        <span data-value="4">فوری</span>
    </div>
</div>


</div>


<div class="selected-filters-data" style="display: none;">
    <span data-selected-store="{{ request('store_id', '') }}"></span>
    <span data-selected-status="{{ request('status', '') }}"></span>
    <span data-selected-contact-name="{{ request('contact_name', '') }}"></span>
    <span data-selected-priority="{{ request('priority', '') }}"></span>  {{-- اضافه شد --}}
    <span data-selected-sort="{{ request('sort', 'latest') }}"></span>
</div>

<div id="mobileSortModal" class="mobile-filter-modal">
    <div class="mobile-filter-overlay"></div>
    <div class="mobile-filter-sheet">
        <div class="mobile-filter-header">
            <button class="mobile-sort-back" style="display: none;">
                <i class="fa fa-chevron-right"></i>
            </button>
            <h3 class="mobile-filter-title">ترتیب نمایش</h3>
            <button class="mobile-sort-close">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="mobile-filter-content">
            <div class="filter-sort-main-page">
                <div class="filter-option-item" data-sort-type="latest">
                    <span>جدید ترین</span>
                    <div class="filter-option-right">
                        <i class="fa fa-check check-icon" style="color: #007bff; font-size: 18px; display: none;"></i>
                    </div>
                </div>
                <div class="filter-option-item" data-sort-type="oldest">
                    <span>قدیمی ترین</span>
                    <div class="filter-option-right">
                        <i class="fa fa-check check-icon" style="color: #007bff; font-size: 18px; display: none;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="mobileFilterModal" class="mobile-filter-modal">
    <div class="mobile-filter-overlay"></div>
    <div class="mobile-filter-sheet">
        <div class="mobile-filter-header">
            <button class="mobile-filter-back" style="display: none;">
            </button>
            <h4 class="mobile-filter-title">فیلترها</h4>
            <button type="button" class="mobile-filter-close">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="mobile-filter-content">
            <div class="filter-main-page">
                <div class="filter-option-item" data-filter-type="store">
                    <span>نام فروشگاه</span>
                    <div class="filter-option-right">
                        <span class="filter-selected-value" id="selected-store-value"></span>
                        <i class="fa fa-chevron-left"></i>
                    </div>
                </div>
                <div class="filter-option-item" data-filter-type="status">
                    <span>وضعیت</span>
                    <div class="filter-option-right">
                        <span class="filter-selected-value" id="selected-status-value"></span>
                        <i class="fa fa-chevron-left"></i>
                    </div>
                </div>
                <div class="filter-option-item" data-filter-type="team">
                    <span>تیم مخاطب</span>
                    <div class="filter-option-right">
                        <span class="filter-selected-value" id="selected-team-value"></span>
                        <i class="fa fa-chevron-left"></i>
                    </div>
                </div>
                <div class="filter-option-item" data-filter-type="priority">
                    <span>اولویت</span>
                    <div class="filter-option-right">
                        <span class="filter-selected-value" id="selected-priority-value"></span>
                        <i class="fa fa-chevron-left"></i>
                    </div>
                </div>
            </div>

            <div class="filter-sub-page" data-filter-page="store" style="display: none;">

                <div class="filter-sub-content"></div>
            </div>
            <div class="filter-sub-page" data-filter-page="status" style="display: none;">

                <div class="filter-sub-content"></div>
            </div>
            <div class="filter-sub-page" data-filter-page="team" style="display: none;">

                <div class="filter-sub-content"></div>
            </div>
            <div class="filter-sub-page" data-filter-page="priority" style="display: none;">

                <div class="filter-sub-content"></div>
            </div>
        </div>

        <div class="mobile-filter-footer">
            <button type="button" class="btn btn-beta-solid" id="mobileApplyFilters">اعمال</button>
            <button type="button" class="btn btn-beta-outline" id="mobileClearFilters">حذف فیلترها</button>
        </div>
    </div>
</div>
