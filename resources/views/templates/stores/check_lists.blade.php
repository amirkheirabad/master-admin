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
    <script src="{{ asset('/js/check-list.js') }}"></script>
@endsection


@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                        <div>
                            <h3>چک لیست ها</h3>
                        </div>
                        <div class="mt-2">
                            <a href="#" class="btn btn-beta-solid" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-plus ml-1"></i>
                                افزودن چک لیست
                            </a>
                        </div>
                    </div>
                </div>
                {{--                <h5>تعداد کل رکورد ها: {{ count($categories) }}</h5>--}}
                <div class="x_panel rounded-top mt-2 p-0">
                    <table class="table">
                        <thead class="responsive-table-head">
                        <tr>
                            <th>همه</th>
                            <th>نام چک لیست</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($checkLists as $checkList)
                            <tr class="item-record{{$checkList->id}}">
                                <th scope="row">{{ $checkList->id }}</th>
                                <td>{{ $checkList->title }}</td>
                                <td class="">
                                    <a href="#" class="text-success editCategoryBtn" data-toggle="modal" data-target="#myModal2" data-id="{{ $checkList->id }}">
                                        <i class="fa fa-pencil text-beta fa-x"></i>
                                    </a>
                                    <a href="#" class="text-danger delete-chek_list mr-1" data-id="{{ $checkList->id }}">
                                        <i class="fa fa-trash fa-x"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{$checkLists->withQueryString()->links('vendor.pagination.bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>
    @include('templates.stores.Modal.check_list-madal')
@endsection
