@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <script src="{{ asset('/js/generate-token.js') }}"></script>
    <script src="{{ asset('/js/app-stores.js') }}"></script>
    <script src="{{ asset('/js/quick_create_seller.js') }}"></script>
    <script>
    var quickCreateSellerConfig = {
        route: '{{ route("quick_create_seller") }}',
        token: '{{ csrf_token() }}'
    };
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row ">
                    <div class="col-md-6">
                        <h3>اضافه کردن فروشگاه</h3>
                    </div>
                </div>
                <form id="storeForm" method="post" action="">
                    @csrf

                    <div class="form-group mb-8">
                        <div class="row">


                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4 agency-field">
                                <label>نام فروشگاه <span class="text-danger">*</span></label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="store_name" name="store_name">
                                <div class="mt-1">
                                    <span class="text-danger" id="store_name_error"></span>
                                </div>
                            </div>


                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>نام مدیر <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center gap-2">
                                    <select id="user_id" class="form-control custom-radius select2" name="user_id">
                                        <option value="">همه</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-beta-solidd btn-sm text-nowrap" id="btn-quick-create-user" title="ساخت کاربر جدید">
                                        + کاربر جدید
                                    </button>
                                </div>
                                <div class="mt-1">
                                    <span class="text-danger" id="user_id_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>آدرس وبسایت<span class="text-danger">*</span></label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="link" name="link">
                                <div class="mt-1">
                                    <span class="text-danger" id="link_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>شعار فروشگاه</label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="slogan" name="slogan">
                                <div class="mt-1">
                                    <span class="text-danger" id="slogan_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>شماره تماس<span class="text-danger">*</span></label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="phone" name="phone">
                                <div class="mt-1">
                                    <span class="text-danger" id="phone_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>استان<span class="text-danger">*</span></label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="province" name="province">
                                <div class="mt-1">
                                    <span class="text-danger" id="province_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>شهر<span class="text-danger">*</span></label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="city" name="city">
                                <div class="mt-1">
                                    <span class="text-danger" id="city_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>آدرس فروشگاه <span class="text-danger">*</span></label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="location" name="location">
                                <div class="mt-1">
                                    <span class="text-danger" id="location_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>کد پستی <span class="text-danger">*</span></label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="code_posty" name="code_posty">
                                <div class="mt-1">
                                    <span class="text-danger" id="code_posty_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>توکن فروشگاه <span class="text-danger">*</span></label>
                                <div class="search-container">
                                    <input type="text" name="token" id="token" value="" class="search-input">
                                    <button type="button" class="btn btn-beta-solid" id="btn-generate-token">
                                        تولید توکن
                                    </button>
                                </div>
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="token_error"></span>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 mt-4">
                                <label>درباره فروشگاه</label>
                                <textarea id="about" name="about" rows="5" class="form-control custom-radius input-border-focus" placeholder="لطفا توضیحات خود را وارد کنید..."></textarea>
                            </div>


                            <div class="col-md-6 mt-4">
                                <button type="button" id="attachButton" class="btn btn-beta-outline mb-2 mt-2 ml-1">افزودن
                                    لوگو</button>
                                <br>

                                <div id="fileList"></div>

                                <input type="file" id="fileInput" style="display: none;" multiple>
                                <div class="mt-2">
                                    <span class="text-danger error-message" id="file_path_error"></span>
                                </div>

                            </div>

                        </div>
                        <div class="d-flex justify-content-center mt-8">
                            <button type="submit" class="btn btn-beta-solid">تایید تغییرات</button>
                            <a href="{{ route('list_stores') }}" class="btn btn-beta-outline">انصراف</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- مودال ساخت سریع کاربر --}}
    <div class="modal fade" id="quickCreateUserModal" tabindex="-1" role="dialog" aria-labelledby="quickCreateUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickCreateUserModalLabel">ساخت کاربر جدید (فروشنده)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="بستن">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>نام <span class="text-danger">*</span></label>
                        <input type="text" class="form-control custom-radius input-border-focus" id="quick_name" placeholder="نام کاربر">
                        <span class="text-danger mt-1 d-block" id="quick_name_error"></span>
                    </div>
                    <div class="form-group mt-3">
                        <label>شماره موبایل <span class="text-danger">*</span></label>
                        <input type="text" class="form-control custom-radius input-border-focus" id="quick_mobile" placeholder="شماره موبایل">
                        <span class="text-danger mt-1 d-block" id="quick_mobile_error"></span>
                    </div>
                    <div class="form-group mt-3">
                        <label>رمز عبور <span class="text-danger">*</span></label>
                        <input type="password" class="form-control custom-radius input-border-focus" id="quick_password" placeholder="رمز عبور">
                        <span class="text-danger mt-1 d-block" id="quick_password_error"></span>
                    </div>
                    <div id="quick_general_error" class="alert alert-danger mt-3 d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-beta-outline" data-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-beta-solid" id="btn-submit-quick-user">
                        <span id="quick-user-btn-text">ساخت کاربر</span>
                        <span id="quick-user-spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
