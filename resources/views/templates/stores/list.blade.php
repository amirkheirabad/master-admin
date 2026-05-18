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
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row ">
                    <div class="col-md-6">
                        <h3>لیست فروشگاه ها</h3>
                    </div>
                </div>
                <a href="{{ route('insert_store') }}" class="btn btn-beta-solid mt-2">اضافه کردن فروشگاه</a>
                <div class="x_panel rounded-top mt-2 p-0">
                    <table class="table" style="margin-bottom: 0px">
                        <thead class="responsive-table-head">
                            <tr>
                                <th>#</th>
                                <th>نام فروشگاه</th>
                                <th>نام مدیر</th>
                                <th>آدرس وبسایت</th>
                                <th>شعار فروشگاه</th>
                                <th>شماره تماس</th>
                                <th>استان</th>
                                <th>شهر</th>
                                <th>آدرس فروشگاه</th>
                                <th>کد پستی</th>
                                <th>عرض جغرافیایی</th>
                                <th>طول جغرافیایی</th>
                                <th>درباره فروشگاه</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($stores as $store)
                            <tr class="responsive-table-row item-record{{$store->id}}">
                                <th scope="row" class="responsive-table-td">{{ $loop->iteration }}</th>
                                <td data-title="نام فروشگاه" class="responsive-table-td">{{ $store->store_name }}</td>
                                <td data-title="نام مدیر" class="responsive-table-td">{{ $store->manager_name }}</td>
                                <td data-title="آدرس وبسایت" class="responsive-table-td">{{ $store->link }}</td>
                                <td data-title="شعار فروشگاه" class="responsive-table-td">{{ $store->slogan }}</td>
                                <td data-title="شماره تماس" class="responsive-table-td">{{ $store->phone }}</td>
                                <td data-title="استان" class="responsive-table-td">{{ $store->province }}</td>
                                <td data-title="شهر" class="responsive-table-td">{{ $store->city }}</td>
                                <td data-title="آدرس فروشگاه" class="responsive-table-td">{{ $store->location }}</td>
                                <td data-title="کد پستی" class="responsive-table-td">{{ $store->code_posty }}</td>
                                <td data-title="عرض جغرافیایی" class="responsive-table-td">{{ $store->latitude }}</td>
                                <td data-title="طول جغرافیایی" class="responsive-table-td">{{ $store->longitude }}</td>
                                <td data-title="درباره فروشگاه" class="responsive-table-td">{{ Str::limit($store->about, 50) }}</td>
                                <td data-title="عملیات" class="responsive-table-td">
                                    <div class="action-buttons" style="display: flex; gap: 10px;">
                                        <a href="{{ route('edit_store', $store->id) }}" class="text-success" title="ویرایش">
                                            <i class="fa fa-pencil fa-x"></i>
                                        </a>
                                        <a href="javascript:;" class="text-danger delete-message" data-id="{{ $store->id }}" title="حذف">
                                            <i class="fa fa-trash fa-x"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
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
