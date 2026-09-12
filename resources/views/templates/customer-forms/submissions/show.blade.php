@extends('layouts.admin.master')

@section('content')
<div class="row"><div class="col-md-12">
    <h3>{{ $submission->assignment->form->title }}</h3>
    <p>فروشگاه: {{ $submission->assignment->store->store_name }} — مشتری: {{ $submission->assignment->store->user?->name }} — نسخه: {{ $submission->formVersion->version_number }}</p>
    <div class="x_panel">
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
        <div class="mb-4"><strong>{{ $question->label }}</strong><div class="mt-2">{{ $display }}</div></div>
    @endforeach
    </div>
    <a href="{{ route('customer-forms.submissions.index') }}" class="btn btn-beta-outline">بازگشت</a>
</div></div>
@endsection
