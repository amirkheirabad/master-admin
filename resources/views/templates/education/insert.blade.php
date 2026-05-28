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
    <script src="{{ asset('/js/insert-video.js') }}"></script>
    <style>
        .video-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f5f5f5;
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: 8px;
        }

        .video-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .video-icon {
            font-size: 20px;
        }

        .video-name {
            font-size: 13px;
            color: #333;
        }

        .video-size {
            font-size: 11px;
            color: #888;
            margin-right: 8px;
        }

        .video-remove {
            background: #ff6b6b;
            color: white;
            border: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }

        .video-remove:hover {
            background: #ff4444;
        }
    </style>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                        <div>
                            <h3>افزودن ویدئو</h3>
                        </div>
                    </div>
                </div>
                <div class="">
                    <form id="videoForm" method="post" action="" class="x_panel rounded-4">
                        @csrf

                        <div class="form-group mt-8">
                            <div class="row">


                                <div class="col-md-6 col-xs-12 mb-3">
                                    <label>دسته بندی ویدئو </label>
                                    <select id="category_id"
                                            class="form-control custom-radius select2">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="category_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xs-12 mb-3">
                                    <label>عنوان ویدئو</label>
                                    <input type="text" name="title" id="title"
                                           class="form-control custom-radius input-border-focus">
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="title_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-12 col-xs-12 mb-3 mt-2">
                                    <label>توضیحات ویدئو</label>
                                    <textarea id="description" name="description" rows="5" class="form-control custom-radius input-border-focus"
                                              placeholder="متن خود را وارد کنید"></textarea>
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="description_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-4">
                                    <button type="button" id="attachButton" class="btn btn-beta-outline mb-2 mt-2 ml-1">افزودن
                                        ویدئو</button>
                                    <br>
                                    <span>فرمت‌های مجاز: mp4, mov, avi, mkv (حداکثر 50MB)</span>

                                    <div id="fileList"></div>

                                    <input type="file" id="fileInput" style="display: none;" accept=".mp4,.mov,.avi,mkv." multiple>
                                    <div class="mt-2">
                                        <span class="text-danger error-message" id="file_path_error"></span>
                                    </div>

                                </div>

                                <div class="d-flex justify-content-end col-md-12 mt-8">
                                    <button type="submit" class="btn btn-beta-solid">تایید</button>
                                    <a href="{{ route('list_tickets') }}" class="btn btn-beta-outline">انصراف</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
@endsection
