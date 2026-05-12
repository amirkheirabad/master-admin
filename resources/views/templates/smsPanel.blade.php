@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/jalalidatepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/jalalidatepicker.min.js') }}"></script>
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <script src="{{ asset('/js/sms-panel.js') }}"></script>
    <script>
        function resetAllFilters() {
            // 1. ریست تقویم (تاریخ ثبت)
            const created_at_filter = document.getElementById('created_at_filter');
            if (created_at_filter) {
                created_at_filter.value = '';
            }

            // 2. ریست select2 فروشگاه (store_name)
            const storeSelect = document.querySelector('#store_name_filter');
            if (storeSelect) {
                storeSelect.value = '';
                // فراخوانی change برای آپدیت select2
                const event = new Event('change', { bubbles: true });
                storeSelect.dispatchEvent(event);
            }

            // 3. ریست custom-select-input (وضعیت)
            const statusSelect = document.querySelector('#status_filter');
            if (statusSelect && statusSelect.classList.contains('custom-select-input')) {
                // ریست کردن سلکت اصلی به اولین آپشن (همه)
                statusSelect.selectedIndex = 0;

                // پیدا کردن wrapper پلاگین
                const wrapper = statusSelect.nextElementSibling;
                if (wrapper && wrapper.classList.contains('cs-wrapper')) {
                    const firstOption = statusSelect.options[0];
                    if (firstOption) {
                        // آپدیت متن نمایشی
                        const triggerText = wrapper.querySelector('.cs-trigger-text');
                        if (triggerText) {
                            triggerText.textContent = firstOption.text;
                        }

                        // آپدیت hidden input
                        const hiddenInput = wrapper.querySelector('input[type="hidden"]');
                        if (hiddenInput) {
                            hiddenInput.value = firstOption.value;
                        }

                        // آپدیت کلاس selected در آپشن‌ها
                        const options = wrapper.querySelectorAll('.cs-option');
                        options.forEach((opt, index) => {
                            if (index === 0) {
                                opt.classList.add('cs-selected');
                            } else {
                                opt.classList.remove('cs-selected');
                            }
                        });
                    }
                }
            }
        }

        // اتصال به دکمه حذف فیلترها
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function(e) {
                e.preventDefault();
                resetAllFilters();
            });
        }
    </script>
@endsection

<?php
    $stores = app(Modules\Stores\Repositories\StoresRepo::class)->index()
?>

@section('content')
    <!-- page content -->
    <!-- top tiles -->

    <div class="container" style="margin-top:50px;">
        <!-- مدال -->
        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"> پیام فروشگاه <span id="store_name"></span></h4>
                        <h5>کمپین <span id="campein_name"></span></h5>
                    </div>

                    <div class="modal-body">
                        <h4>متن پیام:</h4>
                        <textarea id="storeMessage" class="form-control custom-radius" rows="4" readonly></textarea>
                        <h4 class="mt-8">تعیین وضعیت پیامک:</h4>
                        <div class="radio radio-lg mt-8">
                            <label>
                                <input type="radio" class="flat"  name="iCheck" value="2"> تایید درخواست
                            </label>
                            <label class="mr-2">
                                <input type="radio" class="flat"  name="iCheck" value="1">رد درخواست
                            </label>
                        </div>
                        <h4 class="mt-8">توضیحات</h4>
                        <textarea id="adminMessage" class="form-control custom-radius" rows="4"></textarea>
                    </div>

                    <div class="d-flex justify-content-center mb-3 mt-8 gap">
                        <button type="button" class="btn btn-beta-outline" data-dismiss="modal">بستن</button>
                        <button type="button" class="btn btn-beta-solid" onclick="submitSmsPanel()">ذخیره</button>
                    </div>

                </div>
            </div>
        </div>
        <!-- مدال -->
        <div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"></h4>
                    </div>

                    <div class="modal-body">
                        <h4>توضیحات ارسال شده:</h4>
                        <textarea id="adminMessage" class="form-control custom-radius" rows="4" readonly></textarea>
                    </div>

                    <div class="modal-footer mt-8">
                        <button type="button" class="btn btn-beta-outline" data-dismiss="modal">بستن</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row mb-8">
                    <div class="col-md-6">
                        <h3>پنل پیامکی</h3>
                    </div>
                </div>

{{--                <h2>تست API ثبت پیام با توکن</h2>--}}

{{--                <form id="apiTestForm">--}}
{{--                    <label>توکن فروشگاه:</label>--}}
{{--                    <input type="text" id="token" name="token" placeholder="توکن فروشگاه"><br><br>--}}

{{--                    <label>پیام فروشگاه:</label>--}}
{{--                    <textarea id="store_message" name="store_message" placeholder="پیام فروشگاه"></textarea><br><br>--}}

{{--                    <button type="submit">ارسال</button>--}}
{{--                </form>--}}

