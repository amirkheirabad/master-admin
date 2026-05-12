@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
{{--    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">--}}
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
                    <table class="table">
                        <thead>
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
                            <th>طول جفرافیایی</th>
                            <th>درباره فروشگاه</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($stores as $store)
                            <tr class="item-record{{$store->id}}">
                                <th scope="row">#</th>
                                <td>{{ $store->store_name }}</td>
                                <td>{{ $store->manager_name }}</td>
                                <td>{{ $store->link }}</td>
                                <td>{{ $store->slogan }}</td>
                                <td>{{ $store->phone }}</td>
                                <td>{{ $store->province }}</td>
                                <td>{{ $store->city }}</td>
                                <td>{{ $store->location }}</td>
                                <td>{{ $store->code_posty }}</td>
                                <td>{{ $store->latitude }}</td>
                                <td>{{ $store->longitude }}</td>
                                <td>{{ $store->about }}</td>
                                <td class="">
                                    <a href="{{ route('edit_store', $store->id) }}" class="text-success">
                                        <i class="fa fa-pencil fa-x"></i>
                                    </a>
                                    <a href="javascript:;" class="text-danger delete-message mr-1" data-id="{{ $store->id }}">
                                        <i class="fa fa-trash fa-x"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{$stores->withQueryString()->links('vendor.pagination.bootstrap-5')}}
                </div>
        </div>
    </div>
@endsection
