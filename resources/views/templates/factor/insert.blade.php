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
    <script src="{{ asset('/js/create-factor.js') }}"></script>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <h3> افزودن فاکتور</h3>
                        </div>
                    </div>
                </div>
                <div class="d-flex mt-5 x_panel rounded-4">
                    <form id="createFactor" method="POST" action="">
                        <div class="form-group mb-8">
                            <div class="row">



                                <div class="col-md-4 col-xs-12 mt-4">
                                    <label>انتخاب تاریخ</label>
                                    <div class="input-wrapper has-icon">
                                        <input type="text" class="form-control custom-radius input-border-focus" id="factor_date" name="factor_date" data-jdp placeholder="انتخاب تاریخ">
                                        <div class="icon-box" onclick="document.getElementById('factor_date').focus()">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="factor_date_error"></span>
                                    </div>
                                </div>


                                <div class="col-md-4 col-xs-12 mt-4">
                                    <label>نوع طرف حساب</label>
                                    <select id="account_type" class="form-control custom-radius custom-select-input input-border-focus">
                                        <option value="store">فروشگاه</option>
                                        <option value="agency">مشتری</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-xs-12 mt-4 customer-field" style="display:none;">
                                    <label>انتخاب مشتری (اختیاری)</label>
                                    <select id="customer_id" class="form-control custom-radius select2" style="height: 40px">
                                        <option value="">انتخاب مشتری</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" data-name="{{ $customer->name }}" data-phone="{{ $customer->mobile }}">
                                                {{ $customer->name }} - {{ $customer->mobile }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="customer_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-4 col-xs-12 mt-4">
                                    <label>فروشگاه</label> <span class="text-danger">*</span>
                                    <select id="store_id" class="form-control custom-radius select2" name="store_id" style="height: 40px">
                                        <option value="">انتخاب فروشگاه</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="store_id_error" style="position: relative; display: block;"></span>
                                    </div>
                                </div>

                                <div class="col-md-4 col-xs-12 mt-4 agency-field" style="display:none;">
                                    <label>نام و نام خانوادگی</label> <span class="text-danger">*</span>
                                    <input type="text" class="form-control custom-radius input-border-focus" name="name" id="name" placeholder="نام و نام خانوادگی">
                                    <div class="mt-1">
                                        <span class="text-danger" id="name_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-4 col-xs-12 mt-4 agency-field" style="display:none;">
                                    <label>کد ملی</label>
                                    <input type="text" name="national_kod" id="national_kod" class="form-control custom-radius input-border-focus" placeholder="کد ملی">
                                </div>

                                <div class="col-md-4 col-xs-12 mt-4 agency-field" style="display:none;">
                                    <label>شماره تماس</label> <span class="text-danger">*</span>
                                    <input type="text" name="phone" id="phone" class="form-control custom-radius input-border-focus" placeholder="شماره تماس">
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="phone_error"></span>
                                    </div>
                                </div>


                                <div class="col-md-4 col-xs-12 mt-4">
                                    <label>دسته بندی</label> <span class="text-danger">*</span>
                                    <select id="category_id" name="category_id" class="form-control custom-radius select2">
                                        <option value="">همه</option>
                                        @foreach($categories as $category)
                                         @if($category->active == 1)
                                            <option value="{{ $category->id }}"> {{ $category->name }} </option>
                                         @endif
                                        @endforeach
                                    </select>
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="category_id_error"></span>
                                    </div>
                                </div>


                                <div class="col-md-4 col-xs-12 mt-4">
                                    <label class="mt-">قیمت</label> <span class="text-danger">*</span>
                                    <input type="text" name="price" id="price" class="form-control custom-radius input-border-focus" placeholder="قیمت">
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="price_error"></span>
                                        <div class=" text-left display-price" id="display_price_toman"></div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-xs-12 mt-4">
                                    <label>وضعیت نمایش</label>
                                    <select class="form-control custom-radius custom-select-input" name="show_status" id="show_status">
                                        <option value="1">فعال</option>
                                        <option value="0">غیر فعال</option>
                                    </select>
                                </div>

                                <div class="col-md-12 col-xs-12 mt-4">
                                    <label>توضیحات</label>
                                    <textarea id="description" name="description" rows="5" class="form-control custom-radius input-border-focus"></textarea>
                                </div>
                                <div class="col-md-12 col-xs-12 mt-4">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            id="send_sms"
                                            name="send_sms"
                                            value="1"
                                        >
                                        <label class="form-check-label me-2" for="send_sms">
                                            ارسال پیامک اطلاع‌رسانی به طرف حساب
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-8">
                                    <button type="submit" class="btn btn-beta-solid w-100">ذخیره</button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
