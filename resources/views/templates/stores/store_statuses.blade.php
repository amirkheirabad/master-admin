@extends('layouts.admin.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script src="{{ asset('/js/store-status.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between">
                    <h3>وضعیت‌های فروشگاه</h3>
                    <div class="mt-2">
                        <button type="button" class="btn btn-beta-solid" data-toggle="modal" data-target="#createStatusModal">
                            <i class="fa fa-plus ml-1"></i>
                            افزودن وضعیت
                        </button>
                    </div>
                </div>
            </div>

            <div class="x_panel rounded-top mt-2 p-0">
                <table class="table">
                    <thead class="responsive-table-head">
                    <tr>
                        <th>#</th>
                        <th>نام وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($storeStatuses as $status)
                        <tr>
                            <th scope="row">{{ $status->id }}</th>
                            <td>{{ $status->name }}</td>
                            <td>
                                <button type="button" class=" btn-link text-success edit-status p-0"
                                        data-id="{{ $status->id }}" data-name="{{ $status->name }}"
                                        data-toggle="modal" data-target="#editStatusModal" title="ویرایش">
                                    <i class="fa fa-pencil text-beta fa-x"></i>
                                </button>
                                <button type="button" class=" btn-link delete-status text-danger p-0 mr-1"
                                        data-id="{{ $status->id }}" title="حذف">
                                    <i class="fa fa-trash fa-x"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center py-4 text-muted">وضعیتی ثبت نشده است</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $storeStatuses->withQueryString()->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>

    <div id="createStatusModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createStatusModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" id="createStatusModalLabel">ایجاد وضعیت فروشگاه</h3>
                </div>
                <div class="modal-body">
                    <label for="status_name">نام وضعیت</label>
                    <input type="text" id="status_name" class="form-control custom-radius">
                    <small class="text-danger" id="status_name_error"></small>
                </div>
                <div class="d-flex justify-content-center mt-8 mb-3 gap">
                    <button type="button" class="btn btn-beta-outline" data-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-beta-solid" id="create-status">ذخیره</button>
                </div>
            </div>
        </div>
    </div>

    <div id="editStatusModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editStatusModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" id="editStatusModalLabel">ویرایش وضعیت فروشگاه</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_status_id">
                    <label for="edit_status_name">نام وضعیت</label>
                    <input type="text" id="edit_status_name" class="form-control custom-radius">
                    <small class="text-danger" id="edit_status_name_error"></small>
                </div>
                <div class="d-flex justify-content-center mt-8 mb-3 gap">
                    <button type="button" class="btn btn-beta-outline" data-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-beta-solid" id="update-status">ویرایش</button>
                </div>
            </div>
        </div>
    </div>
@endsection
