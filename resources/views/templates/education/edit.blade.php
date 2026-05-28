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
    <script src="{{ asset('/js/edit-video.js') }}"></script>
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
                    <form id="editVideoForm" method="post" action="" class="x_panel rounded-4">
                        @csrf

                        <input type="hidden" id="video_id" value="{{ $video->id }}">

                        <div class="form-group mt-8">
                            <div class="row">


                                <div class="col-md-6 col-xs-12 mb-3">
                                    <label>دسته بندی ویدئو </label>
                                    <select id="category_id"
                                            class="form-control custom-radius select2">
                                        @foreach($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $video->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                        @endforeach
                                    </select>
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="category_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xs-12 mb-3">
                                    <label>عنوان ویدئو</label>
                                    <input value="{{ $video->title }}" type="text" name="title" id="title"
                                           class="form-control custom-radius input-border-focus">
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="title_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-12 col-xs-12 mb-3 mt-2">
                                    <label>توضیحات ویدئو</label>
                                    <textarea id="description" name="description" rows="5" class="form-control custom-radius input-border-focus"
                                              placeholder="متن خود را وارد کنید">{{ $video->description }}</textarea>
                                    <div class="mt-1">
                                        <span class="text-danger error-message" id="description_error"></span>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end col-md-12 mt-8">
                                    <button type="submit" class="btn btn-beta-solid">تایید</button>
                                    <a href="{{ route('video-list') }}" class="btn btn-beta-outline">انصراف</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
@endsection
