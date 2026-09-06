@extends('layouts.admin.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script src="{{ asset('/js/team-list.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-md-6"><h3>لیست تیم‌ها</h3></div>
                <div class="col-md-6 text-left"><a href="{{ route('team-insert') }}" class="btn btn-beta-solid">افزودن تیم</a></div>
            </div>

            <div class="x_panel rounded-top mt-2 p-0">
                <table class="table">
                    <thead><tr><th>#</th><th>نام تیم</th><th>عملیات</th></tr></thead>
                    <tbody>
                    @foreach($teams as $team)
                        <tr>
                            <td>{{ $team->id }}</td>
                            <td>{{ $team->name }}</td>
                            <td class="d-flex">
                                <a href="{{ route('team-edit', $team) }}" class="text-success"><i class="fa fa-pencil fa-x"></i></a>
                                <form method="post" action="{{ route('team-delete', $team) }}" class="d-inline team-delete-form" data-team-name="{{ $team->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class=" btn-link text-danger p-0" aria-label="حذف {{ $team->name }}"><i class="fa fa-trash fa-x"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
