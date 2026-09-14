@extends('layouts.admin.master')

@section('content')
<link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
<style>
    .question-drag-handle {
        display: inline-flex;
        width: 36px;
        height: 36px;
        align-items: center;
        justify-content: center;
        margin-left: 8px;
        padding: 0;
        border: 0;
        border-radius: 7px;
        background: #f2f4f7;
        color: #73879c;
        cursor: grab;
        font-size: 16px;
        touch-action: none;
        transition: color .2s ease, background-color .2s ease;
    }

    .question-drag-handle:hover,
    .question-drag-handle:focus {
        outline: 0;
        background: #e9f0f7;
        color: #133c6d;
    }

    .question-drag-handle:active { cursor: grabbing; }

    tr.question-sortable-ghost { opacity: .4; }
    tr.question-sortable-chosen td { background: #f5f9ff; }
    tr.question-sortable-drag { box-shadow: 0 5px 16px rgba(19, 60, 109, .14); }

    @media (max-width: 576px) {
        .question-drag-handle {
            width: 44px;
            height: 44px;
        }
    }
</style>

<div class="row"><div class="col-md-12">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>{{ $form->title }}</h3>
            <p id="form-version-state">نسخه {{ $version?->version_number ?? '—' }} {{ $form->draft_version_id ? '(پیش‌نویس)' : '(منتشرشده)' }}</p>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('customer-forms.questions.create', ['form' => $form]) }}" class="btn btn-beta-solid ml-2">افزودن سؤال</a>
            <a href="{{ route('customer-forms.index') }}" class="btn btn-beta-outline ml-2">
                انصراف
            </a>
            <div id="draft-actions" class="d-flex">
                @if($form->draft_version_id)
                    <form method="post" action="{{ route('customer-forms.publish', $form) }}" onsubmit="return confirm('نسخه جدید منتشر شود؟')">
                        @csrf
                        <button class="btn btn-beta-solid">انتشار پیش‌نویس</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->has('form'))<div class="alert alert-danger">{{ $errors->first('form') }}</div>@endif

    @php($typeLabels = [
        'text' => 'متن کوتاه',
        'textarea' => 'متن بلند',
        'select' => 'انتخاب از لیست',
        'radio' => 'انتخاب یک گزینه',
        'checkbox' => 'انتخاب چند گزینه',
        'boolean' => 'بله / خیر',
    ])

    <div class="x_panel p-0">
        <table class="table">
            <thead><tr><th>ترتیب</th><th>سؤال</th><th>نوع</th><th>وضعیت</th><th>عملیات</th></tr></thead>
            <tbody id="question-list">
            @foreach($questions as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->label }}</td>
                    <td>{{ $typeLabels[$item->type] ?? $item->type }}</td>
                    <td>{{ $item->is_required ? 'اجباری' : 'اختیاری' }}</td>
                    <td class="d-flex align-items-center">
                        @if($loop->first)
                            <form class="d-none" method="post" action="{{ route('customer-forms.questions.reorder', $form) }}">
                                @csrf
                                @foreach($questions as $orderedQuestion)
                                    <input type="hidden" name="question_ids[]" value="{{ $orderedQuestion->id }}">
                                @endforeach
                            </form>
                        @endif

                        <a class="text-beta question-edit-link" href="{{ route('customer-forms.questions.edit', $item) }}" title="ویرایش" aria-label="ویرایش سؤال">
                            <i class="fa fa-pencil fa-x"></i>
                        </a>
                        <form class="d-inline mr-1 question-delete-form" method="post" action="{{ route('customer-forms.questions.destroy', $item) }}" onsubmit="return confirm('این سؤال از پیش‌نویس حذف شود؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-link text-danger p-0" title="حذف" aria-label="حذف سؤال">
                                <i class="fa fa-trash fa-x"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div></div>

