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
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        $(document).on('click','.delete-role', function (e) {
            e.preventDefault();

            let id = $(this).data('id');

            Swal.fire({
                title:'حذف نقش',
                text: 'آیا از حذف نقش مطمئن هستید؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'بله',
                cancelButtonText: 'خیر',
            }).then((result) => {
                if (result.isConfirmed) {

                    fetch(`/role-delete/${id}`, {
                        headers: {
                            "X-CSRF-Token": csrf,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        method: "DELETE",
                    })
                        .then(function (response) {
                            if(response.ok)
                            {
                                Swal.fire({
                                    title: "حذف شد!",
                                    text: "نقش با موفقیت حذف شد",
                                    icon: "success",
                                    showCancelButton: false,
                                    confirmButtonText: "بستن",
                                })
                                $(`.item-record${id}`).remove()
                            }
                            else {
                                Swal.fire({
                                    title: "خطا!",
                                    text: "حذف نقش انجام نشد",
                                    icon: "error",
                                    showCancelButton: false,
                                    confirmButtonText: "بستن",
                                });
                            }
                        })
                }
            })
        })


    </script>
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
                        <h3>لیست نقش ها</h3>
                    </div>
                </div>

                <div class="x_panel rounded-top mt-2 p-0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>نام نقش</th>
                            <th>سظح دسترسی</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr class="item-record{{$role->id}}">
                                <th scope="row">#</th>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @foreach($role->permissions->pluck('name') as $permissionName)
                                        {{ __($permissionName) }}_
                                    @endforeach
                                </td>
                                <td data-title="عملیات" class="responsive-table-td">
                                    <div class="">
                                        <a href="{{ route('role-edit', $role->id) }}" class="text-success" data-id="{{ $role->id }}">
                                            <i class="fa fa-pencil fa-x"></i>
                                        </a>
                                        <a href="javascript:;" class="text-danger delete-role" data-id="{{ $role->id }}">
                                            <i class="fa fa-trash fa-x"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                </div>
            </div>
        </div>
@endsection
