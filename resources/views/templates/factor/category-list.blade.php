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
    <script src="{{ asset('/js/category.js') }}"></script>
@endsection


@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                        <div>
                            <h3>دسته بندی</h3>
                        </div>
                        <div class="mt-2">
                            <a href="#" class="btn btn-beta-solid" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-plus ml-1"></i>
                                افزودن دسته بندی
                            </a>
                        </div>
                    </div>
                </div>
{{--                <h5>تعداد کل رکورد ها: {{ count($categories) }}</h5>--}}
                <div class="x_panel rounded-top mt-2 p-0">
                    <table class="table">
                        <thead class="responsive-table-head">
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>وضعیت نمایش</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr class="item-record{{$category->id}}">
                                <th scope="row">#</th>
                                <td>{{ $category->name }}</td>
                                <td>
                                    @if($category->active == 0)
                                        <span class="bg-red p-2 custom-radius">
                                            غیرفعال
                                        </span>
                                    @elseif($category->active == 1)
                                        <span class="bg-jade p-2 custom-radius">
                                            فعال
                                        </span>
                                    @endif
                                </td>
                                <td class="">
                                    <a href="#" class="text-success editCategoryBtn" data-toggle="modal" data-target="#myModal2" data-id="{{ $category->id }}">
                                        <i class="fa fa-pencil fa-x"></i>
                                    </a>
                                    <a href="#" class="text-danger delete-category mr-1" data-id="{{ $category->id }}">
                                        <i class="fa fa-trash fa-x"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    @include('templates.factor.Modal.category-modal')
@endsection
