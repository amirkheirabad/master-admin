@extends('layouts.admin.master')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <h3>افزودن تیم</h3>

            <form method="post" action="{{ route('team-create') }}">
                @csrf
                <div class="form-group mt-8">
                    <label for="name">نام تیم</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control custom-radius input-border-focus">
                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="d-flex justify-content-end col-md-12 mt-8">
                    <button type="submit" class="btn btn-beta-solid">تایید</button>
                    <a href="{{ route('team-list') }}" class="btn btn-beta-outline">انصراف</a>
                </div>
            </form>
        </div>
    </div>
@endsection
