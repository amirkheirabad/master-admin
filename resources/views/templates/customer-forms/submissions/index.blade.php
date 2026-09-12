@extends('layouts.admin.master')

@section('content')
<div class="row"><div class="col-md-12">
    <h3>پاسخ‌های فرم‌ها</h3>
    <div class="x_panel p-0"><table class="table">
        <thead><tr><th>فرم</th><th>فروشگاه / مشتری</th><th>نسخه</th><th>وضعیت</th><th>آخرین ویرایش</th><th></th></tr></thead>
        <tbody>
        @foreach($submissions as $submission)
            <tr>
                <td>{{ $submission->assignment->form->title }}</td>
                <td>{{ $submission->assignment->store->store_name }} / {{ $submission->assignment->store->user?->name }}</td>
                <td>{{ $submission->formVersion->version_number }}</td>
                <td>{{ $submission->assignment->current_submission_id === $submission->id ? 'فعلی' : 'تاریخی' }}</td>
                <td>{{ $submission->submitted_at }}</td>
                <td><a href="{{ route('customer-forms.submissions.show', $submission) }}">مشاهده</a></td>
            </tr>
        @endforeach
        </tbody>
    </table></div>
    <div class="d-flex justify-content-center">{{ $submissions->links('vendor.pagination.bootstrap-5') }}</div>
</div></div>
@endsection
