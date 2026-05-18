@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="">
            <div class="row">
                <div class="col-md-6">
                    <h3>مدیریت پیام‌ها</h3>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('message.insert') }}" class="btn btn-beta-solid">
                        <i class="fa fa-plus"></i> پیام جدید
                    </a>
                </div>
            </div>

            <div class="x_panel rounded-top mt-2 p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان</th>
                            <th>متن</th>
                            <th>وضعیت</th>
                            <th>ترتیب</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $message)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $message->title }}</td>
                            <td>{{ Str::limit($message->content, 50) }}</td>
                            <td>
                                @if($message->is_active)
                                    <span class="badge badge-success">فعال</span>
                                @else
                                    <span class="badge badge-danger">غیرفعال</span>
                                @endif
                            </td>
                            <td>{{ $message->order ?? '-' }}</td>
                            <td>
                                <a href="{{ route('message.edit', $message->id) }}" class="text-success">
                                    <i class="fa fa-pencil fa-x"></i>
                                </a>
                                <a href="javascript:;" class="text-danger delete-message" data-id="{{ $message->id }}">
                                    <i class="fa fa-trash fa-x"></i>
                                </a>
                             </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('.delete-message').on('click', function() {
        let id = $(this).data('id');
        if(confirm('آیا مطمئن هستید؟')) {
            $.ajax({
                url: `/message-delete/${id}`,
                method: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(response) {
                    if(response.success) {
                        window.location.reload();
                    }
                }
            });
        }
    });
});
</script>
@endsection
