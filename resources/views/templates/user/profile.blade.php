@extends('layouts.admin.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script src="{{ asset('/js/profile.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-6">
                        <h3>پروفایل</h3>
                    </div>
                </div>

                <div class="x_panel rounded-4 mt-2">
                    <div class="row">
                        <div class="col-md-6 col-xs-12 mb-3">
                            <label>نام و نام خانوادگی</label>
                            <input type="text" value="{{ $user->name }}" class="form-control custom-radius" disabled>
                        </div>
                        <div class="col-md-6 col-xs-12 mb-3">
                            <label>شماره تماس</label>
                            <input type="text" value="{{ $user->mobile }}" class="form-control custom-radius" disabled>
                        </div>
                    </div>
                </div>

                <form id="profilePasswordForm" method="post" action="{{ route('profile.update-password') }}"
                    class="x_panel rounded-4 mt-2">
                    @csrf
                    @method('PUT')

                    <div class="form-group mt-2">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h4 style="margin: 0; font-size: 16px; color: #0e2d55;">تغییر رمز عبور</h4>
                            </div>

                            <div class="col-md-6 col-xs-12 mb-3">
                                <label>رمز عبور جدید<span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password"
                                    class="form-control custom-radius input-border-focus">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="password_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 mb-3">
                                <label>تکرار رمز عبور<span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control custom-radius input-border-focus">
                            </div>

                            <div class="d-flex justify-content-end col-md-12 mt-4">
                                <button type="submit" class="btn btn-beta-solid">ذخیره</button>
                                <a href="{{ route('dashboard') }}" class="btn btn-beta-outline mr-2">انصراف</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
