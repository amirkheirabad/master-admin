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
        // ========== فیلترها (بدون تغییر) ==========
        function resetAllFilters() {
            document.getElementById('factor_date').value = '';
            document.getElementById('created_at').value = '';
            document.getElementById('payed_factor_data').value = '';

            const priceStatus = document.querySelector('select[name="price_status"]');
            if (priceStatus) priceStatus.selectedIndex = 0;

            const storeSelect = document.querySelector('select[name="store_id"]');
            if (storeSelect) {
                storeSelect.value = '';
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

        // مودال پرداخت
        function setPaymentData(id, title, amount) {
            let formattedAmount = new Intl.NumberFormat('fa-IR').format(amount);

            let html = `
                <div style="direction: rtl; text-align: right;">
                    <div style="background: white; border-radius: 12px; padding: 15px; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-right: 3px solid #0e2d55;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #e9ecef;">
                            <span style="color: #64748b; font-size: 13px;">شماره فاکتور</span>
                            <span style="color: #0e2d55; font-weight: 600;">${id}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #e9ecef;">
                            <span style="color: #64748b; font-size: 13px;">عنوان</span>
                            <span style="color: #1e293b;">${title || 'فاکتور فروش'}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #64748b; font-size: 13px;">مبلغ قابل پرداخت</span>
                            <span style="color: #0e2d55; font-weight: 700; font-size: 18px;">${formattedAmount} <span style="font-size: 12px;">تومان</span></span>
                        </div>
                    </div>
                    <p style="margin-bottom: 15px; color: #334155; font-weight: 500; font-size: 14px;">بانک خود را انتخاب کنید:</p>
                    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">

                        <div class="bank-option" data-bank="saman" style="cursor: pointer; text-align: center; width: 100px; padding: 12px 8px; background: white; border-radius: 12px; border: 2px solid #e2e8f0;">
                            <img src="{{ asset('icons/saman.svg') }}" width="50" style="margin-bottom: 8px;">
                            <div style="font-size: 12px; color: #475569;">بانک سامان</div>
                        </div>

                    </div>
                    <input type="hidden" id="selectedBank" value="">
                    <input type="hidden" id="factorId" value="${id}">
                </div>
            `;
        <div style="direction: rtl; text-align: right;">
            {{-- اطلاعات فاکتور --}}
            <div style="background: white; border-radius: 12px; padding: 15px; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-right: 3px solid #0e2d55;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #e9ecef;">
                    <span style="color: #64748b; font-size: 13px;">شماره فاکتور</span>
                    <span style="color: #0e2d55; font-weight: 600;">${id}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #e9ecef;">
                    <span style="color: #64748b; font-size: 13px;">عنوان</span>
                    <span style="color: #1e293b;">${title || 'فاکتور فروش'}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b; font-size: 13px;">مبلغ قابل پرداخت</span>
                    <span class='price-display-class' style="color: #0e2d55; font-weight: 700; font-size: 18px;">${formattedAmount} <span style="font-size: 12px;">تومان</span></span>
                </div>
            </div>

            {{-- انتخاب بانک --}}
            <p style="margin-bottom: 15px; color: #334155; font-weight: 500; font-size: 14px;">بانک خود را انتخاب کنید:</p>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <div class="bank-option" data-bank="mellat" style="cursor: pointer; text-align: center; width: 100px; padding: 12px 8px; background: white; border-radius: 12px; transition: all 0.2s; border: 2px solid #e2e8f0;">
                    <img src="{{ asset('icons/mellat.svg') }}" width="50" style="margin-bottom: 8px;">
                    <div style="font-size: 12px; color: #475569;">بانک ملت</div>
                </div>
                <div class="bank-option" data-bank="saman" style="cursor: pointer; text-align: center; width: 100px; padding: 12px 8px; background: white; border-radius: 12px; transition: all 0.2s; border: 2px solid #e2e8f0;">
                    <img src="{{ asset('icons/saman.svg') }}" width="50" style="margin-bottom: 8px;">
                    <div style="font-size: 12px; color: #475569;">بانک سامان</div>
                </div>
                <div class="bank-option" data-bank="iranzamin" style="cursor: pointer; text-align: center; width: 100px; padding: 12px 8px; background: white; border-radius: 12px; transition: all 0.2s; border: 2px solid #e2e8f0;">
                    <img src="{{ asset('icons/iran-zamin.svg') }}" width="50" style="margin-bottom: 8px;">
                    <div style="font-size: 12px; color: #475569;">بانک ایران زمین</div>
                </div>
            </div>
            <input type="hidden" id="selectedBank" value="">
            <input type="hidden" id="factorId" value="${id}">
        </div>
    `;

            document.getElementById('paymentModalBody').innerHTML = html;

            // افزودن رویداد انتخاب بانک
            document.querySelectorAll('.bank-option').forEach(el => {
                el.onclick = function() {
                    document.querySelectorAll('.bank-option').forEach(opt => {
                        opt.style.border = '2px solid #e2e8f0';
                        opt.style.background = 'white';
                    });
                    this.style.border = '2px solid #0e2d55';
                    this.style.background = '#f0f4f8';
                    document.getElementById('selectedBank').value = this.getAttribute('data-bank');
                };
            });
        }

        // رویداد کلیک دکمه پرداخت (فقط یک بار در کل صفحه)
        document.addEventListener('DOMContentLoaded', function() {
            const confirmBtn = document.getElementById('confirmPaymentBtn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    let bank = document.getElementById('selectedBank')?.value;
                    let factorId = document.getElementById('factorId')?.value;

                    if (!bank) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'توجه',
                            text: 'لطفاً یک بانک را انتخاب کنید',
                            confirmButtonText: 'باشه',
                            confirmButtonColor: '#0e2d55'
                        });
                        return;
                    }

                    // رفتن به درگاه پرداخت
                    window.location.href = `/factor/pay/${factorId}`;
                });
            }
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
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h3 class="mb-2 mb-md-0">فاکتور</h3>
                            </div>
                            <div class="hide-from-md">
                                <a href="{{ route('factor-insert') }}" class="btn btn-beta-solid">
                                    <i class="fa fa-plus"></i>
                                    افزودن فاکتور
                                </a>
                            </div>
                        </div>
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
                                <!-- تاریخ فاکتور -->
                                <div class="mb-2">
                                    <div class="input-wrapper has-icon">
                                        <input type="text" class="form-control custom-radius" id="factor_date" name="factor_date" data-jdp value="{{ request()->get('factor_date') }}" placeholder="تاریخ فاکتور">
                                        <div class="icon-box" onclick="document.getElementById('factor_date').focus()"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>

                                <!-- تاریخ صدور -->
                                <div class="mb-2">
                                    <div class="input-wrapper has-icon">
                                        <input type="text" class="form-control custom-radius" id="created_at" name="created_at" data-jdp placeholder="تاریخ صدور" value="{{ request()->get('created_at') }}">
                                        <div class="icon-box" onclick="document.getElementById('created_at').focus()"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>

                                <!-- تاریخ پرداخت -->
                                <div class="mb-2">
                                    <div class="input-wrapper has-icon">
                                        <input type="text" class="form-control custom-radius" id="payed_factor_data" name="payed_factor_data" data-jdp placeholder="تاریخ پرداخت">
                                        <div class="icon-box" onclick="document.getElementById('payed_factor_data').focus()"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>

                                <!-- وضعیت مالی -->
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

                                <!-- فروشگاه -->
                                <div class="mb-2">
                                    <select class="form-control custom-radius  select2" name="store_id" data-placeholder=" فروشگاه">
                                        <option value="">همه </option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}" {{ request()->get('store_id') == $store->id ? 'selected' : '' }}>{{ $store->store_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- دسته بندی -->
                               <!-- دسته بندی -->
                               <!-- دسته بندی -->
                                <div class="mb-2">
                                    <select name="category_id" class="form-control custom-radius select2" data-placeholder=" دسته بندی">
                                        <option value=""> دسته بندی</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ request()->get('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
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

            <div class="x_panel rounded-top mt-3 p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>همه</th>
                            <th>نام فروشگاه</th>
                            <th>قیمت (ریال)</th>
                            <th>تاریخ فاکتور</th>
                            @if (auth()->user()->hasRole('admin'))
                            <th>تاریخ صدور فاکتور</th>
                            <th>دسته بندی</th>
                            @endif
                            <th>توضیحات</th>
                            <th>وضعیت نمایش</th>
                            <th>وضعیت مالی</th>
                            <th>تاریخ پرداخت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($factors as $factor)
                            <tr class="responsive-table-row item-record{{ $factor->id }}">
                                <th scope="row" class="responsive-table-td">{{ $factor->id }}</th>
                                <td data-title="نام فروشگاه" class="responsive-table-td">
                                    {{ $factor->store->store_name ?? 'مشتری' }}</td>
                                <td data-title="قیمت" class="responsive-table-td price-display-class">
                                    {{ $factor->price }}</td>
                                <td data-title="" class="responsive-table-td">
                                    {{ Verta($factor->factor_date)->format(' %d %B  %Y') }}</td>
                                <td data-title="" class="responsive-table-td">
                                    {{ Verta($factor->created_at)->format(' %d %B  %Y') }}</td>
                                <td data-title="توضیحات" class="responsive-table-td">{{ $factor->description }}</td>
                                <td data-title="وضعیت" class="responsive-table-td">
                                    @if ($factor->show_status == 0)
                                        <span class="bg-warning p-2 custom-radius">
                                            غیرفعال
                                        </span>
                                    @elseif($factor->show_status == 1)
                                        <span class="bg-jade p-2 custom-radius">
                                            فعال
                                        </span>
                                    @endif
                                </td>
                                <td data-title="نام فروشگاه" class="responsive-table-td">{{ $factor->category->name }}
                                </td>
                                <td data-title="وضعیت" class="responsive-table-td">
                                    @if ($factor->price_status == 0)
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
                                <td data-title="نام فروشگاه" class="responsive-table-td">
                                    {{ $factor->paid_factor_date ? Verta($factor->paid_factor_date)->format(' %d %B  %Y') : '' }}
                                </td>
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
                        @endforeach
                    </tbody>
                </table>
            </div>

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
        </div>
    </div>
    @include('templates.factor.Modal.payment-modal')
@endsection
