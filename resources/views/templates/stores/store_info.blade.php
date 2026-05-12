@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/jalalidatepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/jalalidatepicker.min.js') }}"></script>
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <script src="{{ asset('/js/create-factor.js') }}"></script>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="">
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <h3>فروشگاه x</h3>
                        </div>
                    </div>
                </div>
                <div class="d-flex mt-5">
                    <form id="createFactor" method="POST" action="">
                        <div class="form-group mb-8">
                            <div class="row">


                                <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                    <label>نام فروشگاه</label>
                                    <input type="text" class="form-control custom-radius input-border-focus" id="store_name" name="store_name" placeholder="نام فروشگاه">
                                </div>


                                <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                    <label>نام مدیر</label>
                                    <input type="text" class="form-control custom-radius input-border-focus" id="manager_name" name="manager_name" placeholder="نام مدیر">
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                    <label>آدرس وبسایت</label>
                                    <input type="text" class="form-control custom-radius input-border-focus" id="link" name="link" placeholder="آدرس وبسایت">
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                    <label>شعار فروشگاه</label>
                                    <input type="text" class="form-control custom-radius input-border-focus" id="" name="" placeholder="شعار فروشگاه">
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                    <label>شماره تماس</label>
                                    <input type="text" class="form-control custom-radius input-border-focus" id="phone" name="phone" placeholder="شماره تماس">
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                    <label>استان</label>
                                    <input type="text" class="form-control custom-radius input-border-focus" id="phone" name="phone" placeholder="استان">
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                    <label>شهر</label>
                                    <input type="text" class="form-control custom-radius input-border-focus" id="city" name="city" placeholder="شهر">
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                    <label>آدرس فروشگاه</label>
                                    <input type="text" class="form-control custom-radius input-border-focus" id="location" name="location" placeholder="آدرس فروشگاه">
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                    <label>کد پستی</label>
                                    <input type="text" class="form-control custom-radius input-border-focus" id="city" name="city" placeholder="کد پستی">
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                    <label> عرض جغرافیایی (latitude)</label>
                                    <input type="text" class="form-control custom-radius input-border-focus" id="latitude" name="latitude" placeholder="عرض جغرافیایی">
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                    <label>طول جغرافیایی (longitude)</label>
                                    <input type="text" class="form-control custom-radius input-border-focus" id="longitude" name="longitude" placeholder="طول جغرافیایی">
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-12 mt-4">
                                    <label>توکن فروشگاه</label>
                                    <div class="search-container">
                                        <input type="text" name="token" id="token" value="" class="search-input" placeholder="توکن فروشگاه">
                                        <button type="button" class="search-button" id="btn-generate-token">
                                            تولید توکن
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mt-4">
                                    <label>درباره فروشگاه</label>
                                    <textarea id="description" name="description" rows="5" class="form-control custom-radius input-border-focus"></textarea>
                                </div>

                            </div>
                            <div class="d-flex justify-content-center mt-8">
                                <button type="submit" class="btn btn-beta-solid">تایید تغییرات</button>
                                <button type="submit" class="btn btn-beta-outline">انصراف</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
