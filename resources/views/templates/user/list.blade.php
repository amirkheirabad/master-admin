@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
    {{--
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">--}}
@endsection

@section('js')
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <script src="{{ asset('/js/app-user.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row ">
                    <div class="col-md-12 d-flex justify-content-start">
                        <div>
                            <h3>لیست کاربران</h3>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-2">
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
                                        <select name="role_id" class="form-control custom-radius custom-select-input"
                                            data-placeholder=" نقش">
                                            <option value=""> نقش</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" @selected(request('role_id') == $role->id)>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
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
                        <thead class="responsive-table-head">
                            <tr class="responsive-table-row">
                                <th>#</th>
                                <th>نام و نام خانوادگی</th>
                                <th>شماره همراه</th>
                                <th>نقش</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr class="responsive-table-row item-record{{$user->id}}">
                                    <th scope="row" class="responsive-table-td">
                                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                    </th>
                                    <td data-title="نام و نام خانوادگی" class="responsive-table-td">{{ $user->name }}</td>
                                    <td data-title="شماره همراه" class="responsive-table-td">{{ $user->mobile }}</td>
                                    <td data-title="نقش" class="responsive-table-td">
                                        @php
                                            $roleColors = [
                                                'admin'   => 'bg-new',
                                                'seller'  => 'bg-jade',
                                            ];
                                        @endphp
                                        @foreach($user->getRoleNames() as $roleName)
                                            @php
                                                $colorClass = $roleColors[$roleName] ?? 'bg-gray-new';
                                            @endphp
                                            <span class=" {{ $colorClass }} p-2 custom-radius" >
                                                {{ $roleName }}
                                            </span>
                                        @endforeach
                                    </td class="responsive-table-td">
                                    <td data-title="عملیات" class="responsive-table-td">
                                        <div class="">
                                            <a href="{{ route('user-edit', $user->id) }}" class="text-success"
                                                data-id="{{ $user->id }}">
                                                <i class="fa fa-pencil fa-x"></i>
                                            </a>
                                            <a href="javascript:;" class="text-danger delete-message mr-1"
                                                data-id="{{ $user->id }}">
                                                <i class="fa fa-trash fa-x"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fa fa-search fa-2x mb-2 d-block"></i>
                                        نتیجه‌ای برای جستجو یافت نشد
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{$users->withQueryString()->links('vendor.pagination.bootstrap-5')}}
                </div>

                <div class="d-flex justify-content-center">
                </div>
            </div>
        </div>
@endsection