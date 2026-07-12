@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
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

        /* استایل کپچا */
        .captcha-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .captcha-question {
            background: #f4f6f9;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #2c3e50;
            min-width: 120px;
            text-align: center;
        }
        .captcha-input {
            width: 100px !important;
            text-align: center;
            border-radius: 8px !important;
        }
        .refresh-captcha-btn {
            background: none;
            border: 1px solid #d0d7de;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            color: #57606a;
            cursor: pointer;
            transition: 0.2s;
        }
        .refresh-captcha-btn:hover {
            background: #f0f2f4;
            border-color: #b9c1ca;
        }

        .file-item{
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .file-name{
            flex: 1;
            min-width: 0;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .remove-btn{
            flex-shrink: 0;
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
                @if(auth()->user()->hasRole('admin'))
                <form id="ticketForm" method="post" action="" class="x_panel rounded-4">
                    @csrf

                    <div class="form-group mt-8">
                        <div class="row">

                {{-- طرف حساب --}}
                <div class="col-md-6 col-xs-12 mb-3 mt-2">
                    <label>طرف حساب <span class="text-danger">*</span></label>
                    <select name="recipient_type" id="recipient_type"
                        class="form-control custom-radius custom-select-input input-border-focus">
                        <option value="store">فروشگاه</option>
                        <option value="user">کاربر</option>
                    </select>
                    <div class="mt-1">
                        <span class="text-danger error-message" id="recipient_type_error"></span>
                    </div>
                </div>

                {{-- فروشگاه --}}
                <div class="col-md-6 col-xs-12 mb-3 mt-2" id="store_wrapper">
                    <label>نام فروشگاه <span class="text-danger">*</span></label>
                    <select name="store_id" id="store_id"
                        class="form-control custom-radius select2">
                        <option value="">انتخاب کنید</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                        @endforeach
                    </select>
                    <div class="mt-1">
                        <span class="text-danger error-message" id="store_id_error"></span>
                    </div>
                </div>

                {{-- کاربر --}}
                <div class="col-md-6 col-xs-12 mb-3 mt-2" id="user_wrapper" style="display:none;">
                    <label>کاربر <span class="text-danger">*</span></label>
                    <select name="user_id" id="user_id"
                        class="form-control custom-radius select2">
                        <option value="">انتخاب کنید</option>
                        @foreach ($users as $user)
                         <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->mobile }}</option>
                        @endforeach
                    </select>
                    <div class="mt-1">
                        <span class="text-danger error-message" id="user_id_error"></span>
                    </div>
                </div>

                            <div class="col-md-6 col-xs-12 mt-2 mb-3">
                                <label>تیم ارسال کننده </label>
                                <select id="contact_name"
                                    class="form-control custom-radius custom-select-input input-border-focus">
                                    <option value="0">درخواست ماژول با فیچر جدید</option>
                                    <option value="1">تیم فنی و عملیات</option>
                                    <option value="2">تیم پشتیبانی و سفارشات</option>
                                    <option value="3">گزارش خطا</option>
                                </select>
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="contact_name_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 mb-3 mt-2">
                                <label>عنوان تیکت</label>
                                <input type="text" name="title" id="title"
                                    class="form-control custom-radius input-border-focus" placeholder="عنوان تیکت">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="title_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 mb-3 mt-2">
                                    <label>اولویت <span class="text-danger">*</span></label>
                                    <select name="priority" class="form-control custom-select-input custom-radius input-border-focus">
                                        <option value="1">کم</option>
                                        <option value="2" selected>معمولی</option>
                                        <option value="3">بالا</option>
                                        <option value="4">فوری</option>
                                    </select>
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="priority_error"></span>
                                    </div>
                                </div>

                            <div class="col-md-12 col-xs-12 mb-3 mt-2">
                                <label>متن تیکت</label>
                                <textarea id="message" name="message" rows="5" class="form-control custom-radius input-border-focus"
                                    placeholder="متن خود را وارد کنید"></textarea>
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="message_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 mb-3 mt-2">
                                <label>کد امنیتی</label>
                                <div class="captcha-row">
                                    <span class="captcha-question" id="captchaLabel">
                                        {{ $captcha_question ?? '5 + 3 = ?' }}
                                    </span>
                                    <button type="button" class="refresh-captcha-btn" id="refreshCaptchaBtn">
                                        <i class="fa fa-refresh"></i> جدید
                                    </button>
                                    <input type="text" name="captcha" id="captcha" class="form-control custom-radius input-border-focus" placeholder="حاصل" >
                                </div>
                                <div class=" mb-3 mt-1">
                                <span class=" text-danger error-message" id="captcha_error"></span>
                            </div>

                            <div class="col-md-6 mt-4">
                                <p>پیوست فایل ها (اختیاری)</p>
                                <button type="button" id="attachButton" class="btn btn-beta-outline mb-2 mt-2 ml-1">افزودن
                                    فایل</button>
                                    <br>
                                <span>پسوندهای مجاز: JPG / JPEG / PNG / PDF / ZIP / GIF / WEBP / SVG / ICO</span>

                                <div id="fileList"></div>

                                <input type="file" id="fileInput" style="display: none;"     accept=".jpg,.jpeg,.png,.pdf,.gif,.zip,.webp,.svg,.ico"
                                       multiple>
                                <div class="mt-2">
                                    <span class="text-danger error-message" id="attachments_error"></span>
                                </div>

                            </div>



                        </div>
                            <div class="d-flex justify-content-end col-md-12 mt-8">
                                <button type="submit" class="btn btn-beta-solid">تایید</button>
                                <a href="{{ route('list_tickets') }}" class="btn btn-beta-outline">انصراف</a>
                            </div>
                    </div>
                    </div>
                </form>
                @endif
                @if(auth()->user()->hasRole('seller'))
                <form id="ticketFormUser" method="post" action="" class="x_panel rounded-4">
                    @csrf

                    <div class="form-group mt-8">
                        <div class="row">
                              <div class="col-md-6 col-xs-12 mb-3">
                                    <label>اولویت <span class="text-danger">*</span></label>
                                    <select name="priority" class="form-control custom-radius custom-select-input input-border-focus">
                                        <option value="1">کم</option>
                                        <option value="2" selected>معمولی</option>
                                        <option value="3">بالا</option>
                                        <option value="4">فوری</option>
                                    </select>
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="priority_error"></span>
                                    </div>
                                </div>
                                @php $sellerStore = auth()->user()->stores()->first(); @endphp

                                @if($sellerStore)
                                    <input type="hidden" name="recipient_type" value="store">
                                    <input type="hidden" name="store_id" value="{{ $sellerStore->id }}">
                                @else
                                    <input type="hidden" name="recipient_type" value="user">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                @endif
                            <div class="col-md-6 col-xs-12 mb-3">
                                <label>تیم  گیرنده </label>
                                <select id="contact_name"
                                        class="form-control custom-radius custom-select-input input-border-focus">
                                    {{-- <option value="0">درخواست ماژول با فیچر جدید</option> --}}
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
                                <input type="text" name="title" id="title"
                                       class="form-control custom-radius input-border-focus" placeholder="عنوان تیکت">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="title_error"></span>
                                </div>
                            </div>

                            <div class="col-md-12 col-xs-12 mb-3 mt-2">
                                <label>متن تیکت</label>
                                <textarea id="message" name="message" rows="5" class="form-control custom-radius input-border-focus"
                                          placeholder="متن خود را وارد کنید"></textarea>
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="message_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 mb-3 mt-2">
                                <label>کد امنیتی</label>
                                <div class="captcha-row">
                                    <span class="captcha-question" id="captchaLabel">
                                        {{ $captcha_question ?? '5 + 3 = ?' }}
                                    </span>
                                    <button type="button" class="refresh-captcha-btn" id="refreshCaptchaBtn">
                                        <i class="fa fa-refresh"></i> جدید
                                    </button>
                                    <input type="text" name="captcha" id="captcha" class="form-control custom-radius input-border-focus" placeholder="حاصل" >
                                </div>
                                <div class=" mb-3 mt-1">
                                    <span class=" text-danger error-message" id="captcha_error"></span>
                                </div>

                                <div class="col-md-6 mt-4">
                                    <p>پیوست فایل ها (اختیاری)</p>
                                    <button type="button" id="attachButton" class="btn btn-beta-outline mb-2 mt-2 ml-1">افزودن
                                        فایل</button>
                                    <br>
                                    <span>پسوندهای مجاز: JPG / JPEG / PNG / PDF / ZIP / GIF / WEBP / SVG / ICO</span>

                                    <div id="fileList"></div>

                                    <input type="file" id="fileInput" style="display: none;"     accept=".jpg,.jpeg,.png,.pdf,.gif,.zip,.webp,.svg,.ico"
                                           multiple>
                                    <div class="mt-2">
                                        <span class="text-danger error-message" id="attachments_error"></span>
                                    </div>

                                </div>

                            </div>
                            <div class="d-flex justify-content-end col-md-12 mt-8">
                                <button type="submit" class="btn btn-beta-solid">تایید</button>
                                <a href="{{ route('list_tickets') }}" class="btn btn-beta-outline">انصراف</a>
                            </div>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
@endsection
