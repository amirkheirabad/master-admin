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
    <script src="{{ asset('/js/log-list.js') }}"></script>
@endsection


@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                        <div>
                            <h3>گزارشات فاکتور</h3>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-2 mb-3">
                    <div></div>

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
                                        <div class="input-wrapper has-icon">
                                            <input type="text" class="form-control custom-radius input-border-focus" id="created_at" name="created_at" data-jdp placeholder="تاریخ " value="{{ request()->get('created_at') }}">
                                            <div class="icon-box" onclick="document.getElementById('created_at').focus()"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <select name="operation" class="form-control custom-radius custom-select-input" data-title="عملیات:">
                                            <option value="">همه</option>
                                            <option value="created" {{ request()->get('operation') == 'created' ? 'selected' : ''}}>ساخت</option>
                                            <option value="updated" {{ request()->get('operation') == 'updated' ? 'selected' : ''}}>ویرایش</option>
                                            <option value="deleted" {{ request()->get('operation') == 'deleted' ? 'selected' : ''}}>حذف</option>
                                        </select>
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
                    <table class="table">
                        <thead class="">
                        <tr>
                            <th>شماره فاکتور</th>
                            <th>نام کاربر سایت</th>
                            <th>عملیات</th>
                            <th>تاریخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($factors as $factor)
                            <tr class="item-record{{$factor->id}}">
                                <th scope="row">{{$factor->subject_id}}</th>
                                <td>{{$factor->user->name}}</td>
                                <td class="">
                                    @if($factor->event == 'updated')
                                        <span class="bg-warning p-2 custom-radius">
                                            ویرایش
                                        </span>
                                    @endif
                                    @if($factor->event == 'created')
                                        <span class="bg-jade p-2 custom-radius">
                                            ساخت
                                        </span>
                                    @endif
                                    @if($factor->event == 'deleted')
                                        <span class="bg-red-new p-2 custom-radius">
                                            حذف
                                        </span>
                                    @endif
                                </td>
                                <td>{{ Verta($factor->created_at)->format(' %d %B  %Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{$factors->withQueryString()->links('vendor.pagination.bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>
@endsection
