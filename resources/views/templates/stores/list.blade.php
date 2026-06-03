@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <script src="{{ asset('/js/generate-token.js') }}"></script>
    <script src="{{ asset('/js/stores-list.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-start">
                        <div>
                            <h3>لیست فروشگاه ها</h3>
                        </div>
                        <div class="hide-from-md mr-auto">
                            <a href="{{ route('insert_store') }}" class="btn btn-beta-solid text-left">
                                <i class="fa fa-plus"></i>
                                اضافه کردن فروشگاه
                            </a>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <div class="hide-on-mobile">
                        <a href="{{ route('insert_store') }}" class="btn btn-beta-solid">
                            <i class="fa fa-plus"></i>
                            اضافه کردن فروشگاه
                        </a>
                    </div>

                    <form method="get" action="">
                        <div class="d-flex align-items-center">
                            <div class="search-container">
                                <button class="search-button"><i class="fa fa-search"></i></button>
                                <input type="text" name="search_query" value="{{ request('search_query') }}"
                                    class="search-input" placeholder="جستجو کنید...">
                            </div>

                            <div class="dropdown custom-dropdown">
                                <button
                                    class="btn btn-white-new dropdown-toggle d-inline-flex align-items-center position-relative"
                                    type="button" id="filterDropdown" style="gap: 8px;">
                                    <i class="fa fa-filter"></i> فیلتر ها
                                    <span id="filterBadge" class="badge badge-danger bg-beta"
                                        style="color: white; border-radius: 50%; padding: 2px 6px; font-size: 11px; display: none; margin-left: 4px;">0</span>
                                </button>

                                <div class="dropdown-menu rounded-5" id="filterMenu"
                                    style="padding: 15px; min-width: 320px;">
                                    <div class="mb-2">
                                        <select name="user_id" class="form-control custom-radius select2"
                                            data-placeholder="نام مدیر">
                                            <option value="">نام مدیر</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <input type="text" name="province" value="{{ request('province') }}"
                                            class="form-control custom-radius" placeholder="استان">
                                    </div>

                                    <div class="mb-2">
                                        <input type="text" name="city" value="{{ request('city') }}"
                                            class="form-control custom-radius" placeholder="شهر">
                                    </div>

                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" id="clearFiltersBtn"
                                            class="btn btn-link text-default text-bold" style="padding: 0;">حذف
                                            فیلترها</button>
                                        <button type="submit" class="btn btn-beta-solid mr-6">اعمال</button>
                                        <button type="button" class="btn btn-beta-outline"
                                            id="cancelFilterBtn">انصراف</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="x_panel rounded-top mt-2 p-0">
                    <table class="table" style="margin-bottom: 0px">
                        <thead class="responsive-table-head">
                            <tr>
                                <th>#</th>
                                <th>نام فروشگاه</th>
                                <th>نام مدیر</th>
                                <th>آدرس وبسایت</th>
                                <th>شماره تماس</th>
                                <th>استان</th>
                                <th>شهر</th>
                                <th>آدرس فروشگاه</th>
                                <th>کد پستی</th>

                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($stores as $store)
                            <tr class="responsive-table-row item-record{{$store->id}}">
                                <th scope="row" class="responsive-table-td">
                                    {{ ($stores->currentPage() - 1) * $stores->perPage() + $loop->iteration }}
                                </th>
                                <td data-title="نام فروشگاه" class="responsive-table-td">{{ $store->store_name }}</td>
                                <td data-title="نام مدیر" class="responsive-table-td">{{ $store->user->name }}</td>
                                <td data-title="آدرس وبسایت" class="responsive-table-td">{{ $store->link }}</td>
                                <td data-title="شماره تماس" class="responsive-table-td">{{ $store->phone }}</td>
                                <td data-title="استان" class="responsive-table-td">{{ $store->province }}</td>
                                <td data-title="شهر" class="responsive-table-td">{{ $store->city }}</td>
                                <td data-title="آدرس فروشگاه" class="responsive-table-td">{{ $store->location }}</td>
                                <td data-title="کد پستی" class="responsive-table-td">{{ $store->code_posty }}</td>
                                <td data-title="عملیات" class="responsive-table-td">
                                    <div class="action-buttons gap">
                                        <a href="{{ route('edit_store', $store->id) }}" class="text-success" title="ویرایش">
                                            <i class="fa fa-pencil fa-x"></i>
                                        </a>
                                        <a href="javascript:;" class="text-danger delete-message" data-id="{{ $store->id }}" title="حذف">
                                            <i class="fa fa-trash fa-x"></i>
                                        </a>
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
                    {{ $stores->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
