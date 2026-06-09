@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/jalalidatepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/jalalidatepicker.min.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <script src="{{ asset('js/create-factor.js') }}"></script>
    <script>
        function formatPrice(price) {
            let numericPrice = String(price).replace(/[^0-9]/g, '');
            if (numericPrice === '') return '';
            return numericPrice.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function formatPriceOnInput(event) {
            let input = event.target;
            input.value = formatPrice(input.value);
        }

        function formatDisplayPriceByClass(className) {
            let elements = document.getElementsByClassName(className);
            for (let i = 0; i < elements.length; i++) {
                elements[i].textContent = formatPrice(elements[i].textContent);
            }
        }

        let priceInputs = document.getElementsByClassName('price-input-class');
        if (priceInputs.length > 0) {
            for (let i = 0; i < priceInputs.length; i++) {
                priceInputs[i].addEventListener('input', formatPriceOnInput);
                formatPriceOnInput({ target: priceInputs[i] });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            formatDisplayPriceByClass('price-display-class');
        });
    </script>
    <script src="{{ asset('js/factor-list.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-start">
                        <div>
                            <h3>فاکتور</h3>
                        </div>
                        <div class="hide-from-md mr-auto">
                            @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('factor-insert') }}" class="btn btn-beta-solid text-left">
                                <i class="fa fa-plus"></i>
                                افزودن فاکتور
                            </a>
                            @endif
                        </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                @if (auth()->user()->hasRole('admin'))
                    <div class="hide-on-mobile">
                        <a href="{{ route('factor-insert') }}" class="btn btn-beta-solid">
                            <i class="fa fa-plus"></i>
                            افزودن فاکتور
                        </a>
                    </div>
                @endif
                <form method="get" action="">
                    <div class="d-flex align-items-center">
                        <div class="search-container">
                            <button class="search-button"><i class="fa fa-search"></i></button>
                            <input type="text" name="search_query" value="{{ request('search_query') }}" class="search-input" placeholder="جستجو کنید...">
                        </div>

                        <div class="dropdown custom-dropdown">
                            <button class="btn btn-white-new dropdown-toggle d-inline-flex align-items-center position-relative" type="button" id="filterDropdown" style="gap: 8px;">
                                <i class="fa fa-filter"></i> فیلتر ها
                                <span id="filterBadge" class="badge badge-danger bg-beta" style="color: white; border-radius: 50%; padding: 2px 6px; font-size: 11px; display: none; margin-left: 4px;">0</span>
                            </button>

                            <div class="dropdown-menu rounded-5" id="filterMenu" style="padding: 15px; min-width: 320px;">
                                <div class="mb-2">
                                    <div class="input-wrapper has-icon">
                                        <input type="text" class="form-control custom-radius" id="factor_date" name="factor_date" data-jdp value="{{ request()->get('factor_date') }}" placeholder="تاریخ فاکتور">
                                        <div class="icon-box" onclick="document.getElementById('factor_date').focus()"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="input-wrapper has-icon">
                                        <input type="text" class="form-control custom-radius" id="created_at" name="created_at" data-jdp placeholder="تاریخ صدور" value="{{ request()->get('created_at') }}">
                                        <div class="icon-box" onclick="document.getElementById('created_at').focus()"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="input-wrapper has-icon">
                                        <input type="text" class="form-control custom-radius" id="payed_factor_data" name="payed_factor_data" data-jdp placeholder="تاریخ پرداخت">
                                        <div class="icon-box" onclick="document.getElementById('payed_factor_data').focus()"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <select name="price_status" class="form-control custom-radius custom-select-input" >
                                        <option value="">وضعیت پرداخت</option>
                                        <option value="0" {{ request()->get('price_status') == '0' ? 'selected' : '' }}>در حال پرداخت</option>
                                        <option value="1" {{ request()->get('price_status') == '1' ? 'selected' : '' }}>پرداخت نشده</option>
                                        <option value="2" {{ request()->get('price_status') == '2' ? 'selected' : '' }}>پرداخت شده</option>
                                        <option value="3" {{ request()->get('price_status') == '3' ? 'selected' : '' }}>کارت به کارت</option>
                                        <option value="4" {{ request()->get('price_status') == '4' ? 'selected' : '' }}>معلق شده</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <select class="form-control custom-radius  select2" name="store_id" data-placeholder=" فروشگاه">
                                        <option value="">همه </option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}" {{ request()->get('store_id') == $store->id ? 'selected' : '' }}>{{ $store->store_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <select name="category_id" class="form-control custom-radius select2" data-placeholder=" دسته بندی">
                                        <option value=""> دسته بندی</option>
                                        @foreach($categories as $category)
                                            @if($category->active == 1)
                                                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                                    {{ $category->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

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

            <div class="x_panel rounded-top mt-3 p-0">
                <table class="table">
                    <thead class="responsive-table-head">
                        <tr>
                            <th>همه</th>
                            <th>نام فروشگاه</th>
                            <th>قیمت (ریال)</th>
                            <th>تاریخ فاکتور</th>
                            @if (auth()->user()->hasRole('admin'))
                            <th>تاریخ صدور فاکتور</th>
                            @endif
                            <th>تاریخ پرداخت</th>
                            @if (auth()->user()->hasRole('admin'))
                            <th>دسته بندی</th>
                            @endif
                            @if (auth()->user()->hasRole('admin'))
                            <th>وضعیت نمایش</th>
                            @endif
                            <th>وضعیت مالی</th>
                            <th>توضیحات</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($factors as $factor)
                            @if (auth()->user()->hasRole('seller') && $factor->show_status == 0)
                                @continue
                            @endif
                            <tr class="responsive-table-row item-record{{ $factor->id }}">
                            <th scope="row" class="responsive-table-td">
                                        {{ $factor->id }}
                                    </th>
                                <td data-title="نام فروشگاه" class="responsive-table-td">
                                    {{ $factor->store->store_name ?? 'مشتری' }}</td>
                                <td data-title="قیمت" class="responsive-table-td fa-number">
                                    {{ number_format($factor->price) }}</td>
                                <td data-title="تاریخ فاکتور" class="responsive-table-td fa-number">
                                    {{ Verta($factor->factor_date)->format(' %d %B  %Y') }}</td>
                                @if (auth()->user()->hasRole('admin'))
                                <td data-title="تاریخ صدور" class="responsive-table-td fa-number">
                                    {{ Verta($factor->created_at)->format(' %d %B  %Y') }}</td>
                                @endif
                                <td data-title="تاریخ پرداخت" class="responsive-table-td fa-number">
                                    {{ $factor->paid_factor_date ? Verta($factor->paid_factor_date)->format(' %d %B  %Y') : '' }}
                                </td>
                                @if (auth()->user()->hasRole('admin'))
                                <td data-title="دسته بندی" class="responsive-table-td">{{ $factor->category->name }}</td>
                                @endif

                                @if (auth()->user()->hasRole('admin'))
                                <td data-title="وضعیت نمایش" class="responsive-table-td">
                                    @if ($factor->show_status == 0)
                                        <span class="bg-red-new p-2 custom-radius">
                                            غیرفعال
                                        </span>
                                    @elseif($factor->show_status == 1)
                                        <span class="bg-jade p-2 custom-radius">
                                            فعال
                                        </span>
                                    @endif
                                </td>
                                @endif
                                <td data-title="وضعیت مالی" class="responsive-table-td">
                                    @if ($factor->price_status == 0)
                                        <span class="bg-warning p-2 custom-radius">
                                            در حال پرداخت
                                        </span>
                                    @elseif($factor->price_status == 1)
                                        <span class="bg-red-new p-2 custom-radius">
                                            پرداخت نشده
                                        </span>
                                    @elseif($factor->price_status == 2)
                                        <span class="bg-jade p-2 custom-radius">
                                            پرداخت شده
                                        </span>
                                    @elseif($factor->price_status == 3)
                                        <span class="bg-new p-2 custom-radius">
                                            کارت به کارت
                                        </span>
                                    @elseif($factor->price_status == 4)
                                        <span class="bg-file p-2 custom-radius">
                                            معلق شده
                                        </span>
                                    @endif
                                </td>
                                <td data-title="توضیحات" class="responsive-table-td">{{ $factor->description }}</td>
                                <td data-title="عملیات" class="responsive-table-td">
                                    <div class="gap">
                                        <a href="{{ route('factor-show', $factor->id) }}" target="_blank"
                                            class="text-primary">
                                            <i class="fa fa-eye fa-x"></i>
                                        </a>

                                        @if (auth()->user()->hasRole('admin'))
                                            <a href="{{ route('factor-edit', $factor->id) }}" class="text-success"
                                                data-id="{{ $factor->id }}">
                                                <i class="fa fa-pencil fa-x"></i>
                                            </a>
                                            <a href="javascript:;" id="copyHash" class="text-secondary"
                                                data-id="{{ $factor->id }}">
                                                <i class="fa fa-copy fa-x"></i>
                                            </a>
                                            <a href="javascript:;" class="text-danger delete-factor"
                                                data-id="{{ $factor->id }}">
                                                <i class="fa fa-trash fa-x"></i>
                                            </a>

                                        @elseif(auth()->user()->hasRole('seller'))
                                            <a href="#" class="text-success"
                                                style="display: inline-block; padding: 6px 18px; background: #e8f5e9; border-radius: 8px; font-size: 14px;margin: 0px;"
                                                data-toggle="modal" data-target="#paymentModal"
                                                onclick="setPaymentData({{ $factor->id }}, '{{ addslashes($factor->description) }}', {{ $factor->price }})">
                                                <i class="fa fa-credit-card"></i> پرداخت
                                            </a>
                                            @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">
                                    <i class="fa fa-search fa-2x mb-2 d-block"></i>
                                    نتیجه‌ای برای جستجو یافت نشد
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{$factors->withQueryString()->links('vendor.pagination.bootstrap-5')}}
            </div>
        </div>
    </div>
    @include('templates.factor.Modal.payment-modal')
@endsection
