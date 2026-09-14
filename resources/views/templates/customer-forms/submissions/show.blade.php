@extends('layouts.admin.master')

@section('css')
    <style>
        .submission-detail .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .submission-detail .page-heading h3 { margin: 2px 0 0; color: #133c6d; }

        .submission-detail .page-eyebrow {
            color: #73879c;
            font-size: 12px;
        }

        .submission-detail .submission-summary {
            padding: 22px;
            border-top: 3px solid #133c6d;
        }

        .submission-detail .section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 18px;
            color: #133c6d;
            font-size: 16px;
            font-weight: 600;
        }

        .submission-detail .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .submission-detail .summary-item {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: 12px;
            padding: 14px;
            border: 1px solid #e6e9ed;
            border-radius: 8px;
            background: #f8f7fa;
        }

        .submission-detail .summary-icon {
            display: inline-flex;
            flex: 0 0 40px;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #e9f0f7;
            color: #133c6d;
            font-size: 17px;
        }

        .submission-detail .summary-content { min-width: 0; }

        .submission-detail .summary-label {
            display: block;
            margin-bottom: 3px;
            color: #73879c;
            font-size: 12px;
        }

        .submission-detail .summary-value {
            display: block;
            overflow-wrap: anywhere;
            color: #34495e;
            font-size: 14px;
            font-weight: 600;
        }

        .submission-detail .version-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 8px;
            background: #e8ffee;
            color: #0d7525;
        }

        .submission-detail .answers-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 26px 0 12px;
        }

        .submission-detail .answers-heading .section-title { margin: 0; }

        .submission-detail .question-count {
            padding: 4px 10px;
            border-radius: 8px;
            background: #e6eaff;
            color: #1c00dc;
            font-size: 12px;
        }

        .submission-detail .answer-list {
            display: grid;
            gap: 12px;
        }

        .submission-detail .answer-card {
            padding: 18px 20px;
            border: 1px solid #e6e9ed;
            border-right: 4px solid #133c6d;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(19, 60, 109, .05);
        }

        .submission-detail .answer-question {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
        }

        .submission-detail .answer-number {
            display: inline-flex;
            flex: 0 0 28px;
            width: 28px;
            height: 28px;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #133c6d;
            color: #fff;
            font-size: 12px;
        }

        .submission-detail .answer-question h5 {
            margin: 2px 0 0;
            color: #133c6d;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.8;
        }

        .submission-detail .answer-value {
            min-height: 44px;
            padding: 11px 14px;
            border-radius: 8px;
            background: #f8f7fa;
            color: #34495e;
            font-size: 14px;
            line-height: 2;
            overflow-wrap: anywhere;
            white-space: pre-wrap;
        }

        .submission-detail .answer-value.is-empty { color: #9aa7b5; }

        @media (max-width: 767px) {
            .submission-detail .summary-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 576px) {
            .submission-detail .page-heading { align-items: flex-start; }
            .submission-detail .page-heading h3 { font-size: 19px; line-height: 1.7; }
            .submission-detail .submission-summary { padding: 16px; }
            .submission-detail .answer-card { padding: 15px 14px; }
            .submission-detail .answer-value { padding: 10px 12px; }
        }
    </style>
@endsection

@section('content')
<div class="row submission-detail">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="page-heading">
            <div>
                <div class="page-eyebrow">جزئیات پاسخ فرم مشتری</div>
                <h3>{{ $submission->assignment->form->title }}</h3>
            </div>
            <a href="{{ route('customer-forms.submissions.index') }}" class="btn btn-beta-outline">
                <i class="fa fa-arrow-right ml-1"></i>
                بازگشت
            </a>
        </div>

        <section class="x_panel rounded-top submission-summary">
            <h4 class="section-title"><i class="fa fa-info-circle"></i> اطلاعات ارسال‌کننده</h4>
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-icon"><i class="fa fa-shopping-cart"></i></span>
                    <div class="summary-content">
                        <span class="summary-label">فروشگاه</span>
                        <span class="summary-value">{{ $submission->assignment->store->store_name }}</span>
                    </div>
                </div>

                <div class="summary-item">
                    <span class="summary-icon"><i class="fa fa-user"></i></span>
                    <div class="summary-content">
                        <span class="summary-label">مشتری</span>
                        <span class="summary-value">{{ $submission->assignment->store->user?->name ?? '—' }}</span>
                    </div>
                </div>

                <div class="summary-item">
                    <span class="summary-icon"><i class="fa fa-clone"></i></span>
                    <div class="summary-content">
                        <span class="summary-label">نسخه فرم</span>
                        <span class="summary-value version-badge">نسخه {{ $submission->formVersion->version_number }}</span>
                    </div>
                </div>
            </div>
        </section>

        <div class="answers-heading">
            <h4 class="section-title"><i class="fa fa-list-alt"></i> پاسخ‌های ثبت‌شده</h4>
            <span class="question-count">{{ $submission->formVersion->questions->count() }} پرسش</span>
        </div>

        <div class="answer-list">
            @foreach($submission->formVersion->questions as $question)
                @php
                    $answer = $answers->get($question->id);
                    $display = '—';
                    if ($answer) {
                        if ($question->type === 'boolean') {
                            $display = $answer->value ? 'بله' : 'نه';
                        } elseif (in_array($question->type, ['select', 'radio', 'checkbox'], true)) {
                            $ids = array_map('intval', (array) $answer->value);
                            $display = $question->options->whereIn('id', $ids)->pluck('label')->implode('، ');
                        } else {
                            $display = $answer->value;
                        }
                    }
                @endphp
                <article class="answer-card">
                    <div class="answer-question">
                        <span class="answer-number">{{ $loop->iteration }}</span>
                        <h5>{{ $question->label }}</h5>
                    </div>
                    <div class="answer-value{{ $answer ? '' : ' is-empty' }}">{{ $display }}</div>
                </article>
            @endforeach
        </div>
    </div>
</div>
@endsection
