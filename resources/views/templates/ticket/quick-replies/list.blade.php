{{-- resources/views/templates/ticket/quick-replies/list.blade.php --}}
@extends('layouts.admin.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script src="{{ asset('/js/quick-replies.js') }}"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>جواب‌های آماده</h3>
            <button class="btn btn-beta-solid" id="addNewBtn">
                <i class="fa fa-plus"></i> افزودن جواب جدید
            </button>
        </div>

        {{-- فرم افزودن/ویرایش --}}
        <div class="x_panel rounded-3 p-3 mb-3" id="formPanel" style="display:none;">
            <h5 id="formTitle">افزودن جواب جدید</h5>
            <input type="hidden" id="editId">
            <div class="mb-2">
                <label>عنوان (نمایش در منو)</label>
                <input type="text" id="qrTitle" class="form-control custom-radius" placeholder="مثلا: پاسخ تاخیر ارسال">
            </div>
            <div class="mb-2">
                <label>متن جواب</label>
                <textarea id="qrBody" class="form-control custom-radius" rows="4" placeholder="متن کامل پاسخ آماده را وارد کنید..."></textarea>
            </div>
            <div class="d-flex" style="gap:8px;">
                <button class="btn btn-beta-solid" id="saveBtn">ذخیره</button>
                <button class="btn btn-beta-outline" id="cancelFormBtn">انصراف</button>
            </div>
        </div>

        {{-- جدول --}}
        <div class="x_panel rounded-top p-0">
            <table class="table">
                <thead class="responsive-table-head">
                    <tr>
                        <th>#</th>
                        <th>عنوان</th>
                        <th>متن</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody id="qrTableBody">
                    @forelse($quickReplies as $qr)
                    <tr id="qr-row-{{ $qr->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $qr->title }}</td>
                        <td class="text-muted" style="max-width:400px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $qr->body }}
                        </td>
                        <td>
                            <a href="javascript:;" class="text-beta fa-x edit-qr"
                               data-id="{{ $qr->id }}"
                               data-title="{{ $qr->title }}"
                               data-body="{{ $qr->body }}">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="javascript:;" class="text-danger fa-x delete-qr mr-1"
                               data-id="{{ $qr->id }}">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="4" class="text-center text-muted">هیچ جوابی ثبت نشده است</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-2">
            {{ $quickReplies->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
