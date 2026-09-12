<div class="x_panel mt-4">
    <h4>{{ $question ? 'ویرایش سؤال' : 'سؤال جدید' }}</h4>
    <form method="post" action="{{ $question ? route('customer-forms.questions.update', $question) : route('customer-forms.questions.store', $form) }}">
        @csrf
        @if($question) @method('PUT') @endif
        <div class="form-group">
            <label>متن سؤال</label>
            <input name="label" class="form-control" value="{{ old('label', $question?->label) }}" required>
            @error('label')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="form-group mt-3">
            <label>نوع پاسخ</label>
            <select name="type" class="form-control">
                @foreach(['text' => 'متن کوتاه', 'textarea' => 'متن بلند', 'select' => 'فهرست انتخابی', 'radio' => 'انتخاب تکی', 'checkbox' => 'انتخاب چندتایی', 'boolean' => 'بله / نه'] as $type => $label)
                    <option value="{{ $type }}" @selected(old('type', $question?->type ?? 'text') === $type)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <label class="mt-3"><input type="checkbox" name="is_required" value="1" @checked(old('is_required', $question?->is_required))> پاسخ اجباری باشد</label>
        <div class="mt-3">
            <label>گزینه‌ها؛ هر گزینه در یک سطر</label>
            <textarea name="options_text" class="form-control" rows="5">{{ old('options_text', $question?->options->pluck('label')->implode(chr(10))) }}</textarea>
        </div>
        <div class="mt-4">
            <button class="btn btn-beta-solid">ذخیره سؤال</button>
            @if($question)<a href="{{ route('customer-forms.builder', $form) }}" class="btn btn-beta-outline">انصراف</a>@endif
        </div>
    </form>
</div>
