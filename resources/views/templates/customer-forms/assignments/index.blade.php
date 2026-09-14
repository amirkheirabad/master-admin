@extends('layouts.admin.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script src="{{ asset('/js/customer-form-list.js') }}"></script>
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
            $('#user_id').select2();
        });

        function assignmentNotice(icon, message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: icon,
                    title: message,
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
                return;
            }

            window.alert(message);
        }

        function fallbackCopy(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            textarea.setSelectionRange(0, textarea.value.length);

            let copied = false;
            try {
                copied = document.execCommand('copy');
            } catch (error) {
                copied = false;
            }
            textarea.remove();

            return copied;
        }

        document.addEventListener('click', async function (event) {
            const button = event.target.closest('.copy-link');
            if (!button) {
                return;
            }

            button.disabled = true;
            try {
                let copied = false;
                if (navigator.clipboard?.writeText) {
                    try {
                        await navigator.clipboard.writeText(button.dataset.link);
                        copied = true;
                    } catch (error) {
                        copied = fallbackCopy(button.dataset.link);
                    }
                } else {
                    copied = fallbackCopy(button.dataset.link);
                }

                if (!copied) {
                    throw new Error('copy failed');
                }

                assignmentNotice('success', 'لینک با موفقیت کپی شد');
            } catch (error) {
                assignmentNotice('error', 'کپی لینک انجام نشد. لطفاً دوباره تلاش کنید');
            } finally {
                button.disabled = false;
            }
        });
    </script>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <h3>تخصیص فرم‌ها</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="post" action="{{ route('customer-forms.assignments.store') }}" class="x_panel rounded-top mt-2">
                @csrf
                <div class="row">
                    <div class="col-md-5 col-sm-6 col-xs-12 mb-3">
                        <label for="form_id">فرم</label>
                        <select id="form_id" name="form_id" class="form-control custom-radius custom-select-input input-border-focus" required>
                            <option value="">انتخاب کنید</option>
                            @foreach($forms as $form)
                                <option value="{{ $form->id }}">{{ $form->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5 col-sm-6 col-xs-12 mb-3">
                        <label for="store_id">فروشگاه</label>
                        <select id="store_id" name="store_id" class="form-control custom-radius select2" required>
                            <option value=""></option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->store_name }} — {{ $store->user?->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12 col-xs-12 mt-5">
                        <button type="submit" class=" btn-beta-solid w-100">ساخت تخصیص</button>
                    </div>
                </div>
            </form>

            <div class="d-flex justify-content-end mb-3" style="margin-top: 40px">
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
                                    <select name="form_id" class="form-control custom-radius select2" data-placeholder="فرم">
                                        <option value="">فرم</option>
                                        @foreach($filterForms as $form)
                                            <option value="{{ $form->id }}" @selected(request('form_id') == $form->id)>{{ $form->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <select name="store_id" class="form-control custom-radius select2" data-placeholder="فروشگاه">
                                        <option value="">فروشگاه</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" @selected(request('store_id') == $store->id)>{{ $store->store_name }}</option>
                                        @endforeach
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

            <div class="x_panel rounded-top mt-2 p-0">
                <table class="table" style="margin-bottom: 0">
                    <thead class="responsive-table-head">
                        <tr>
                            <th>فرم</th>
                            <th>فروشگاه / مشتری</th>
                            <th>نسخه</th>
                            <th>پاسخ</th>
                            <th>دسترسی</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr class="responsive-table-row">
                                <td data-title="فرم" class="responsive-table-td">{{ $assignment->form->title }}</td>
                                <td data-title="فروشگاه / مشتری" class="responsive-table-td">
                                    {{ $assignment->store->store_name }} / {{ $assignment->store->user?->name }}
                                </td>
                                <td data-title="نسخه" class="responsive-table-td">{{ $assignment->formVersion->version_number }}</td>
                                <td data-title="پاسخ" class="responsive-table-td">
                                    <span class="{{ $assignment->currentSubmission ? 'bg-jade' : 'bg-warning' }} p-2 custom-radius">
                                        {{ $assignment->currentSubmission ? 'ثبت شده' : 'ثبت نشده' }}
                                    </span>
                                </td>
                                <td data-title="دسترسی" class="responsive-table-td">
                                    <span class="{{ $assignment->is_active ? 'bg-jade' : 'bg-red-new' }} p-2 custom-radius">
                                        {{ $assignment->is_active ? 'فعال' : 'غیرفعال' }}
                                    </span>
                                </td>
                                <td data-title="عملیات" class="responsive-table-td">
                                    <div class="d-flex align-items-center">
                                        <button type="button" class="btn-link text-beta copy-link p-0" data-link="{{ route('customer-forms.public.show', $assignment->token_encrypted) }}" title="کپی لینک" aria-label="کپی لینک">
                                            <i class="fa fa-copy fa-x"></i>
                                        </button>

                                        <form class="d-inline mr-1" method="post" action="{{ route('customer-forms.assignments.toggle', $assignment) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-link {{ $assignment->is_active ? 'text-danger' : 'text-success' }} p-0" title="{{ $assignment->is_active ? 'غیرفعال‌کردن دسترسی' : 'فعال‌کردن دسترسی' }}" aria-label="{{ $assignment->is_active ? 'غیرفعال‌کردن دسترسی' : 'فعال‌کردن دسترسی' }}">
                                                <i class="fa {{ $assignment->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} fa-x"></i>
                                            </button>
                                        </form>

                                        <form class="d-inline mr-1" method="post" action="{{ route('customer-forms.assignments.rotate', $assignment) }}" onsubmit="return confirm('لینک قبلی نامعتبر شود؟')">
                                            @csrf
                                            <button type="submit" class="btn-link text-beta p-0" title="ساخت لینک جدید" aria-label="ساخت لینک جدید">
                                                <i class="fa fa-refresh fa-x"></i>
                                            </button>
                                        </form>

                                        @if($assignment->currentSubmission && $assignment->form->published_version_id !== $assignment->form_version_id)
                                            <form class="d-inline mr-1" method="post" action="{{ route('customer-forms.assignments.upgrade', $assignment) }}" onsubmit="return confirm('پاسخ فعلی تاریخی و فرم به نسخه جدید منتقل شود؟')">
                                                @csrf
                                                <button type="submit" class="btn-link text-success p-0" title="ارتقای نسخه" aria-label="ارتقای نسخه">
                                                    <i class="fa fa-level-up fa-x"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-10 text-muted">تخصیصی ثبت نشده است.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $assignments->withQueryString()->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
