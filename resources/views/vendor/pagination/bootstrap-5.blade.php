<style>
    /* استایل پجینیشن */
    .pagination {
        gap: 8px !important;
        display: flex !important;
    }

    .pagination .page-item {
        margin: 0 !important;
        display: inline-block !important;
    }

    .pagination .page-link {
        border: 2px solid transparent !important; /* حذف بوردر از همه دکمه‌ها */
        border-radius: 8px !important;
        padding: 6px 14px !important;
        color: #333 !important;
        background-color: #fff !important;
        transition: all 0.2s ease !important;
        font-weight: 500 !important;
        font-size: 14px !important;
        line-height: 1.5 !important;
        height: 38px !important; /* ارتفاع ثابت برای همه */
        width: auto !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* یکسان کردن کامل فلش‌ها با اعداد */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-size: 18px !important;
        font-weight: bold !important;
        padding: 6px 16px !important;
        height: 38px !important; /* ارتفاع دقیقاً برابر با اعداد */
        line-height: 1.5 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* فقط صفحه فعال (صفحه جاری) بوردر رنگی داشته باشه */
    .pagination .page-item.active .page-link {
        border: 2px solid #99eae4 !important;
        background-color: #fff !important;
        color: #1a7f76 !important;
        font-weight: bold !important;
    }

    /* بقیه دکمه‌ها (غیرفعال) - بدون بوردر */
    .pagination .page-item:not(.active) .page-link {
        border: 2px solid transparent !important;
        background-color: #fff !important;
    }

     /*استایل هاور برای دکمه‌های غیرفعال */
    .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #f0fdfc !important;
        border: 2px solid #99eae4 !important;
        color: #1a7f76 !important;
    }

    /*!* استایل برای آیتم‌های غیرفعال (disabled) *!*/
    /*.pagination .page-item.disabled .page-link {*/
    /*    border-color: transparent !important;*/
    /*    background-color: #f5f5f5 !important;*/
    /*    color: #ccc !important;*/
    /*    cursor: not-allowed !important;*/
    /*}*/

    /* حذف استایل‌های پیش‌فرض لاراول و bootstrap */
    .page-item:first-child .page-link,
    .page-item:last-child .page-link {
        border-radius: 8px !important;
    }

    /* اضافه کردن سایه نرم در هاور برای دکمه‌ها */
    .pagination .page-item:not(.disabled):not(.active) .page-link:hover {
        box-shadow: 0 2px 8px rgba(153, 234, 228, 0.3) !important;
    }

    /* استایل برای اعداد */
    .pagination .page-link {
        min-width: 40px !important;
        text-align: center !important;
    }
</style>

@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <!-- <div>
                <p class="small text-muted">
                    {!! __('Showing') !!}
            <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
            <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
            <span class="fw-semibold">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
            </p>
        </div> -->

            <div class="w-100">
                <ul class="pagination justify-content-center">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="قبلی">
                            <span class="page-link" aria-hidden="true">&lsaquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="بعدی">&lsaquo;</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="page-link" aria-hidden="true">&rsaquo;</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
