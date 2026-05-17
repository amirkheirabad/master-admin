@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script src="{{ asset('/js/edit-user.js') }}"></script>
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
                        <h3>ویرایش کاربر</h3>
                    </div>
                </div>

                <form id="editUserForm" method="post" action="{{ route('user-update', $user->id) }}" class="x_panel rounded-4">
                    @csrf
                    @method('PUT')
                        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                    <div class="form-group mt-8">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>نام و نام خانوادگی</label>
                                <input type="text" value="{{ $user->name }}" name="name" id="name" class="form-control custom-radius input-border-focus">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="name_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>شماره تماس</label>
                                <input type="text" value="{{ $user->mobile }}" name="mobile" id="mobile" class="form-control custom-radius input-border-focus">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="mobile_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>رمز عبور جدید</label>
                                <input type="password" name="password" id="password" class="form-control custom-radius input-border-focus" placeholder="برای عدم تغییر، خالی بگذارید">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="password_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>نقش ها</label>
                                <select id="role" name="role" class="form-control custom-radius custom-select-input input-border-focus">
                                    @foreach($roles as $role)
                                        <option value="{{$role->name}}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ __($role->name) }}</option>
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