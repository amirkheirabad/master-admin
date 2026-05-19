@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <script src="{{ asset('/js/app-stores.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row x_title">
                    <div class="col-md-6">
                        <h3>ویرایش فروشگاه {{ $store->store_name }}</h3>
                    </div>
                </div>
                <form data-id="{{ $store->id }}" id="editForm" method="post" action="#">
                    @csrf

                    <div class="form-group mb-8">
                        <div class="row">


                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>نام فروشگاه</label>
                                <input type="text" value="{{ $store->store_name }}" class="form-control custom-radius input-border-focus" id="store_name" name="store_name" placeholder="نام فروشگاه">
                                <div class="mt-1">
                                    <span class="text-danger" id="store_name_error"></span>
                                </div>
                            </div>


                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>نام مدیر</label>
                                <select id="user_id" class="form-control custom-radius select2" name="user_id">
                                    <option value="">همه</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $store->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <div class="mt-1">
                                    <span class="text-danger" id="user_id_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>آدرس وبسایت</label>
                                <input type="text" value="{{ $store->link }}" class="form-control custom-radius input-border-focus" id="link" name="link" placeholder="آدرس وبسایت">
                                <div class="mt-1">
                                    <span class="text-danger" id="link_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>شعار فروشگاه</label>
                                <input type="text" value="{{ $store->slogan }}" class="form-control custom-radius input-border-focus" id="slogan" name="slogan" placeholder="شعار فروشگاه">
                                <div class="mt-1">
                                    <span class="text-danger" id="slogan_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>شماره تماس</label>
                                <input type="text" value="{{ $store->phone }}" class="form-control custom-radius input-border-focus" id="phone" name="phone" placeholder="شماره تماس">
                                <div class="mt-1">
                                    <span class="text-danger" id="phone_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>استان</label>
                                <input type="text" value="{{ $store->province }}" class="form-control custom-radius input-border-focus" id="province" name="province" placeholder="استان">
                                <div class="mt-1">
                                    <span class="text-danger" id="province_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>شهر</label>
                                <input type="text" value="{{ $store->city }}" class="form-control custom-radius input-border-focus" id="city" name="city" placeholder="شهر">
                                <div class="mt-1">
                                    <span class="text-danger" id="city_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>آدرس فروشگاه</label>
                                <input type="text" value="{{ $store->location }}" class="form-control custom-radius input-border-focus" id="location" name="location" placeholder="آدرس فروشگاه">
                                <div class="mt-1">
                                    <span class="text-danger" id="location_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>کد پستی</label>
                                <input type="text" value="{{ $store->code_posty }}" class="form-control custom-radius input-border-focus" id="code_posty" name="code_posty" placeholder="کد پستی">
                                <div class="mt-1">
                                    <span class="text-danger" id="code_posty_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label> عرض جغرافیایی (latitude)</label>
                                <input type="text" value="{{ $store->latitude }}" class="form-control custom-radius input-border-focus" id="latitude" name="latitude" placeholder="عرض جغرافیایی">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="latitude_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>طول جغرافیایی (longitude)</label>
                                <input type="text" value="{{ $store->longitude }}" class="form-control custom-radius input-border-focus" id="longitude" name="longitude" placeholder="طول جغرافیایی">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="longitude_error"></span>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                <label>توکن فروشگاه</label>
                                <div class="search-container">
                                    <input type="text" value="{{ $store->token }}" name="token" id="token" class="search-input" placeholder="توکن فروشگاه">
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
                                <textarea id="about" name="about" rows="5" class="form-control custom-radius input-border-focus">{{ $store->about }}</textarea>
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
