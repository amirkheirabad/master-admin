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


                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>نام فروشگاه</label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="store_name" name="store_name">
                                <div class="mt-1">
                                    <span class="text-danger" id="store_name_error"></span>
                                </div>
                            </div>


                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>نام مدیر</label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="manager_name" name="manager_name">
                                <div class="mt-1">
                                    <span class="text-danger" id="manager_name_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>آدرس وبسایت</label>
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
                                <label>شماره تماس</label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="phone" name="phone">
                                <div class="mt-1">
                                    <span class="text-danger" id="phone_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>استان</label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="province" name="province">
                                <div class="mt-1">
                                    <span class="text-danger" id="province_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>شهر</label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="city" name="city">
                                <div class="mt-1">
                                    <span class="text-danger" id="city_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>آدرس فروشگاه</label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="location" name="location">
                                <div class="mt-1">
                                    <span class="text-danger" id="location_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>کد پستی</label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="code_posty" name="code_posty">
                                <div class="mt-1">
                                    <span class="text-danger" id="code_posty_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label> عرض جغرافیایی (latitude)</label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="latitude" name="latitude">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="latitude_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>طول جغرافیایی (longitude)</label>
                                <input type="text" class="form-control custom-radius input-border-focus" id="longitude" name="longitude">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="longitude_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>توکن فروشگاه</label>
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
@endsection
