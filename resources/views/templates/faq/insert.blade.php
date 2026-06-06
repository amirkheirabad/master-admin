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
    <script src="{{ asset('/js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('/js/faq-insert.js') }}"></script>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between">
                    <div>
                        <h3>افزودن سوال</h3>
                    </div>
                </div>
            </div>
            <div class="">
                <form id="FAQForm" method="post" action="" class=" rounded-4">
                    @csrf

                    <div class="form-group mt-8">
                        <div class="row x_panel">
                            <div class="col-md-6 col-xs-12 mb-3">
                                <label>سوال</label>
                                <input type="text" name="question" id="question"
                                       class="form-control custom-radius input-border-focus">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="question_error"></span>
                                </div>
                            </div>

                            <div class="col-md-12 col-xs-12 mb-3 mt-2">
                                <label>پاسخ</label>
                                <textarea id="answer" name="answer" class="form-control custom-radius input-border-focus"></textarea>
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="answer_error"></span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end col-md-12 mt-8">
                                <button type="submit" class="btn btn-beta-solid">تایید</button>
                                <a href="{{ route('faq_list') }}" class="btn btn-beta-outline">انصراف</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

    </div>
@endsection