<script type="application/json" id="question-order-data">@json($questions->pluck('id')->values())</script>
<script src="{{ asset('/js/sortable.js') }}"></script>
<script src="{{ asset('/js/sweetalert2.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reorderUrl = @json(route('customer-forms.questions.reorder', $form));
        const reorderForms = Array.from(document.forms).filter(form => form.action === reorderUrl);
        const reorderForm = reorderForms[0];
        const questionIds = JSON.parse(document.getElementById('question-order-data').textContent);

        if (!reorderForm || questionIds.length < 2 || typeof Sortable === 'undefined') {
            return;
        }

        const questionList = reorderForm.closest('tbody');
        const rows = Array.from(questionList.children).filter(row => row.matches('tr'));

        if (rows.length !== questionIds.length) {
            return;
        }

        rows.forEach(function (row, index) {
            row.dataset.questionId = questionIds[index];

            const handle = document.createElement('button');
            handle.type = 'button';
            handle.className = 'question-drag-handle';
            handle.title = 'برای تغییر ترتیب بکشید';
            handle.setAttribute('aria-label', 'جابجایی سؤال ' + (index + 1));
            handle.innerHTML = '<i class="fa fa-bars" aria-hidden="true"></i>';
            row.firstElementChild.prepend(handle);
        });

        function currentOrder() {
            return Array.from(questionList.children).map(row => String(row.dataset.questionId));
        }

        function renderOrder(questionOrder) {
            const rowsById = new Map(Array.from(questionList.children).map(row => [String(row.dataset.questionId), row]));
            questionOrder.forEach(id => questionList.append(rowsById.get(String(id))));
        }

        function reorderNotice(icon, message) {
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

        function updateBuilderState(html) {
            const documentCopy = new DOMParser().parseFromString(html, 'text/html');
            const state = documentCopy.getElementById('question-order-data');
            const freshIds = state ? JSON.parse(state.textContent) : [];
            const freshRows = Array.from(documentCopy.querySelectorAll('#question-list > tr'));
            const versionState = document.getElementById('form-version-state');
            const freshVersionState = documentCopy.getElementById('form-version-state');
            const draftActions = document.getElementById('draft-actions');
            const freshDraftActions = documentCopy.getElementById('draft-actions');
            const currentRows = Array.from(questionList.children);

            if (
                freshIds.length !== rows.length ||
                freshRows.length !== rows.length ||
                !versionState ||
                !freshVersionState ||
                !draftActions ||
                !freshDraftActions
            ) {
                throw new Error('question IDs missing from response');
            }

            const updates = freshRows.map((freshRow, index) => {
                const row = currentRows[index];
                const editLink = freshRow.querySelector('.question-edit-link');
                const deleteForm = freshRow.querySelector('.question-delete-form');
                const currentEditLink = row.querySelector('.question-edit-link');
                const currentDeleteForm = row.querySelector('.question-delete-form');

                if (!editLink || !deleteForm || !currentEditLink || !currentDeleteForm) {
                    throw new Error('question actions missing from response');
                }

                return {
                    row,
                    editLink: currentEditLink,
                    deleteForm: currentDeleteForm,
                    id: freshIds[index],
                    editUrl: editLink.href,
                    deleteUrl: deleteForm.action
                };
            });

            updates.forEach(update => {
                update.row.dataset.questionId = update.id;
                update.editLink.href = update.editUrl;
                update.deleteForm.action = update.deleteUrl;
            });

            versionState.textContent = freshVersionState.textContent;
            draftActions.innerHTML = freshDraftActions.innerHTML;
        }

        let orderBeforeDrag = currentOrder();
        let saving = false;
        const sortable = Sortable.create(questionList, {
            handle: '.question-drag-handle',
            animation: 150,
            ghostClass: 'question-sortable-ghost',
            chosenClass: 'question-sortable-chosen',
            dragClass: 'question-sortable-drag',
            onStart: function () {
                orderBeforeDrag = currentOrder();
            },
            onEnd: async function (event) {
                if (event.oldIndex === event.newIndex) {
                    return;
                }

                await saveOrder(currentOrder(), orderBeforeDrag);
            }
        });

        async function saveOrder(questionOrder, previousOrder) {
            if (saving) {
                return;
            }

            saving = true;
            sortable.option('disabled', true);

            const payload = new FormData();
            payload.append('_token', reorderForm.querySelector('input[name="_token"]').value);
            questionOrder.forEach(id => payload.append('question_ids[]', id));

            try {
                const response = await fetch(reorderUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: payload
                });

                if (!response.ok) {
                    throw new Error('reorder request failed');
                }

                updateBuilderState(await response.text());
                reorderNotice('success', 'ترتیب سوالات با موفقیت ذخیره شد');
            } catch (error) {
                renderOrder(previousOrder);
                reorderNotice('error', 'ذخیره ترتیب سوالات انجام نشد. لطفاً دوباره تلاش کنید');
            } finally {
                saving = false;
                sortable.option('disabled', false);
            }
        }

        reorderForms.forEach(function (form) {
            form.addEventListener('submit', async function (event) {
                event.preventDefault();

                const previousOrder = currentOrder();
                const requestedOrder = [...previousOrder];
                const rowIndex = Array.from(questionList.children).indexOf(form.closest('tr'));
                const direction = form.querySelector('button[aria-label]')?.getAttribute('aria-label');
                const targetIndex = direction === 'انتقال به بالا' ? rowIndex - 1 : rowIndex + 1;

                [requestedOrder[rowIndex], requestedOrder[targetIndex]] = [requestedOrder[targetIndex], requestedOrder[rowIndex]];
                renderOrder(requestedOrder);
                await saveOrder(requestedOrder, previousOrder);
            });
        });
    });
</script>
@endsection
