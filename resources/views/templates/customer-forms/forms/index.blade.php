@extends('layouts.admin.master')

@section('content')
<div class="row"><div class="col-md-12">
    <div class="d-flex justify-content-between align-items-center">
        <h3>فرم‌های مشتری</h3>
        <a href="{{ route('customer-forms.create') }}" class="btn btn-beta-solid">فرم جدید</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="x_panel rounded-top mt-2 p-0">
        <table class="table">
            <thead><tr><th>#</th><th>عنوان</th><th>نسخه منتشرشده</th><th>پیش‌نویس</th><th>وضعیت</th><th>عملیات</th></tr></thead>
            <tbody>
            @forelse($forms as $form)
                <tr>
                    <td>{{ $form->id }}</td><td>{{ $form->title }}</td>
                    <td>{{ $form->publishedVersion?->version_number ?? '—' }}</td>
                    <td>{{ $form->draftVersion?->version_number ?? '—' }}</td>
                    <td>{{ $form->is_active ? 'فعال' : 'غیرفعال' }}</td>
                    <td>
                        <a href="{{ route('customer-forms.builder', $form) }}" class="btn btn-sm btn-beta-solid">سؤال‌ها</a>
                        <a href="{{ route('customer-forms.edit', $form) }}" class="btn btn-sm btn-beta-outline">ویرایش</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">هنوز فرمی ساخته نشده است.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">{{ $forms->links('vendor.pagination.bootstrap-5') }}</div>
</div></div>
@endsection
