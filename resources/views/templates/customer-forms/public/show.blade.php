<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $assignment->form->title }}</title>
    @vite(['resources/css/app.css'])
    <style>
        body { background: #f8f7fa; color: #34495e; }
        .form-card { max-width: 760px; margin: 40px auto; background: #fff; border-radius: 12px; padding: 28px; }
        .question { border-bottom: 1px solid #eee; padding: 18px 0; }
        .question:last-of-type { border-bottom: 0; }
    </style>
</head>
<body>
<main class="form-card">
    <h1 class="h3">{{ $assignment->form->title }}</h1>
    @if($assignment->form->description)
        <p class="text-muted">{{ $assignment->form->description }}</p>
    @endif

    @include('templates.customer-forms.public.success')

    <form method="post" action="{{ route('customer-forms.public.submit', $token) }}">
        @csrf
        @foreach($assignment->formVersion->questions as $question)
            @php($value = old('answers.'.$question->id, $answers->get($question->id)))
            <fieldset class="question">
                <legend class="h6">
                    {{ $question->label }}
                    @if($question->is_required)<span class="text-danger">*</span>@endif
                </legend>

                @if($question->type === 'textarea')
                    <textarea name="answers[{{ $question->id }}]" class="form-control" rows="4">{{ $value }}</textarea>
                @elseif($question->type === 'select')
                    <select name="answers[{{ $question->id }}]" class="form-control">
                        <option value="">انتخاب کنید</option>
                        @foreach($question->options as $option)
                            <option value="{{ $option->id }}" @selected((string) $value === (string) $option->id)>{{ $option->label }}</option>
                        @endforeach
                    </select>
                @elseif($question->type === 'radio')
                    @foreach($question->options as $option)
                        <label class="d-block mb-2">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" @checked((string) $value === (string) $option->id)>
                            {{ $option->label }}
                        </label>
                    @endforeach
                @elseif($question->type === 'checkbox')
                    @foreach($question->options as $option)
                        <label class="d-block mb-2">
                            <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->id }}" @checked(in_array((string) $option->id, array_map('strval', (array) $value), true))>
                            {{ $option->label }}
                        </label>
                    @endforeach
                @elseif($question->type === 'boolean')
                    <label class="ml-3"><input type="radio" name="answers[{{ $question->id }}]" value="1" @checked((string) $value === '1')> بله</label>
                    <label><input type="radio" name="answers[{{ $question->id }}]" value="0" @checked((string) $value === '0')> نه</label>
                @else
                    <input type="text" name="answers[{{ $question->id }}]" value="{{ $value }}" class="form-control">
                @endif

                @error('answers.'.$question->id)
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </fieldset>
        @endforeach

        @error('answers')
            <div class="text-danger my-3">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-primary mt-4">ذخیره و ارسال پاسخ‌ها</button>
    </form>
</main>
</body>
</html>
