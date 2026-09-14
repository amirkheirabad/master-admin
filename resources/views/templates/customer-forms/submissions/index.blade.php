@extends('layouts.admin.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script src="{{ asset('/js/customer-form-list.js') }}"></script>
    <script>
        $('.select2').select2({
            placeholder: 'انتخاب کنید',
            allowClear: true,
            width: '100%',
            language: { noResults: function () { return 'نتیجه‌ای یافت نشد'; } }
        });
    </script>
@endsection

@section('content')
<div class="row"><div class="col-md-12">
    <h3>پاسخ‌های فرم‌ها</h3>

    <div class="d-flex justify-content-end mt-2 mb-3">
        <form id="customerFormFilters" method="get" action="">
            <div class="d-flex align-items-center">
                <div class="search-container">
                    <button class="search-button"><i class="fa fa-search"></i></button>
                    <input type="text" name="search_query" value="{{ request('search_query') }}"
                        class="search-input" placeholder="جستجو کنید...">
                </div>

                <div class="dropdown custom-dropdown">
                    <button class="btn btn-white-new dropdown-toggle d-inline-flex align-items-center position-relative"
                        type="button" id="filterDropdown" style="gap: 8px;">
                        <i class="fa fa-filter"></i> فیلترها
                        <span id="filterBadge" class="badge badge-danger bg-beta"
                            style="color: white; border-radius: 50%; padding: 2px 6px; font-size: 11px; display: none; margin-left: 4px;">0</span>
                    </button>

                    <div class="dropdown-menu rounded-5" id="filterMenu" style="padding: 15px; min-width: 320px;">
                        <div class="mb-2">
                            <select name="store_id" class="form-control custom-radius select2" data-placeholder="فروشگاه">
                                <option value="">فروشگاه</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" @selected(request('store_id') == $store->id)>{{ $store->store_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <select name="form_id" class="form-control custom-radius select2" data-placeholder="فرم">
                                <option value="">فرم</option>
                                @foreach($forms as $form)
                                    <option value="{{ $form->id }}" @selected(request('form_id') == $form->id)>{{ $form->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <select name="status" class="form-control custom-radius select2" data-placeholder="وضعیت">
                                <option value="">وضعیت</option>
                                <option value="current" @selected(request('status') === 'current')>فعلی</option>
                                <option value="old" @selected(request('status') === 'old')>قدیمی</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" id="clearFiltersBtn" class="btn btn-link text-default text-bold" style="padding: 0;">حذف فیلترها</button>
                            <button type="submit" class="btn btn-beta-solid mr-6">اعمال</button>
                            <button type="button" class="btn btn-beta-outline" id="cancelFilterBtn">انصراف</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="x_panel rounded-top mt-2 p-0"><table class="table">
        <thead><tr><th>فرم</th><th>فروشگاه / مشتری</th><th>نسخه</th><th>وضعیت</th><th>آخرین ویرایش</th><th>عملیات</th></tr></thead>
        <tbody>
        @forelse($submissions as $submission)
            <tr>
                <td>{{ $submission->assignment->form->title }}</td>
                <td>{{ $submission->assignment->store->store_name }} / {{ $submission->assignment->store->user?->name }}</td>
                <td>{{ $submission->formVersion->version_number }}</td>
                <td>{{ $submission->assignment->current_submission_id === $submission->id ? 'فعلی' : 'قدیمی' }}</td>
                <td class="fa-number">{{ Verta($submission->submitted_at)->format('Y/m/d H:i') }}</td>
                <td><a href="{{ route('customer-forms.submissions.show', $submission) }}" class="text-beta"><i class="fa fa-eye fa-x"></i></a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center p-10 text-muted">پاسخی یافت نشد.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    <div class="d-flex justify-content-center">{{ $submissions->withQueryString()->links('vendor.pagination.bootstrap-5') }}</div>
</div></div>
@endsection
