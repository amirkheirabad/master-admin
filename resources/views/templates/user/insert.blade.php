@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/select2.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/insert-user.js') }}"></script>
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
                        <h3>افزودن کاربر</h3>
                    </div>
                </div>

                <form id="userForm" method="post" action="{{ route('user-create') }}" class=" rounded-4">
                    @csrf

                    <div class="form-group mt-8">
                        <div class="row">

                            <div class="col-md-6 col-xs-12 mb-3">
                                <label class="fw-bold">نام و نام خانوادگی<span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control custom-radius input-border-focus" style="background: #f9fafb;">
                                <div class="mt-1 mb-3">
                                    <span class="text-danger error-message " id="name_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 mb-3">
                                <label class="fw-bold">شماره تماس<span class="text-danger">*</span></label>
                                <input type="text" name="mobile" id="mobile"
                                    class="form-control custom-radius input-border-focus" style="background: #f9fafb;">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="mobile_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 mb-3">
                                <label class="fw-bold">رمز عبور<span class="text-danger">*</span></label>
                                <div class="search-container">
                                    <input type="password" name="password" id="password"
                                           class="search-input">
                                    <button type="button" id="togglePassword" class="search-button">
                                        <i class="fa fa-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                <div class="mt-1">
                                    <span class="text-danger error-message mb-4" id="password_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 mb-3">
                                <label class="fw-bold">نقش ها</label>
                                <select name="role" id="role"
                                    class="form-control custom-radius custom-select-input input-border-focus"
                                    style="background: #f9fafb;">
                                    @foreach ($roles as $role)
                                        <option {{ old('role', 'seller') == $role->name ? 'selected' : '' }}
                                            value="{{ $role->name }}">
                                            {{ __($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="role_error"></span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end col-md-12 mt-8">
                                <button type="submit" class="btn btn-beta-solid">تایید</button>
                                <a href="{{ route('user-list') }}" class="btn btn-beta-outline">انصراف</a>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
