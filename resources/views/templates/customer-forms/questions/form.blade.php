@extends('layouts.admin.master')

@section('content')
<div class="row"><div class="col-md-12">
    <style>
            .question-option-row {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 10px;
            }

            .question-option-row .form-control { flex: 1; }

            .question-option-remove {
                min-width: 42px;
                min-height: 38px;
            }
    </style>

    <div class="x_panel mt-2">
            <h3>{{ $question ? 'ویرایش سؤال' : 'سؤال جدید' }}</h3>
            <p class="text-muted">{{ $form->title }}</p>
            <form method="post" action="{{ $question ? route('customer-forms.questions.update', $question) : route('customer-forms.questions.store', $form) }}">
                @csrf
                @if($question) @method('PUT') @endif
                <div class="form-group">
                    <label>متن سؤال</label>
                    <input name="label" class="form-control custom-radius input-border-focus" value="{{ old('label', $question?->label) }}" required>
                    @error('label')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mt-3">
                    <label>نوع پاسخ</label>
                    <select name="type" class="form-control custom-radius custom-select-input input-border-focus">
                        @foreach(['text' => 'متن کوتاه', 'textarea' => 'متن بلند', 'select' => 'فهرست انتخابی', 'radio' => 'انتخاب تکی', 'checkbox' => 'انتخاب چندتایی', 'boolean' => 'بله / نه'] as $type => $label)
                            <option value="{{ $type }}" @selected(old('type', $question?->type ?? 'text') === $type)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="mt-3"><input type="checkbox" name="is_required" value="1" @checked(old('is_required', $question?->is_required))> پاسخ اجباری باشد</label>
                @php($optionsText = old('options_text', $question?->options->pluck('label')->implode(chr(10)) ?? ''))
                @php($optionValues = array_values(array_filter(preg_split('/\r\n|\r|\n/', (string) $optionsText), fn ($option) => trim($option) !== '')))
                <div class="mt-3" id="question-options-section" hidden>
                    <label>گزینه‌ها</label>
                    <div id="question-options-list" data-options-list>
                        @foreach($optionValues ?: [''] as $option)
                            <div class="question-option-row" data-option-row>
                                <input type="text" class="form-control custom-radius input-border-focus" data-option-input value="{{ $option }}" maxlength="255" aria-label="متن گزینه">
                                <button type="button" class="btn btn-outline-danger question-option-remove" data-option-remove title="حذف گزینه" aria-label="حذف گزینه">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <textarea name="options_text" id="question-options-text" hidden>{{ $optionsText }}</textarea>
                    <button type="button" class="btn btn-beta-outline mt-1" id="add-question-option">+ افزودن گزینه</button>
                </div>
                <div class="mt-4">
                    <button class="btn btn-beta-solid">{{ $question ? 'ویرایش' : 'تایید' }}</button>
                    <a href="{{ route('customer-forms.builder', $form) }}" class="btn btn-beta-outline">
                        انصراف
                    </a>
                </div>
            </form>
    </div>

    <script>
            document.addEventListener('DOMContentLoaded', function () {
                const typeInput = document.querySelector('select[name="type"]');
                const optionsSection = document.getElementById('question-options-section');
                const optionsList = document.getElementById('question-options-list');
                const optionsText = document.getElementById('question-options-text');
                const addOption = document.getElementById('add-question-option');
                const questionForm = typeInput?.closest('form');

                function createOption() {
                    const row = document.createElement('div');
                    row.className = 'question-option-row';
                    row.dataset.optionRow = '';
                    row.innerHTML = '<input type="text" class="form-control custom-radius input-border-focus" data-option-input maxlength="255" aria-label="متن گزینه">' +
                        '<button type="button" class="btn btn-outline-danger question-option-remove" data-option-remove title="حذف گزینه" aria-label="حذف گزینه"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                    optionsList.append(row);
                    row.querySelector('input').focus();
                }

                function toggleOptions() {
                    optionsSection.hidden = !['select', 'radio', 'checkbox'].includes(typeInput.value);
                }

                typeInput.addEventListener('change', toggleOptions);
                addOption.addEventListener('click', createOption);
                optionsList.addEventListener('click', function (event) {
                    event.target.closest('[data-option-remove]')?.closest('[data-option-row]')?.remove();
                });
                questionForm.addEventListener('submit', function () {
                    optionsText.value = optionsSection.hidden
                        ? ''
                        : Array.from(optionsList.querySelectorAll('[data-option-input]')).map(input => input.value).join('\n');
                });

                toggleOptions();
            });
    </script>
</div></div>
@endsection
