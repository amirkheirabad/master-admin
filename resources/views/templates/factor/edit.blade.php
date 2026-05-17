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
    <script src="{{ asset('/js/edit-factor.js') }}"></script>
    <script>
        const imagePreview = document.getElementById('imagePreview');
        const fileInput = document.querySelector('.file-input');

        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(event) {
                        console.log("FileReader success! Result is:", event.target.result);
                        imagePreview.src = event.target.result;
                        imagePreview.style.display = 'block';
                    }

                    reader.readAsDataURL(file);

                } else {
                    alert('لطفاً فقط فایل‌های تصویری را انتخاب کنید.');
                    fileInput.value = '';
                    imagePreview.style.display = 'none';
                }
            } else {
                imagePreview.style.display = 'none';
            }
        });

        document.querySelector('.custom-file-button').addEventListener('click', function(e) {
            e.preventDefault();
            fileInput.click();
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');
            const displayPriceToman = document.getElementById('display_price_toman');

            function formatPrice(price) {
                if (isNaN(price)) {
                    return '0';
                }
                return parseFloat(price).toLocaleString('en');
            }

            function updatePriceDisplay() {
                const priceValue = priceInput.value;
                const cleanedPrice = priceValue.replace(/,/g, '').replace(/ تومان/g, '');

                if (cleanedPrice === '' || isNaN(cleanedPrice)) {
                    displayPriceToman.textContent = '0 تومان';
                    return;
                }
                displayPriceToman.textContent = formatPrice(cleanedPrice/10) + ' تومان';

            }

            priceInput.addEventListener('input', updatePriceDisplay);

            updatePriceDisplay();
        });
    </script>


@endsection


@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row ">
                    <div class="col-md-12">
                        <div>
                            <h3> ویرایش فاکتور<span class="text-danger"> {{ $factor->id }}</span></h3>
                            <h4>{{ $factor->hash }}</h4>
                        </div>
                    </div>
                </div>


                <div class="d-flex mt-5 x_panel rounded-4">
                    <form id="editFactor" class="" enctype="multipart/form-data" method="POST" action="{{ route('factor-update', $factor->id) }}">
                        @csrf
                        <input type="hidden" id="factor_id" value="{{ $factor->id }}">
                        <div class="form-group mb-8">
                            <div class="row">

                                <div class="col-md-4 mt-4 col-xs-12">
                                    <label>انتخاب تاریخ</label>
                                    <div class="input-wrapper has-icon">
                                        <input value="{{ Verta($factor->factor_date)->format('Y/m/d') }}" type="text" class="form-control custom-radius" id="factor_date" name="factor_date" data-jdp placeholder="انتخاب تاریخ">
                                        <div class="icon-box" onclick="document.getElementById('factor_date').focus()">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                    <div class="mt-1 error-space">
                                        <span class="text-danger error-message" id="factor_date_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-4 mt-4 col-xs-12">
                                    <label>تغییر وضعیت پرداخت</label>
                                    <select class="form-control custom-radius custom-select-input" name="price_status" id="price_status">
                                        <option value="0" {{ $factor->price_status == '0' ? 'selected' : '' }}>در حال پرداخت</option>
                                        <option value="1" {{ $factor->price_status == '1' ? 'selected' : '' }}>پرداخت نشده</option>
                                        <option value="2" {{ $factor->price_status == '2' ? 'selected' : '' }}>پرداخت شده</option>
                                        <option value="3" {{ $factor->price_status == '3' ? 'selected' : '' }}>کارت به کارت</option>
                                        <option value="4" {{ $factor->price_status == '4' ? 'selected' : '' }}>معلق شده</option>
                                    </select>
                                </div>


                                <div class="col-md-4 mt-4 col-xs-12" style="display: none">
                                    <label>انتخاب تاریخ پرداخت</label> <span class="text-danger">*</span>
                                    <div class="input-wrapper has-icon">
                                        <input type="text" value="{{ $factor->paid_factor_date ? Verta($factor->paid_factor_date)->format('Y/m/d') : '' }}" class="form-control custom-radius" id="paid_factor_date" name="paid_factor_date" data-jdp placeholder="انتخاب تاریخ پرداخت">
                                        <div class="icon-box" onclick="document.getElementById('paid_factor_date').focus()">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                    <div class="error-space mt-1">
                                        <span class="text-danger" id="paid_factor_date_error"></span>  <!-- تغییر این خط -->
                                    </div>
                                </div>

                                <div class="col-md-4 mt-4 col-xs-12">
                                    <label>دسته بندی</label>
                                    <select id="category_id" name="category_id" class="form-control custom-radius select2">
                                        <option value="">همه</option>
                                        @foreach($categories as $category)
                                         @if($category->active == 1)
                                            <option value="{{ $category->id }}"
                                                {{ $factor->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                         @endif
                                        @endforeach
                                    </select>
                                    <div class="mt-1 error-space">
                                        <span class="text-danger" id="category_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-4 mt-4 col-xs-12">
                                    <label>وضعیت نمایش</label>
                                    <select class="form-control custom-radius custom-select-input" name="show_status" id="show_status">
                                        <option value="1">فعال</option>
                                        <option value="0">غیر فعال</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mt-4 col-xs-12">
                                    <label class="mt-">قیمت</label>
                                    <input type="text"  value="{{ $factor->price }}" name="price" id="price" class="form-control custom-radius price-input-class" placeholder="قیمت">
                                    <div class="mt-1 error-space">
                                        <span class="d-block text-left display-price" id="display_price_toman">{{ $factor->price/10 }} تومان</span>
                                        <span class="text-danger" id="price_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <label>توضیحات</label>
                                    <textarea name="description" id="description" rows="4" class="form-control custom-radius">{{ $factor->description }}</textarea>
                                </div>

                                @if(!empty($factor->image))
                                    <img id="imagePreview" class="col-md-10 mr-2 mt-2" src="{{ asset('storage/' . $factor->image) }}" alt="پیش‌نمایش عکس" style="max-width: 200px; max-height: 200px; margin-bottom: 10px;" />
                                @else
                                    <img id="imagePreview" class="col-md-10 mr-2 mt-2" src="#" alt="پیش‌نمایش عکس" style="display: none; max-width: 200px; max-height: 200px; margin-bottom: 10px;" />
                                @endif

                                <div class="file-input-wrapper col-md-10 mt-2">
                                    <button class="btn btn-beta-outline custom-file-button">انتخاب عکس</button>
                                    <input type="file" id="imageInput" class="file-input" accept="image/*">
                                </div>

                                <!-- (اختیاری) اگر دکمه حذف هم دارید -->
                                <!-- <button id="removeImageButton" style="display: none;">حذف عکس</button> -->



                                <div class="col-md-3 mt-2">
                                    <button type="submit" class="btn btn-beta-solid">ذخیره تغییرات</button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


<style>
    .file-input-wrapper {
        position: relative;
        display: inline-block;
        overflow: hidden;
    }

    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 30px;        /* تغییر: به جای 100% */
        height: 30px;       /* تغییر: به جای 100% */
        opacity: 0;
        cursor: pointer;
        font-size: 300px;
        filter: alpha(opacity=0);
    }

    #imagePreview {
        max-width: 200px;
        max-height: 200px;
        display: block;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        padding: 5px;
        box-sizing: border-box;
    }
</style>

