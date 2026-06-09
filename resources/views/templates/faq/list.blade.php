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
    <script src="{{ asset('/js/faq-list.js') }}"></script>
@endsection


@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                        <div>
                            <h3>لیست سوالات</h3>
                        </div>
                    </div>
                </div>
                <div class="x_panel rounded-top mt-2 p-0">
                    <table class="table">
                        <thead class="responsive-table-head">
                        <tr>
                            <th>#</th>
                            <th>سوال</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($faqs as $faq)
                            <tr class="item-record{{$faq->id}}">
                                <th scope="row">#</th>
                                <td>{{ $faq->question }}</td>
                                <td class="">
                                    <a href="{{ route('faq_edit', $faq->id) }}" class="text-beta">
                                        <i class="fa fa-pencil fa-x"></i>
                                    </a>
                                    <a href="#" class="text-danger delete-message mr-1" data-id="{{ $faq->id }}">
                                        <i class="fa fa-trash fa-x"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{$faqs->withQueryString()->links('vendor.pagination.bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>
@endsection
