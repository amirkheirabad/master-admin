@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
    {{--    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">--}}
@endsection

@section('js')
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script src="{{ asset('/js/insert-role.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row ">
                    <div class="col-md-6">
                        <h3>افزودن نقش</h3>
                    </div>
                </div>

                <form id="roleForm" method="post" action="" class=" rounded-4">
                    @csrf

                    <div class="form-group mt-8">
                        <div class="row">

                            <div class="col-md-6 col-xs-12 mb-3">
                                <label>نام نقش</label>
                                <input type="text" name="name" id="name" class="form-control custom-radius input-border-focus">
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="name_error"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 mb-3">
                                <label>دسترسی ها</label>
                                <select id="permission" class="form-control custom-radius select2 input-border-focus" multiple>
                                    @foreach($permissions as $permission)
                                        <option {{ (collect(old('$permissions'))->contains($permission->name)) ? 'selected': '' }} value="{{$permission->name}}">{{ __($permission->name) }}</option>
                                    @endforeach
                                </select>
                                <div class="mt-1">
                                    <span class="text-danger error-message" id="permissions_error"></span>
                                </div>
                            </div>



                            <div class="d-flex justify-content-end col-md-12 mt-8">
                                <button type="submit" class="btn btn-beta-solid ">تایید</button>
                                <a href="{{ route('role-list') }}" class="btn btn-beta-outline ">انصراف</a>
                            </div>

                        </div>


                    </div>
                </form>
            </div>
        </div>
@endsection
