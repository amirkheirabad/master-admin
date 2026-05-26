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
    <script src="{{ asset('/js/video-list.js') }}"></script>
@endsection


@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                        <div>
                            <h3>لیست ویدئو ها</h3>
                        </div>
                    </div>
                </div>
                <div class="x_panel rounded-top mt-2 p-0">
                    <table class="table">
                        <thead class="responsive-table-head">
                        <tr>
                            <th>#</th>
                            <th>عنوان</th>
                            <th>دسته بندی</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($videos as $video)
                            <tr class="item-record{{$video->id}}">
                                <th scope="row">#</th>
                                <td>{{ $video->title }}</td>
                                <td>{{ $video->category->name ?? 'بدون دسته بندی' }}</td>
                                <td class="">
                                    <a href="{{ route('video-edit', $video->id) }}" class="text-success editCategoryBtn" data-id="{{ $video->id }}">
                                        <i class="fa fa-pencil fa-x"></i>
                                    </a>
                                    <a href="#" class="text-danger delete-message mr-1" data-id="{{ $video->id }}">
                                        <i class="fa fa-trash fa-x"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{$videos->withQueryString()->links('vendor.pagination.bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>
@endsection
