@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/jalalidatepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/jalalidatepicker.min.js') }}"></script>
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <script src="{{ asset('/js/create-factor.js') }}"></script>
    <script>
        function resetAllFilters() {
            // ریست تقویم‌ها
            document.getElementById('factor_date').value = '';
            document.getElementById('created_at').value = '';
            document.getElementById('payed_factor_data').value = '';

            // ریست select معمولی price_status
            const priceStatus = document.querySelector('select[name="price_status"]');
            if (priceStatus) {
                priceStatus.selectedIndex = 0;
            }

            // ریست select2 فروشگاه
            const storeSelect = document.querySelector('select[name="store_id"]');
            if (storeSelect) {
                storeSelect.value = '';
                // فراخوانی change برای آپدیت select2
                const event = new Event('change', { bubbles: true });
                storeSelect.dispatchEvent(event);
            }

            $('select.custom-select-input').each(function() {
                $(this).prop('selectedIndex', 0);

                const wrapper = this.nextElementSibling;
                if (wrapper && wrapper.classList.contains('cs-wrapper')) {
                    const firstOption = this.options[0];
                    if (firstOption) {
                        $(wrapper).find('.cs-trigger-text').text(firstOption.text);
                        $(wrapper).find('input[type="hidden"]').val(firstOption.value);
                        $(wrapper).find('.cs-option').each(function(index) {
                            if (index === 0) $(this).addClass('cs-selected');
                            else $(this).removeClass('cs-selected');
                        });
                    }
                }
            });

            // ریست select2 دسته‌بندی
            const categorySelect = document.querySelector('select[name="category_id"]');
            if (categorySelect) {
                categorySelect.value = '';
                const event = new Event('change', { bubbles: true });
                categorySelect.dispatchEvent(event);
            }
        }

        document.getElementById('clearFiltersBtn')?.addEventListener('click', function(e) {
            e.preventDefault();
            resetAllFilters();
        });
    </script>

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
                            <a href="{{ route('factor-insert') }}" class="btn btn-beta-solid">
                                <i class="fa fa-plus"></i>
                                افزودن فاکتور
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div class="hide-on-mobile">
                    <a href="{{ route('factor-insert') }}" class="btn btn-beta-solid">
                        <i class="fa fa-plus"></i>
                        افزودن فاکتور
                    </a>
                </div>
                <form method="get" action="">
                    <div class="d-flex align-items-center">
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
                                <!-- محتوای فیلترها مثل قبل -->
                                <div class="mb-2">
                                    <div class="input-wrapper has-icon">
                                        <input type="text"
                                               class="form-control custom-radius"
                                               id="factor_date"
                                               name="factor_date"
                                               data-jdp
                                               value="{{ request()->get('factor_date') }}"
                                               placeholder="انتخاب تاریخ فاکتور">
                                        <div class="icon-box" onclick="document.getElementById('factor_date').focus()">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="input-wrapper has-icon">
                                        <input type="text"
                                               class="form-control custom-radius"
                                               id="created_at"
                                               name="created_at"
                                               data-jdp
                                               placeholder="انتخاب تاریخ صدور فاکتور"
                                               value="{{ request()->get('created_at') }}">
                                        <div class="icon-box" onclick="document.getElementById('created_at').focus()">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="input-wrapper has-icon">
                                        <input type="text"
                                               class="form-control custom-radius"
                                               id="payed_factor_data"
                                               name="payed_factor_data"
                                               data-jdp
                                               placeholder="انتخاب تاریخ پرداخت فاکتور">
                                        <div class="icon-box" onclick="document.getElementById('payed_factor_data').focus()">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <select name="price_status" class="form-control custom-radius custom-select-input">
                                        <option value="">همه</option>
                                        <option value="0" {{ request()->get('price_status') == '0' ? 'selected' : ''}}>در حال پرداخت</option>
                                        <option value="1" {{ request()->get('price_status') == '1' ? 'selected' : ''}}>پرداخت نشده</option>
                                        <option value="2" {{ request()->get('price_status') == '2' ? 'selected' : ''}}>پرداخت شده</option>
                                        <option value="3" {{ request()->get('price_status') == '3' ? 'selected' : ''}}>کارت به کارت</option>
                                        <option value="4" {{ request()->get('price_status') == '4' ? 'selected' : ''}}>معلق شده</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <select class="form-control custom-radius select2" name="store_id">
                                        <option value="">انتخاب فروشگاه</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ request()->get('store_id') == $store->id ? 'selected' : '' }}>
                                                {{ $store->store_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <select name="category_id" class="form-control custom-radius select2">
                                        <option value="">همه</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request()->get('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <p type="button" id="clearFiltersBtn" class="mt-3 text-default text-bold" style="display: flex">حذف فیلترها</p>
                                    <button type="submit" class="btn btn-beta-solid mr-6">اعمال</button>
                                    <button type="button" class="btn btn-beta-outline" id="cancelFilterBtn">انصراف</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
{{--                <h5>تعداد کل رکورد ها: {{ count($factors) }}</h5>--}}
                <div class="x_panel rounded-top mt-3 p-0">
                    <table class="table">
                        <thead class="responsive-table-head">
                        <tr>
                            <th>همه</th>
                            <th>نام فروشگاه</th>
                            <th>قیمت (ریال)</th>
                            <th>تاریخ فاکتور</th>
                            <th>تاریخ صدور فاکتور</th>
                            <th>توضیحات</th>
                            <th>وضعیت نمایش</th>
                            <th>دسته بندی</th>
                            <th>وضعیت مالی</th>
                            <th>تاریخ پرداخت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($factors as $factor)
                            <tr class="responsive-table-row item-record{{$factor->id}}">
                                <th scope="row" class="responsive-table-td">{{ $factor->id }}</th>
                                <td data-title="نام فروشگاه" class="responsive-table-td">{{ $factor->store->store_name ?? 'مشتری' }}</td>
                                <td data-title="قیمت" class="responsive-table-td price-display-class">{{ $factor->price }}</td>
                                <td data-title="" class="responsive-table-td">{{ Verta($factor->factor_date)->format(' %d %B  %Y') }}</td>
                                <td data-title="" class="responsive-table-td">{{ Verta($factor->created_at)->format(' %d %B  %Y') }}</td>
                                <td data-title="توضیحات" class="responsive-table-td">{{ $factor->description }}</td>
                                <td data-title="وضعیت" class="responsive-table-td">
                                    @if($factor->show_status == 0)
                                        <span class="bg-warning p-2 custom-radius">
                                        غیرفعال
                                    </span>
                                    @elseif($factor->show_status == 1)
                                        <span class="bg-jade p-2 custom-radius">
                                        فعال
                                    </span>
                                    @endif
                                </td>
                                <td data-title="نام فروشگاه" class="responsive-table-td">{{ $factor->category->name }}</td>
                                <td data-title="وضعیت" class="responsive-table-td">
                                    @if($factor->price_status == 0)
                                        <span class="bg-warning p-2 custom-radius">
                                        در حال پرداخت
                                    </span>
                                    @elseif($factor->price_status == 1)
                                        <span class="bg-danger p-2 custom-radius">
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
                                        <span class="bg-green p-2 custom-radius">
                                        معلق شده
                                    </span>
                                    @endif
                                </td>
                                <td data-title="نام فروشگاه" class="responsive-table-td"> {{ $factor->paid_factor_date ? Verta($factor->paid_factor_date)->format(' %d %B  %Y') : '' }}</td>
                                <td data-title="عملیات" class="responsive-table-td">
                                    <div class="gap">
                                        <a href="{{ route('factor-show', $factor->id) }}" target="_blank" class="text-primary">
                                            <i class="fa fa-eye fa-x"></i>
                                        </a>
                                        <a href="{{ route('factor-edit', $factor->id) }}" class="text-success" data-id="{{ $factor->id }}">
                                            <i class="fa fa-pencil fa-x"></i>
                                        </a>
                                        <a href="javascript:;" id="copyHash" class="text-secondary" data-id="{{ $factor->id }}">
                                            <i class="fa fa-copy fa-x"></i>
                                        </a>
                                        <a href="javascript:;" class="text-danger delete-factor" data-id="{{ $factor->id }}">
                                            <i class="fa fa-trash fa-x"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <script>
                    function formatPrice(price) {
                        let numericPrice = String(price).replace(/[^0-9]/g, '');

                        if (numericPrice === '') {
                            return '';
                        }

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

            </div>
        </div>
@endsection

