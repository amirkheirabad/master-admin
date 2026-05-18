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
    <script src="{{ asset('/js/ticket-insert.js') }}"></script>
    <style>
        #fileList ul {
            list-style: none;
            padding: 0;
            margin: 10px 0;
        }

        #fileList li {
            background: #f5f5f5;
            padding: 8px 12px;
            margin: 5px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .remove-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 4px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .remove-btn:hover {
            background: #c82333;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row ">
                    <div class="col-md-6">
                        <h3>تیکت جدید</h3>
                    </div>
                </div>
                <form id="ticketForm" method="post" action="" class="x_panel rounded-4">
                    @csrf

                    <div class="form-group mt-8">
                        <div class="row">

                            <div class="col-md-6 col-xs-12 mb-3">
                                <label>نام فروشگاه</label>
                                <select id="store_id" class="form-control custom-radius custom-select-input input-border-focus">
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}"> {{ $store->store_name }} </option>
                                    @endforeach
                                </select>
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="store_id"></span>
                                </div>
                            </div>


                            <div class="col-md-6 col-xs-12 mb-3">
                                <label>تیم ارسال کننده </label>
                                <select id="contact_name" class="form-control custom-radius custom-select-input input-border-focus">
                                    <option value="0">درخواست ماژول با فیچر جدید</option>
                                    <option value="1">تیم فنی و عملیات</option>
                                    <option value="2">تیم پشتیبانی و سفارشات</option>
                                    <option value="3">گزارش خطا</option>
                                </select>
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="contact_name_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 mb-3">
                                <label>عنوان تیکت</label>
                                <input type="text" name="national_kod" id="title" class="form-control custom-radius input-border-focus" placeholder="عنوان تیکت">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="title_error"></span>
                                </div>
                            </div>

                            <div class="col-md-12 col-xs-12 mb-3">
                                <label class="mt-2">متن تیکت</label>
                                <textarea id="description" name="description" rows="5" class="form-control custom-radius input-border-focus" placeholder="متن خود را وارد کنید"></textarea>
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="message_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <p>پیوست فایل ها (اختیاری)</p>
                                <button type="button" id="attachButton" class="btn btn-beta-outline mt-2 ml-1">افزودن فایل</button>
                                <span>پسوند های مجاز : png / jpg / pdf</span>

                                <div id="fileList"></div>

                                <input type="file" id="fileInput" style="display: none;" multiple>
                                <div class="mt-2">
                                    <span class="text-danger error-message" id="attachments_error"></span>
                                </div>

                            </div>


                            <div class="d-flex justify-content-end col-md-12 mt-8">
                                <button type="submit" class="btn btn-beta-solid ">تایید</button>
                                <button type="submit" class="btn btn-beta-outline ">انصراف</button>
                            </div>

                        </div>


                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection
