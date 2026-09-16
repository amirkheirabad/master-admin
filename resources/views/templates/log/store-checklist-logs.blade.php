@extends('layouts.admin.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/jalalidatepicker.min.css') }}">
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>تاریخچه چک‌لیست فروشگاه‌ها</h3>
            <div class="alert alert-info mt-3">
                این گزارش فقط تغییراتی را نشان می‌دهد که پس از فعال‌شدن ثبت تاریخچه چک‌لیست انجام شده‌اند؛ تغییرات قدیمی در دسترس نیستند.
            </div>

            <form method="get" class="row align-items-end mt-3 mb-3">
                <div class="col-md-3 mb-2">
                    <label for="checklist_from">از تاریخ</label>
                    <input id="checklist_from" name="checklist_from" value="{{ request('checklist_from') }}" data-jdp class="form-control custom-radius" autocomplete="off">
                    @error('checklist_from')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-2">
                    <label for="checklist_to">تا تاریخ</label>
                    <input id="checklist_to" name="checklist_to" value="{{ request('checklist_to') }}" data-jdp class="form-control custom-radius" autocomplete="off">
                    @error('checklist_to')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-2">
                    <label for="store_id">فروشگاه</label>
                    <select id="store_id" name="store_id" class="form-control custom-radius select2">
                        <option value="">همه فروشگاه‌ها</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" @selected((string) request('store_id') === (string) $store->id)>{{ $store->store_name }}</option>
                        @endforeach
                    </select>
                    @error('store_id')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2 mb-2 d-flex align-items-end" style="margin-top: 24px">
                    <button class="form-control btn btn-beta-solid" type="submit">اعمال فیلتر</button>
                </div>
            </form>

            <div class="x_panel rounded-top p-0">
                <table class="table">
                    <thead>
                    <tr>
                        <th>فروشگاه</th>
                        <th>نام کاربر سایت</th>
                        <th>تاریخ و زمان</th>
                        <th>چک‌لیست</th>
                        <th>وضعیت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->properties['store_name'] ?? 'نامشخص' }}</td>
                            <td>{{ $log->properties['user_name'] ?? $log->user?->name ?? 'نامشخص' }}</td>
                            <td class="fa-number">{{ Verta($log->created_at)->format('Y/m/d H:i') }}</td>
                            <td>{{ $log->properties['checklist_name'] ?? 'نامشخص' }}</td>
                            <td>
                                @if($log->properties['new_checked'] ?? false)
                                    <span class="bg-jade p-2 custom-radius">تیک زده شد</span>
                                @else
                                    <span class="bg-red-new p-2 custom-radius">تیک برداشته شد</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">تغییری برای نمایش وجود ندارد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $logs->withQueryString()->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script src="{{ asset('/js/jalalidatepicker.min.js') }}"></script>
    <script>jalaliDatepicker.startWatch();</script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
        $('.select2').select2({
            placeholder: "انتخاب کنید",
            allowClear: true,
            width: '100%',
            language: {
                noResults: function () {
                    return "نتیجه‌ای یافت نشد";
                }
            }
        });
        $(document).ready(function() {
            $('#store_id').select2();
        });
    </script>
@endsection
