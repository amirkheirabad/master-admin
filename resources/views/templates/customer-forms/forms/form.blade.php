@extends('layouts.admin.master')

@section('content')
<div class="row"><div class="col-md-12">
    <h3>{{ isset($form) ? 'ویرایش فرم' : 'فرم جدید' }}</h3>
    <form method="post" action="{{ isset($form) ? route('customer-forms.update', $form) : route('customer-forms.store') }}" class="x_panel mt-2">
        @csrf
        @isset($form) @method('PUT') @endisset
        <div class="form-group">
            <label for="title">عنوان فرم</label>
            <input id="title" name="title" class="form-control custom-radius input-border-focus" value="{{ old('title', $form->title ?? '') }}" required>
            @error('title')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="form-group mt-3">
            <label for="description">توضیح کوتاه</label>
            <textarea id="description" name="description" class="form-control custom-radius input-border-focus" rows="3">{{ old('description', $form->description ?? '') }}</textarea>
        </div>
        <label class="mt-3"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $form->is_active ?? true))> فرم فعال باشد</label>
        <div class="mt-4">
            <button class="btn btn-beta-solid">ذخیره</button>
            <a href="{{ route('customer-forms.index') }}" class="btn btn-beta-outline">انصراف</a>
        </div>
    </form>
</div></div>
@endsection
