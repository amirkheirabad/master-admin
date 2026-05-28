@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/jalalidatepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/videos-list.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/jalalidatepicker.min.js') }}"></script>
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <script src="{{ asset('/js/videos-show.js') }}"></script>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                        <div>
                            <h3>آموزش ها</h3>
                        </div>
                    </div>
                </div>
                <div class="x_panel rounded-4">
                    <div class="panel-title">
                        <h4>دسته بندی ویدئوها</h4>
                    </div>
                    <div class="category-slider-container rounded-4">
                        <button class="slider-arrow prev-arrow" id="prevCategory">
                            <i class="fa fa-chevron-right"></i>
                        </button>

                        <div class="category-slider-wrapper">
                            <div class="category-slider" id="categorySlider">
                                @foreach($categories as $category)
                                    <div class="category-card" data-id="{{ $category->id }}">
                                        <div class="category-name">{{ $category->name }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button class="slider-arrow next-arrow" id="nextCategory">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                    </div>


                    <div class="videos-container">
                        <div class="videos-grid" id="videosGrid">
                        </div>
                    </div>
                </div>
            </div>
@endsection
