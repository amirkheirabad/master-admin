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
                            <h3>گزارشات پنل پیامکی</h3>
                        </div>
                    </div>
                </div>
                <div class="x_panel rounded-top mt-2 p-0">
                    <table class="table">
                        <thead class="">
                        <tr>
                            <th> شماره پنل پیامکی</th>
                            <th>نام کاربر سایت</th>
                            <th>عملیات</th>
                            <th>توضیحات</th>
                            <th>تاریخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($smsPanels as $smsPanel)
                            <tr class="item-record{{$smsPanel->id}}">
                                <th scope="row">{{$smsPanel->subject_id}}</th>
                                <td>{{$smsPanel->user->name}}</td>
                                <td class="">
                                    @if($smsPanel->event == 'updated')
                                        <span class="bg-new p-2 custom-radius">
                                            ویرایش
                                        </span>
                                    @endif
                                    @if($smsPanel->event == 'created')
                                        <span class="bg-jade p-2 custom-radius">
                                            ساخت
                                        </span>
                                    @endif
                                    @if($smsPanel->event == 'deleted')
                                        <span class="bg-red-new p-2 custom-radius">
                                            حذف
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $smsPanel->description }}</td>
                                <td>{{ Verta($smsPanel->created_at)->format(' %d %B  %Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{$smsPanels->withQueryString()->links('vendor.pagination.bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>
@endsection
