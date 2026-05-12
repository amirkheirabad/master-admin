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
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row ">
                    <div class="col-md-6">
                        <h3>لیست کاربران</h3>
                    </div>
                </div>

                <div class="x_panel rounded-top mt-2 p-0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>نام و نام خانوادگی</th>
                            <th>نقش</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr class="item-record{{$user->id}}">
                                <th scope="row">#</th>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @foreach($user->getRoleNames() as $roleName)
                                        {{ __($roleName) }}
                                    @endforeach
                                </td>
                                <td data-title="عملیات" class="responsive-table-td">
                                    <div class="">
                                        <a href="{{ route('user-edit', $user->id) }}" class="text-success" data-id="{{ $user->id }}">
                                            <i class="fa fa-pencil fa-x"></i>
                                        </a>
                                        <a href="javascript:;" class="text-danger delete-factor" data-id="{{ $user->id }}">
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
                </div>
            </div>
        </div>
@endsection
