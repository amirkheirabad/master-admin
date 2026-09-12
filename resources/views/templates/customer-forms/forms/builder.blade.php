@extends('layouts.admin.master')

@section('content')
<div class="row"><div class="col-md-12">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>{{ $form->title }}</h3>
            <p>نسخه {{ $version?->version_number ?? '—' }} {{ $form->draft_version_id ? '(پیش‌نویس)' : '(منتشرشده)' }}</p>
        </div>
        @if($form->draft_version_id)
            <form method="post" action="{{ route('customer-forms.publish', $form) }}" onsubmit="return confirm('نسخه جدید منتشر شود؟')">
                @csrf
                <button class="btn btn-beta-solid">انتشار پیش‌نویس</button>
            </form>
        @endif
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->has('form'))<div class="alert alert-danger">{{ $errors->first('form') }}</div>@endif

    <div class="x_panel p-0">
        <table class="table">
            <thead><tr><th>ترتیب</th><th>سؤال</th><th>نوع</th><th>اجباری</th><th>عملیات</th></tr></thead>
            <tbody>
            @foreach($questions as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->label }}</td>
                    <td>{{ $item->type }}</td>
                    <td>{{ $item->is_required ? 'بله' : 'نه' }}</td>
                    <td class="d-flex">
                        @if($index > 0)
                            @php($up = $questions->pluck('id')->all())
                            @php([$up[$index - 1], $up[$index]] = [$up[$index], $up[$index - 1]])
                            <form method="post" action="{{ route('customer-forms.questions.reorder', $form) }}">
                                @csrf
                                @foreach($up as $id)<input type="hidden" name="question_ids[]" value="{{ $id }}">@endforeach
                                <button class="btn btn-sm btn-link" aria-label="انتقال به بالا">↑</button>
                            </form>
                        @endif
                        @if($index < $questions->count() - 1)
                            @php($down = $questions->pluck('id')->all())
                            @php([$down[$index], $down[$index + 1]] = [$down[$index + 1], $down[$index]])
                            <form method="post" action="{{ route('customer-forms.questions.reorder', $form) }}">
                                @csrf
                                @foreach($down as $id)<input type="hidden" name="question_ids[]" value="{{ $id }}">@endforeach
                                <button class="btn btn-sm btn-link" aria-label="انتقال به پایین">↓</button>
                            </form>
                        @endif
                        <a class="btn btn-sm btn-link" href="{{ route('customer-forms.builder', ['form' => $form, 'question' => $item->id]) }}">ویرایش</a>
                        <form method="post" action="{{ route('customer-forms.questions.destroy', $item) }}" onsubmit="return confirm('این سؤال از پیش‌نویس حذف شود؟')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-link text-danger">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @include('templates.customer-forms.questions.form')
</div></div>
@endsection
