@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="">
            <div class="row">
                <div class="col-md-6">
                    <h3>ویرایش پیام</h3>
                </div>
            </div>

            <form id="messageForm" method="post" action="{{ route('message.update', $message->id) }}" class="rounded-4 x_panel">
                @csrf
                @method('PUT')

                <div class="form-group mt-8">
                    <div class="row">
                        <div class="col-md-6 col-xs-12 mb-3">
                            <label class="fw-bold">عنوان</label>
                            <input type="text" name="title" id="title" class="form-control custom-radius input-border-focus" value="{{ $message->title }}">
                            <div class="mt-1">
                                <span class="text-danger error-message" id="title_error"></span>
                            </div>
                        </div>

                        <div class="col-md-6 col-xs-12 mb-3">
                            <label class="fw-bold">وضعیت</label>
                            <select name="is_active" id="is_active" class="form-control custom-radius custom-select-input input-border-focus">
                                <option value="1" {{ $message->is_active ? 'selected' : '' }}>فعال</option>
                                <option value="0" {{ !$message->is_active ? 'selected' : '' }}>غیرفعال</option>
                            </select>
                        </div>

                        <div class="col-md-6 col-xs-12 mb-3">
                            <label class="fw-bold">ترتیب نمایش</label>
                            <input type="number" name="order" id="order" class="form-control custom-radius input-border-focus" value="{{ $message->order ?? 0 }}">
                        </div>

                        <div class="col-md-12 col-xs-12 mb-3">
                            <label class="fw-bold">متن پیام</label>
                            <textarea name="content" id="content" class="form-control custom-radius input-border-focus" rows="5">{{ $message->content }}</textarea>
                            <div class="mt-1">
                                <span class="text-danger error-message" id="content_error"></span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end col-md-12 mt-8">
                            <button type="submit" class="btn btn-beta-solid">ویرایش</button>
                            <a href="{{ route('message.list') }}" class="btn btn-beta-outline">انصراف</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$('#messageForm').on('submit', function(e) {
    e.preventDefault();

    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.html('<i class="fa fa-spinner fa-spin"></i> در حال ذخیره...');
    submitBtn.prop('disabled', true);

    $.ajax({
        url: '{{ route("message.update", $message->id) }}',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if(response.success) {
                window.location.href = response.redirect;
            }
        },
        error: function(xhr) {
            if(xhr.responseJSON && xhr.responseJSON.errors) {
                $('.error-message').html('');
                for(const [field, errors] of Object.entries(xhr.responseJSON.errors)) {
                    $(`#${field}_error`).html(errors[0]);
                }
            }
            submitBtn.html('ویرایش');
            submitBtn.prop('disabled', false);
        }
    });
});
</script>
@endsection
