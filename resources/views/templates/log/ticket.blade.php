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
@endsection


@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                        <div>
                            <h3>گزارشات پیام های تیکت</h3>
                        </div>
                    </div>
                </div>
                <div class="x_panel rounded-top mt-2 p-0">
                    <table class="table">
                        <thead class="">
                        <tr>
                            <th> شماره تیکت مربوطه</th>
                            <th>نام کاربر سایت</th>
                            <th>عملیات</th>
                            <th>توضیحات</th>
                            <th>تاریخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr class="item-record{{$ticket->id}}">
                                <th scope="row">{{$ticket->ticket_id}}</th>
                                <td>{{$ticket->user->name}}</td>
                                <td class="">
                                    @if($ticket->event == 'updated')
                                        <span class="bg-new p-2 custom-radius">
                                            ویرایش
                                        </span>
                                    @endif
                                    @if($ticket->event == 'created')
                                        <span class="bg-jade p-2 custom-radius">
                                            ساخت
                                        </span>
                                    @endif
                                    @if($ticket->event == 'deleted')
                                        <span class="bg-red-new p-2 custom-radius">
                                            حذف
                                        </span>
                                    @endif
                                </td>
                                <td>پیامی از تیکت شماره {{ $ticket->ticket_id }} ویرایش شد.</td>
                                <td>{{ Verta($ticket->created_at)->format(' %d %B  %Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{$tickets->withQueryString()->links('vendor.pagination.bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>
@endsection