{{--                <h3>نتیجه:</h3>--}}
{{--                <pre id="result"></pre>--}}

                <div class="d-flex justify-content-between">
                    <div></div>
                    <form method="get" action="">
                        <div class="d-flex align-items-center" style="gap: 15px;">
                            <div class="search-container">
                                <button class="search-button">
                                    <i class="fa fa-search"></i>
                                </button>
                                <input type="text" name="search_query" value="{{ request('search_query') }}" class="search-input" placeholder="جستجو کنید...">
                            </div>

                            <div class="dropdown custom-dropdown">
                                <button
                                    class="btn btn-white-new dropdown-toggle d-inline-flex align-items-center position-relative"
                                    type="button"
                                    id="filterDropdown"
                                    style="gap: 8px;"
                                >
                                    <i class="fa fa-filter"></i> فیلتر ها
                                    <span id="filterBadge" class="badge badge-danger bg-beta" style="color: white; border-radius: 50%; padding: 2px 6px; font-size: 11px; display: none; margin-left: 4px;">0</span>
                                </button>

                                <div class="dropdown-menu rounded-5" id="filterMenu" style="padding: 15px; min-width: 320px;">
                                    <!-- تاریخ ثبت -->
                                    <div class="mb-2">
                                        <div class="input-wrapper has-icon">
                                            <input type="text"
                                                   class="form-control custom-radius"
                                                   id="created_at_filter"
                                                   name="created_at"
                                                   data-jdp
                                                   value="{{ request()->get('created_at') }}"
                                                   placeholder="تاریخ ثبت">
                                            <div class="icon-box" onclick="document.getElementById('created_at_filter').focus()">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- فروشگاه -->
                                    <div class="mb-2">
                                        <select class="form-control custom-radius select2" name="store_name" id="store_name_filter">
                                            <option value="">انتخاب فروشگاه</option>
                                            @foreach($stores as $store)
                                                <option value="{{ $store->store_name }}" {{ request()->get('store_name') == $store->store_name ? 'selected' : '' }}>
                                                    {{ $store->store_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- وضعیت -->
                                    <div class="mb-2">
                                        <select name="status" class="form-control custom-radius custom-select-input" id="status_filter">
                                            <option value="" {{ request()->get('status') == '' ? 'selected' : ''}}>همه</option>
                                            <option value="0" {{ request()->get('status') == '0' ? 'selected' : ''}}>در حال بررسی</option>
                                            <option value="1" {{ request()->get('status') == '1' ? 'selected' : ''}}>رد شده</option>
                                            <option value="2" {{ request()->get('status') == '2' ? 'selected' : ''}}>تایید شده</option>
                                        </select>
                                    </div>

                                    <!-- دکمه‌ها -->
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" id="clearFiltersBtn" class="btn btn-link text-default text-bold" style="padding: 0;">حذف فیلترها</button>
                                        <button type="submit" class="btn btn-beta-solid mr-6">اعمال</button>
                                        <button type="button" class="btn btn-beta-outline" id="cancelFilterBtn">انصراف</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="x_panel rounded-top mt-2 p-0">
                    <table class="table">
                        <thead class="responsive-table-head">
                        <tr>
                            <th>همه</th>
                            <th>نام فروشگاه</th>
                            <th>نام کمپین</th>
                            <th>تاریخ ثبت</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($smsPanels as $sms)
                            <tr class="responsive-table-row">
                                <th scope="row" class="responsive-table-td">{{ $sms->id }}</th>
                                <td data-title="نام فروشگاه" class="responsive-table-td">{{ $sms->store->store_name }}</td>
                                <td data-title="نام کمپین" class="responsive-table-td">{{ $sms->campaign_name }}</td>
                                <td data-title="تاریخ ثبت" class="responsive-table-td">{{ Verta($sms->created_at)->format(' %d %B  %Y') }}</td>
                                <td data-title="وضعیت" class="responsive-table-td">
                                    @if($sms->status == 0)
                                        <span class="bg-warning p-2 custom-radius">
                                        در حال برسی
                                    </span>
                                    @elseif($sms->status == 1)
                                        <span class="text-danger">
                                        رد شده
                                    </span>
                                    @elseif($sms->status == 2)
                                        <span class="bg-jade p-2 custom-radius">
                                        تایید شده
                                    </span>
                                    @endif
                                </td>
                                <td data-title="عملیات" class="responsive-table-td">
                                    <div class="">
                                        <a href="javascript:;" class="text-primary" data-toggle="modal" data-target="#myModal2" data-id="{{ $sms->id }}">
                                            <i class="fa fa-eye fa-x"></i>
                                        </a>
                                        <a href="javascript:;" class="text-success" data-toggle="modal" data-target="#myModal" data-id="{{ $sms->id }}">
                                            <i class="fa fa-pencil fa-x"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="d-flex justify-content-center">
                    {{$smsPanels->withQueryString()->links('vendor.pagination.bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>

@endsection
