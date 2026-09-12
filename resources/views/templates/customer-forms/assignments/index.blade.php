@extends('layouts.admin.master')

@section('content')
<div class="row"><div class="col-md-12">
    <h3>تخصیص فرم‌ها</h3>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <form method="post" action="{{ route('customer-forms.assignments.store') }}" class="x_panel row">
        @csrf
        <div class="col-md-5"><label>فرم</label><select name="form_id" class="form-control" required><option value="">انتخاب کنید</option>@foreach($forms as $form)<option value="{{ $form->id }}">{{ $form->title }}</option>@endforeach</select></div>
        <div class="col-md-5"><label>فروشگاه</label><select name="store_id" class="form-control" required><option value="">انتخاب کنید</option>@foreach($stores as $store)<option value="{{ $store->id }}">{{ $store->store_name }} — {{ $store->user?->name }}</option>@endforeach</select></div>
        <div class="col-md-2 d-flex align-items-end"><button class="btn btn-beta-solid">ساخت تخصیص</button></div>
    </form>
    <div class="x_panel p-0"><table class="table">
        <thead><tr><th>فرم</th><th>فروشگاه / مشتری</th><th>نسخه</th><th>پاسخ</th><th>دسترسی</th><th>عملیات</th></tr></thead>
        <tbody>
        @foreach($assignments as $assignment)
            <tr>
                <td>{{ $assignment->form->title }}</td>
                <td>{{ $assignment->store->store_name }} / {{ $assignment->store->user?->name }}</td>
                <td>{{ $assignment->formVersion->version_number }}</td>
                <td>{{ $assignment->currentSubmission ? 'ثبت شده' : 'ثبت نشده' }}</td>
                <td>{{ $assignment->is_active ? 'فعال' : 'غیرفعال' }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-beta-outline copy-link" data-link="{{ route('customer-forms.public.show', $assignment->token_encrypted) }}">کپی لینک</button>
                    <form class="d-inline" method="post" action="{{ route('customer-forms.assignments.toggle', $assignment) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-beta-outline">{{ $assignment->is_active ? 'غیرفعال‌کردن دسترسی' : 'فعال‌کردن دسترسی' }}</button></form>
                    <form class="d-inline" method="post" action="{{ route('customer-forms.assignments.rotate', $assignment) }}" onsubmit="return confirm('لینک قبلی نامعتبر شود؟')">@csrf<button class="btn btn-sm btn-beta-outline">ساخت لینک جدید</button></form>
                    @if($assignment->currentSubmission && $assignment->form->published_version_id !== $assignment->form_version_id)
                        <form class="d-inline" method="post" action="{{ route('customer-forms.assignments.upgrade', $assignment) }}" onsubmit="return confirm('پاسخ فعلی تاریخی و فرم به نسخه جدید منتقل شود؟')">@csrf<button class="btn btn-sm btn-beta-solid">ارتقای نسخه</button></form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table></div>
    <div class="d-flex justify-content-center">{{ $assignments->links('vendor.pagination.bootstrap-5') }}</div>
</div></div>
@endsection

@section('js')
<script>
document.querySelectorAll('.copy-link').forEach(button => button.addEventListener('click', () => navigator.clipboard.writeText(button.dataset.link)));
</script>
@endsection
