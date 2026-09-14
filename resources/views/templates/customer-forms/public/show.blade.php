<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $assignment->form->title }}</title>
    @vite(['resources/css/app.css'])
    <style>
        .customer-form-page {
            --cf-primary: #133c6d;
            --cf-primary-dark: #0e2d55;
            --cf-text: #34495e;
            --cf-muted: #73879c;
            --cf-border: #e6e9ed;
            --cf-surface: #ffffff;
            --cf-danger: #a94442;
            margin: 0;
            min-height: 100vh;
            background: #f7f7f7;
            color: var(--cf-text);
            font-family: IRANSans, Tahoma, Arial, sans-serif;
            font-size: 17px;
            line-height: 1.9;
        }

        .customer-form-page *,
        .customer-form-page *::before,
        .customer-form-page *::after { box-sizing: border-box; }

        .customer-form-shell {
            width: min(100% - 24px, 800px);
            margin-inline: auto;
            padding-block: 24px 48px;
        }

        .form-header {
            position: relative;
            overflow: hidden;
            margin-bottom: 16px;
            padding: 30px 24px;
            border-radius: 10px;
            background: var(--cf-primary);
            box-shadow: 0 5px 18px rgba(19, 60, 109, .14);
            color: #fff;
        }

        .form-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, .78);
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: .02em;
        }

        .form-eyebrow::before {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #99eae4;
            content: '';
        }

        .form-title {
            position: relative;
            z-index: 1;
            margin: 0;
            font-size: clamp(1.75rem, 5vw, 2.25rem);
            font-weight: 700;
            line-height: 1.65;
        }

        .form-description {
            position: relative;
            z-index: 1;
            max-width: 620px;
            margin: 10px 0 0;
            color: rgba(255, 255, 255, .82);
            font-size: 1.5rem;
            line-height: 2;
        }

        .form-meta {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 8px 18px;
            margin-top: 18px;
            color: rgba(255, 255, 255, .72);
            font-size: 1.3rem;
        }

        .form-feedback .alert {
            margin: 0 0 16px;
            border: 0;
            border-radius: 8px;
            box-shadow: 0 3px 12px rgba(19, 60, 109, .08);
            font-size: 1.6rem;
        }

        .questions-section {
            padding-inline: 18px;
            border: 1px solid var(--cf-border);
            border-radius: 10px;
            background: var(--cf-surface);
        }

        .question {
            min-width: 0;
            margin: 0;
            margin-inline: -18px;
            padding: 24px 18px;
            border: 0;
            background: transparent;
        }

        .question + .question { border-top: 1px solid var(--cf-border); }

        .question-heading {
            display: flex;
            width: 100%;
            align-items: flex-start;
            gap: 11px;
            margin: 0 0 10px;
            padding: 0;
            color: var(--cf-text);
            font-size: 1.22rem;
            font-weight: 700;
            line-height: 2;
        }

        .question-number {
            display: inline-grid;
            flex: 0 0 34px;
            width: 34px;
            height: 34px;
            place-items: center;
            margin-top: 1px;
            border-radius: 8px;
            background: #edf3f8;
            color: var(--cf-primary);
            font-size: 1.4rem;
            font-weight: 700;
        }

        .required-badge {
            display: inline-block;
            color: var(--cf-danger);
            font-size: 1.2rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .customer-control {
            display: block;
            width: 100%;
            min-height: 54px;
            padding: 12px 15px;
            border: 1px solid #d5dce8;
            border-radius: 8px;
            outline: 0;
            background: #fbfcfe;
            color: var(--cf-text);
            font: inherit;
            font-size: 1.5rem;
            line-height: 1.8;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        textarea.customer-control {
            min-height: 130px;
            resize: vertical;
        }

        select.customer-control { min-width: 0; max-width: 100%; cursor: pointer; }

        .customer-control:hover { border-color: #b7c1d1; }

        .customer-control:focus {
            border-color: var(--cf-primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(19, 60, 109, .13);
        }

        .customer-control.is-invalid {
            border-color: var(--cf-danger);
        }

        .choice-list {
            display: grid;
            gap: 9px;
        }

        .choice-list.boolean-list { grid-template-columns: repeat(2, minmax(0, 1fr)); }

        .choice-option {
            display: flex;
            min-height: 54px;
            align-items: center;
            gap: 11px;
            margin: 0;
            padding: 10px 13px;
            border: 1px solid #dce2eb;
            border-radius: 8px;
            background: #fbfcfe;
            color: #344054;
            cursor: pointer;
            font-size: 1.5rem;
            line-height: 1.8;
            transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
            -webkit-tap-highlight-color: transparent;
        }

        .choice-option:hover {
            border-color: #aebdca;
            background: #f7f9fb;
        }

        .choice-option:has(input:checked) {
            border-color: var(--cf-primary);
            background: #edf3f8;
            color: var(--cf-primary-dark);
            box-shadow: inset 0 0 0 1px var(--cf-primary);
        }

        .choice-option:focus-within {
            border-color: var(--cf-primary);
            box-shadow: 0 0 0 3px rgba(19, 60, 109, .13);
        }

        .choice-option input {
            flex: 0 0 20px;
            width: 20px;
            height: 20px;
            margin: 0;
            accent-color: var(--cf-primary);
            cursor: pointer;
        }

        .validation-error {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 10px;
            color: var(--cf-danger);
            font-size: 1.2rem;
            font-weight: 700;
        }

        .validation-error::before { content: '●'; font-size: .55rem; }

        .form-submit {
            margin-top: 18px;
            padding: 20px 18px;
            border: 1px solid var(--cf-border);
            border-radius: 10px;
            background: var(--cf-surface);
            box-shadow: 0 3px 12px rgba(19, 60, 109, .08);
            text-align: center;
        }

        .submit-note {
            margin: 0 0 14px;
            color: var(--cf-muted);
            font-size: 1.4rem;
        }

        .submit-button {
            width: 100%;
            min-height: 58px;
            border: 0;
            border-radius: 8px;
            background: var(--cf-primary);
            box-shadow: 0 5px 14px rgba(19, 60, 109, .2);
            color: #fff;
            cursor: pointer;
            font-family: inherit;
            font-size: 1.4rem;
            font-weight: 700;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
            -webkit-tap-highlight-color: transparent;
        }

        .submit-button:hover {
            background: var(--cf-primary-dark);
            box-shadow: 0 7px 18px rgba(19, 60, 109, .25);
            transform: translateY(-1px);
        }

        .submit-button:focus-visible {
            outline: 3px solid rgba(19, 60, 109, .25);
            outline-offset: 3px;
        }

        .submit-button:active { transform: translateY(0); }

        @media (min-width: 640px) {
            .customer-form-shell { padding-block: 48px 72px; }
            .form-header { padding: 36px 34px; }
            .questions-section { padding-inline: 26px; }
            .question { margin-inline: -26px; padding: 28px 26px; }
            .question-heading { font-size: 1.7rem; }
            .choice-list:not(.boolean-list) { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .form-submit { display: flex; align-items: center; gap: 24px; padding: 22px 26px; text-align: right; }
            .submit-note { flex: 1; margin: 0; }
            .submit-button { width: auto; min-width: 245px; padding-inline: 28px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .customer-form-page *,
            .customer-form-page *::before,
            .customer-form-page *::after { scroll-behavior: auto !important; transition: none !important; }
        }
    </style>
</head>
<body class="customer-form-page">
<main class="customer-form-shell">
    <header class="form-header">
        <div class="form-eyebrow">فرم آنلاین مشتری</div>
        <h1 class="form-title">{{ $assignment->form->title }}</h1>
        @if($assignment->form->description)
            <p class="form-description">{{ $assignment->form->description }}</p>
        @endif
        <div class="form-meta">
            <span>{{ $assignment->formVersion->questions->count() }} پرسش</span>
            <span>موارد ضروری با «الزامی» مشخص شده‌اند</span>
        </div>
    </header>

    <div class="form-feedback">
        @if($rateLimited ?? false)
            <div class="alert alert-danger" role="alert">
                درخواست‌های زیادی ارسال شده است. لطفاً کمی صبر کنید و دوباره تلاش کنید.
            </div>
        @else
            @include('templates.customer-forms.public.success')
            @if($errors->any())
                <div class="alert alert-danger" role="alert">لطفاً خطاهای مشخص‌شده را بررسی کنید.</div>
            @endif
        @endif
    </div>

    <form method="post" action="{{ route('customer-forms.public.submit', $token) }}">
        @csrf
        <div class="questions-section">
        @foreach($assignment->formVersion->questions as $question)
            @php
                $value = old('answers.'.$question->id, $answers->get($question->id));
                $hasError = $errors->has('answers.'.$question->id);
            @endphp
            <fieldset class="question{{ $hasError ? ' has-error' : '' }}">
                <div class="question-heading">
                    <span class="question-number">{{ $loop->iteration }}</span>
                    <span>
                        {{ $question->label }}
                        @if($question->is_required)<span class="required-badge">الزامی</span>@endif
                    </span>
                </div>

                @if($question->type === 'textarea')
                    <textarea name="answers[{{ $question->id }}]" class="customer-control{{ $hasError ? ' is-invalid' : '' }}" rows="4" @if($hasError) aria-invalid="true" @endif>{{ $value }}</textarea>
                @elseif($question->type === 'select')
                    <select name="answers[{{ $question->id }}]" class="customer-control{{ $hasError ? ' is-invalid' : '' }}" @if($hasError) aria-invalid="true" @endif>
                        <option value="">انتخاب کنید</option>
                        @foreach($question->options as $option)
                            <option value="{{ $option->id }}" @selected((string) $value === (string) $option->id)>{{ $option->label }}</option>
                        @endforeach
                    </select>
                @elseif($question->type === 'radio')
                    <div class="choice-list">
                        @foreach($question->options as $option)
                            <label class="choice-option">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" @checked((string) $value === (string) $option->id)>
                                <span>{{ $option->label }}</span>
                            </label>
                        @endforeach
                    </div>
                @elseif($question->type === 'checkbox')
                    <div class="choice-list">
                        @foreach($question->options as $option)
                            <label class="choice-option">
                                <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->id }}" @checked(in_array((string) $option->id, array_map('strval', (array) $value), true))>
                                <span>{{ $option->label }}</span>
                            </label>
                        @endforeach
                    </div>
                @elseif($question->type === 'boolean')
                    <div class="choice-list boolean-list">
                        <label class="choice-option"><input type="radio" name="answers[{{ $question->id }}]" value="1" @checked((string) $value === '1')> <span>بله</span></label>
                        <label class="choice-option"><input type="radio" name="answers[{{ $question->id }}]" value="0" @checked((string) $value === '0')> <span>نه</span></label>
                    </div>
                @else
                    <input type="text" name="answers[{{ $question->id }}]" value="{{ $value }}" class="customer-control{{ $hasError ? ' is-invalid' : '' }}" @if($hasError) aria-invalid="true" @endif>
                @endif

                @error('answers.'.$question->id)
                    <div class="validation-error" role="alert">{{ $message }}</div>
                @enderror
            </fieldset>
        @endforeach
        </div>

        @error('answers')
            <div class="validation-error" role="alert">{{ $message }}</div>
        @enderror

        <div class="form-submit">
            <p class="submit-note">پس از ارسال، می‌توانید با همین لینک پاسخ‌های خود را ویرایش کنید.</p>
            <button type="submit" class="submit-button">ذخیره و ارسال پاسخ‌ها</button>
        </div>
    </form>
</main>
</body>
</html>
