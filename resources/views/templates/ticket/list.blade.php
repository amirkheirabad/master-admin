@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/ticket-list.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <script src="{{ asset('/js/ticket-list.js') }}"></script>
    <script>

    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between">
                    <div>
                        <h3>تیکت ها</h3>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-8 flex-wrap">
                @if(auth()->user()->hasRole('admin'))
                <div class="hide-on-mobile">
                    <button id="operations-button" class="btn btn-white-new" disabled>
                        <span class="hide-on-mobile">عملیات</span>
                        <i class="fa fa-chevron-down mr-1"></i>
                    </button>
                </div>
                @endif

                <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                    <form method="get" id="filterForm" action="{{ route('list_tickets') }}" class="d-flex flex-wrap align-items-center">
                        <div class="search-container">
                            <button class="search-button">
                                <i class="fa fa-search"></i>
                            </button>
                            <input type="text" name="search_query" value="{{ request('search_query') }}" class="search-input" placeholder="جستجو کنید...">
                        </div>

                        <div class="hide-from-md">
                            <button id="mobileSortBtn" class="btn btn-white-new">
                                <span class="hide-on-mobile">مرتب سازی</span>
                                <img src="{{ asset('/icons/sort.svg') }}" style="width: 11px;height: 18px">
                            </button>
                        </div>

                        <div class="dropdown custom-dropdown">
                            <button
                                class="btn btn-white-new dropdown-toggle d-inline-flex align-items-center position-relative"
                                type="button"
                                id="filterDropdown"
                                style="gap: 8px;"
                            >
                                <i class="fa fa-filter p-1"></i>
                                <span class="hide-on-mobile">فیلتر ها</span>
                                <span id="filterBadge" class="badge badge-danger bg-beta" style="color: white; border-radius: 50%; padding: 2px 6px; font-size: 11px; display: none; margin-left: 4px;">0</span>
                            </button>

                            <!-- منوی دسکتاپ - نسخه دستی -->
                            <div class="dropdown-menu rounded-5 desktop-filter-menu" id="filterMenu" style="padding: 15px; min-width: 320px;">
                                <!-- محتوای فیلترها -->
                                <div class="mb-2">
                                    <select class="form-control custom-radius select2" name="store_id" data-title="نام فروشگاه:">
                                        <option value="">همه</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ request()->get('store_id') == $store->id ? 'selected' : '' }}>
                                                {{ $store->store_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <select name="status" class="form-control custom-radius custom-select-input" data-title="وضعیت:">
                                        <option value="">همه</option>
                                        <option value="0" {{ request()->get('status') == '0' ? 'selected' : ''}}>در حال برسی توسط ایندکس</option>
                                        <option value="1" {{ request()->get('status') == '1' ? 'selected' : ''}}>منتظر پاسخ فروشگاه</option>
                                        <option value="2" {{ request()->get('status') == '2' ? 'selected' : ''}}>بسته شده</option>
                                        <option value="3" {{ request()->get('status') == '3' ? 'selected' : ''}}>ارجاع به واحد فنی</option>
                                        <option value="4" {{ request()->get('status') == '4' ? 'selected' : ''}}>ارجاع به واحد گرافیک دیزاین</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <select name="priority" class="form-control custom-radius custom-select-input" data-title="اولویت:">
                                        <option value="">همه</option>
                                        <option value="1" {{ request('priority') == '1' ? 'selected' : '' }}>کم</option>
                                        <option value="2" {{ request('priority') == '2' ? 'selected' : '' }}>متوسط</option>
                                        <option value="3" {{ request('priority') == '3' ? 'selected' : '' }}>بالا</option>
                                        <option value="4" {{ request('priority') == '4' ? 'selected' : '' }}>فوری</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <select name="contact_name" class="form-control custom-radius custom-select-input" data-title="تیم مخاطب:">
                                        <option value="">همه</option>
                                        <option value="0" {{ request()->get('contact_name') == '0' ? 'selected' : ''}}>درخواست ماژول با فیچر جدید</option>
                                        <option value="1" {{ request()->get('contact_name') == '1' ? 'selected' : ''}}>تیم فنی و عملیات</option>
                                        <option value="2" {{ request()->get('contact_name') == '2' ? 'selected' : ''}}>تیم پشتیبانی و سفارشات</option>
                                        <option value="3" {{ request()->get('contact_name') == '3' ? 'selected' : ''}}>گزارش خطا</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <select name="sort" class="form-control custom-radius custom-select-input" data-title="مرتب سازی:">
                                        <option value="latest" {{ request()->get('sort') == 'latest' ? 'selected' : ''}}>جدید ترین</option>
                                        <option value="oldest" {{ request()->get('sort') == 'oldest' ? 'selected' : ''}}>قدیمی ترین</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" id="clearFiltersBtn" class="btn btn-link text-default text-bold" style="padding: 0;">حذف فیلترها</button>
                                    <button type="submit" class="btn btn-beta-solid mr-6">اعمال</button>
                                    <button type="button" id="cancelFilterBtn" class="btn btn-beta-outline">انصراف</button>
                                </div>
                            </div>
                        </div>

                        <!-- مودال شیت مخصوص موبایل -->

                    </form>
                </div>
            </div>

            <div class="x_panel rounded-top mt-2 p-0">
                <table class="table">
                    <thead class="responsive-table-head">
                    <tr>
                        <th>
                            <input type="checkbox" id="main-checkbox" class="form-check-input">
                        </th>
                        <th>
                            همه
                        </th>
                        <th>نام فروشگاه</th>
                        <th>عنوان تیکت</th>
                        <th>اولویت</th>
                        <th>تیم مخاطب</th>
                        <th class="hide-on-mobile">تاریخ ثبت</th>
                        <th class="hide-on-mobile">وضعیت</th>
                        <th class="hide-on-mobile">تاریخ آخرین پاسخ</th>
                        <th class="hide-on-mobile">مشاهده</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($tickets as $ticket)
                        <tr class="responsive-table-row item-record{{$ticket->id}}">
                            <th scope="row" class="responsive-table-td">
                                <input type="checkbox" class="form-check-input hide-on-mobile" data-id="{{$ticket->id}}">
                                <div class="hide-from-md" style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-direction: row;">
                                    <div class="text-bold">
                                    تیکت {{$ticket->id}} - {{ $ticket->recipient_type === 'store' ? $ticket->store?->store_name : $ticket->user?->name }}
                                    </div>
                                    <div>
                                        <a href="{{ route('show_ticket', $ticket->id) }}">
                                            <img src="{{ asset('/icons/Group.svg') }}" style="width: 30px">
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <td data-title="نام فروشگاه:" class="hide-on-mobile">{{ $ticket->id }}</td>
                            <td data-title="نام فروشگاه:" class="hide-on-mobile">{{ $ticket->recipient_type === 'store' ? $ticket->store?->store_name : $ticket->user?->name }}</td>
                            <td data-title="عنوان:" class="responsive-table-td">{{ $ticket->title }}</td>
                             <td data-title="اولویت" class="responsive-table-td">
                                @if($ticket->priority == 1)
                                    <span class="bg-jade p-2 custom-radius">
                                   کم
                                </span>
                                @elseif($ticket->priority == 2)
                                    <span class="bg-new p-2 custom-radius">
                                   متوسط
                                </span>
                                @elseif($ticket->priority == 3)
                                    <span class="bg-warning p-2 custom-radius">
                                   بالا
                                </span>
                                @elseif($ticket->priority == 4)
                                    <span class="bg-red-new p-2 custom-radius">
                                   فوری
                                </span>
                                @endif
                            </td>
                            <td data-title=" تیم مخاطب:" class="responsive-table-td">
                                @if($ticket->contact_name == 0)
                                    <span>
                                    درخواست ماژول با فیچر جدید
                                </span>
                                @elseif($ticket->contact_name == 1)
                                    <span>
                                    تیم فنی و عملیات
                                </span>
                                @elseif($ticket->contact_name == 2)
                                    <span>
                                    تیم پشتیبانی و سفارشات
                                </span>
                                @elseif($ticket->contact_name == 3)
                                    <span>
                                    گزارش خطا
                                </span>
                                @endif
                            </td>
                            <td data-title="تاریخ:" class="responsive-table-td fa-number">{{ Verta($ticket->created_at)->format(' %d %B  %Y') }}</td>
                            <td data-title="وضعیت:" class="responsive-table-td">
                                @if($ticket->status == 0)
                                    <span class="bg-warning p-2 custom-radius">
                                    در حال برسی توسط ایندکس
                                </span>
                                @elseif($ticket->status == 1)
                                    <span class="bg-red-new p-2 custom-radius">
                                    منتظر پاسخ فروشگاه
                                </span>
                                @elseif($ticket->status == 2)
                                    <span class="bg-jade p-2 custom-radius">
                                    بسته شده
                                </span>
                                @elseif($ticket->status == 3)
                                    <span class="bg-new p-2 custom-radius">
                                    ارجاع به واحد فنی
                                </span>
                                @elseif($ticket->status == 4)
                                    <span class="bg-new p-2 custom-radius">
                                    ارجاع به واحد گرافیک دیزاین
                                </span>
                                @endif
                            </td>
                            <td data-title="تاریخ آخرین پاسخ:" class="hide-on-mobile fa-number">{{ Verta($ticket->updated_at)->format(' %d %B  %Y') }}</td>
                            <td data-title="نام فروشگاه:" class="hide-on-mobile">
                                <a href="{{ route('show_ticket', $ticket->id) }}">
                                    <img src="{{ asset('/icons/Group.svg') }}" style="width: 30px">
                                </a>
                            </td>
                        </tr>
                        @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">
                                        <i class="fa fa-search fa-2x mb-2 d-block"></i>
                                        نتیجه‌ای برای جستجو یافت نشد
                                    </td>
                                </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{$tickets->withQueryString()->links('vendor.pagination.bootstrap-5')}}
            </div>
        </div>
    </div>
    @include('templates.ticket.Modal.modal-list')
@endsection

