@extends('layouts.admin.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/ticket-show.css') }}">
@endsection

@section('js')
    <script src="{{ asset('/js/select2.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2.js') }}"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <script src="{{ asset('/js/ticket-show.js') }}"></script>
@endsection

@section('nav')
    <a href="{{ route('list_tickets') }}">
        <i class="fa fa-chevron-right mt-8 mr-1 fa-x"></i>
    </a>
    <div class="nav toggle hide-from-md">
        <h4>تیکت شماره {{ $ticket->id }}</h4>
    </div>
    <ul class="nav navbar-nav navbar-right hide-from-md">
        <li></li>
        <li>
            @if(auth()->user()->hasanyRole('admin'))
            <div class="dropdown-custom position-relative hide-from-md">
                <div class="pointer mt-2 ml-1 mr-1 p-5" onclick="toggleCustomDropdown(this)">
                    <img src="{{ asset('/icons/Menu_kebab.svg') }}">
                </div>
                <div class="dropdown-options" style="display: none; position: absolute; background: white; border: 1px solid #ddd; border-radius: 4px; min-width: 200px; z-index: 1000; margin-top: 8px; left: 1%;">
                    <div class="dropdown-option pointer p-5 btn-green-light" data-ticket-id="{{ $ticket->id }}">
                        تغییر وضعیت
                    </div>
                    @if($ticket->recipient_type === 'store' && $ticket->store)
                        <div class="dropdown-option pointer p-5 btn-green-light">
                            <a href="{{ route('edit_store' , $ticket->store->id) }}" style="color: black">
                                <i class="fa fa-info-circle fa-x"></i> اطلاعات فروشگاه
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </li>
    </ul>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <div class="hide-on-mobile">
                        <h3>تیکت شماره {{ $ticket->id }}</h3>
                    </div>
                    <div class="d-flex gap-2 hide-on-mobile" style="gap: 10px;">
                         @if(auth()->user()->hasanyRole('admin'))
                         @if($ticket->recipient_type === 'store' && $ticket->store)
                        <a href="{{ route('edit_store' , $ticket->store->id) }}" class="btn btn-beta-outline hide-on-mobile">
                            <i class="fa fa-info-circle fa-x"></i> اطلاعات فروشگاه
                        </a>
                        @endif
                        @endif

                         @if(auth()->user()->hasanyRole('admin'))
                        <div class="dropdown-custom position-relative hide-on-mobile">
                            <div class="pointer btn btn-beta-solid" onclick="toggleCustomDropdown(this)">
                                تغییر وضعیت <i class="fa fa-chevron-down"></i>
                            </div>
                            <div class="dropdown-options" style="display: none; position: absolute; background: white; border: 1px solid #ddd; border-radius: 4px; min-width: 200px; z-index: 1000; margin-top: 8px; left: 1%;">
                                <div class="dropdown-option pointer p-5 btn-green-light" onclick="openModalAndClose(this, {{ $ticket->id }}, 'فروشگاه نمونه', 0, 'در حال بررسی توسط ایندکس')" data-value="in_progress">
                                    در حال بررسی توسط ایندکس
                                </div>
                                <div class="dropdown-option pointer p-5 btn-green-light" onclick="openModalAndClose(this, {{ $ticket->id }}, 'فروشگاه نمونه', 1, 'منتظر پاسخ فروشگاه')" data-value="pending">
                                    منتظر پاسخ فروشگاه
                                </div>
                                <div class="dropdown-option pointer p-5 btn-green-light" onclick="openModalAndClose(this, {{ $ticket->id }}, 'فروشگاه نمونه', 2, 'بسته شده')" data-value="closed">
                                    بسته شده
                                </div>
                                <div class="dropdown-option pointer p-5 btn-green-light" onclick="openModalAndClose(this, {{ $ticket->id }}, 'فروشگاه نمونه', 3, 'ارجاع به واحد فنی')" data-value="closed">
                                    ارجاع به واحد فنی
                                </div>
                                <div class="dropdown-option pointer p-5 btn-green-light" onclick="openModalAndClose(this, {{ $ticket->id }}, 'فروشگاه نمونه', 4, ' ارجاع به واحد گرافیک دیزاین')" data-value="closed">
                                   ارجاع به واحد گرافیک دیزاین
                            </div>

                            </div>
                        </div>
                             @endif
                    </div>
                </div>
            </div>
            <div id="chatbox" class="x_panel h-580 mt-8 d-flex flex-column bg-chatbox rounded-3 p-5">
                <div class="panel-content">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3>{{ $ticket->title }}</h3>
                            <h5>
                                {{ $ticket->recipient_type === 'store' ? 'فروشگاه ' . $ticket->store?->store_name : 'کاربر ' . $ticket->user?->name }}
                            </h5>                        </div>
                        <div>
                            <h5 class="mb-8">
                                @if($ticket->status == 0)
                                    <span class="bg-warning p-2 custom-radius">
                                    در حال برسی توسط ایندکس
                                </span>
                                @elseif($ticket->status == 1)
                                    <span class="bg-red-new p-2 custom-radius">
                                    منتظر پاسخ فروشگاه
                                </span>
                                @elseif($ticket->status == 2)
                                    <span class="bg-jade p-2 custom-radius mr-5">
                                    بسته شده
                                </span>
                                @elseif($ticket->status == 3)
                                    <span class="bg-new p-2 custom-radius mr-5">
                                    ارجاع به واحد فنی
                                </span>
                                @elseif($ticket->status == 4)
                                    <span class="bg-new p-2 custom-radius mr-5">
                                    ارجاع به واحد گرافیک دیزاین
                                </span>
                                @endif
                            </h5>
                            <h5 class="text-left fa-number">{{ Verta($ticket->created_at)->format(' %d %B  %Y') }}</h5>
                        </div>
                    </div>
                </div>
                <!-- بخش نمایش پیام‌ها (Chat Container) -->
                <div class="chat-container p-1" style="overflow-y: auto; margin: 15px 0;">
                    @forelse($ticket->messages->sortBy('created_at') as $message)
                        @if($message->sender_type == 0)
                            {{-- پیام فروشگاه (مشتری) - سمت راست با استایل مشابه ادمین --}}
                            <div class="message-wrapper message-store mt-4 d-flex justify-content-start">
                                <div style="max-width: 100%;">
                                    {{-- حباب پیام --}}
                                    <div class="message-bubble bg-white border p-3 shadow-sm custom-radius" style="display: flex; flex-direction: column; min-height: 80px; max-width: 550px; width: auto;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="">
                                                <img src="{{ asset('/images/men.png') }}" style="width: 22px">
                                                <i class="fa fa-store"></i> {{ $ticket->store->store_name ?? 'فروشگاه' }}
                                            </p>
                                            <small class="text-muted mr-2 fa-number">
                                                {{ Verta($message->created_at)->format('H:i - Y/m/d') }}
                                            </small>
                                        </div>
                                        <div class="message-text" style="word-wrap: break-word; margin-top: auto;">
                                            {!! nl2br(e($message->messages)) !!}
                                        </div>
                                    </div>

                                    {{-- بخش پیوست‌ها - جدا از حباب پیام --}}
                                    @if($message->attachments)
                                        <div class="attachments-wrapper p-2 custom-radius" style="max-width: 550px; margin-left: 0">
                                            @php
                                                $attachments = is_string($message->attachments) ? json_decode($message->attachments, true) : $message->attachments;
                                            @endphp
                                            @if(is_array($attachments) && count($attachments) > 0)
                                                @foreach($attachments as $attachment)
                                                    @php
                                                        $url = asset('storage/' . $attachment);
                                                        $fileName = basename($attachment);
                                                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                                                        $filePath = storage_path('app/public/' . $attachment);
                                                        $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                                        $fileSizeFormatted = $fileSize ? number_format($fileSize / 1024, 1) . ' KB' : '';
                                                        if ($fileSize > 1048576) {
                                                            $fileSizeFormatted = number_format($fileSize / 1048576, 1) . ' MB';
                                                        }
                                                    @endphp
                                                    <a href="{{ $url }}" download class="attachment-item bg-file mt-2 p-3 rounded custom-radius text-decoration-none" style="display: block; cursor: pointer;">
                                                        <div class="d-flex align-items-center" style="gap: 8px;">
                                                            {{-- آیکون --}}
                                                            <div class="file-icon d-flex justify-content-center align-items-center flex-shrink-0" style="width: 40px; height: 40px; background: white; border-radius: 6px;">
                                                                @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                                    <i class="fa fa-image" style="font-size: 20px;"></i>
                                                                @elseif(in_array($fileExtension, ['pdf']))
                                                                    <i class="fa fa-file-pdf-o" style="font-size: 20px;"></i>
                                                                @elseif(in_array($fileExtension, ['doc', 'docx']))
                                                                    <i class="fa fa-file-word-o" style="font-size: 20px;"></i>
                                                                @elseif(in_array($fileExtension, ['xls', 'xlsx']))
                                                                    <i class="fa fa-file-excel-o" style="font-size: 20px;"></i>
                                                                @elseif(in_array($fileExtension, ['zip', 'rar', '7z']))
                                                                    <i class="fa fa-file-archive-o" style="font-size: 20px;"></i>
                                                                @else
                                                                    <i class="fa fa-file-o" style="font-size: 20px;"></i>
                                                                @endif
                                                            </div>

                                                            {{-- اطلاعات فایل --}}
                                                            <div class="flex-grow-1" style="min-width: 0;">
                                                                <div class="text-secondary" style="text-align: right; word-wrap: break-word; word-break: break-all; font-size: 10px; line-height: 1.3;">
                                                                    {{ $fileName }}
                                                                </div>
                                                                @if($fileSizeFormatted)
                                                                    <div class="text-muted" style="font-size: 9px; margin-top: 2px; text-align: right;"> {{ $fileSizeFormatted }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                        {{-- پیام ادمین (پشتیبانی) - سمت چپ --}}
                        <div class="message-wrapper message-admin mt-4 d-flex justify-content-end">
                            <div style="display: flex; align-items: flex-end; gap: 6px;">

                                @if(auth()->user()->hasRole('admin'))
                                @php
                                    $canEdit = $message->created_at->diffInHours(now()) < 48;
                                @endphp
                                <button class="edit-msg-btn"
                                    data-toggle="modal"
                                    data-target="#editModal"
                                    data-id="{{ $message->id }}"
                                    data-message="{{ $message->messages }}"
                                    aria-label="ویرایش پیام"
                                    @if(!$canEdit) disabled title="بعد از 48 ساعت امکان ویرایش وجود ندارد" @endif
                                    style="{{ !$canEdit ? 'opacity: 0.3; cursor: not-allowed;' : '' }}">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                @endif

                                <div style="max-width: 100%;">
                                    {{-- حباب پیام --}}
                                    <div class="message-bubble bg-white border p-3 shadow-sm custom-radius" style="display: flex; flex-direction: column; min-height: 80px; max-width: 550px; width: auto;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="">
                                                <img src="{{ asset('/images/men.png') }}" style="width: 22px">
                                                <i class="fa fa-headset"></i> پشتیبانی ایندکس
                                            </p>
                                            <small class="text-muted mr-2 fa-number">
                                                {{ Verta($message->created_at)->format('H:i - Y/m/d') }}
                                            </small>
                                        </div>
                                        <div class="message-text" style="word-wrap: break-word; margin-top: auto;">
                                            {!! nl2br(e($message->messages)) !!}
                                        </div>
                                        @if($message->created_at != $message->updated_at)
                                            <small class="text-muted" style="font-size: 10px; margin-top: 4px; display: block; text-align: left;">
                                                ویرایش شده
                                            </small>
                                        @endif
                                    </div>

                                    {{-- بخش پیوست‌ها - جدا از حباب پیام --}}
                                    @if($message->attachments)
                                        <div class="attachments-wrapper p-2 custom-radius" style="max-width: 550px; margin-left: 0;">
                                            @php
                                                $attachments = is_string($message->attachments) ? json_decode($message->attachments, true) : $message->attachments;
                                            @endphp
                                            @if(is_array($attachments) && count($attachments) > 0)
                                                @foreach($attachments as $attachment)
                                                    @php
                                                        $url = asset('storage/' . $attachment);
                                                        $fileName = basename($attachment);
                                                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                                                        $filePath = storage_path('app/public/' . $attachment);
                                                        $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                                        $fileSizeFormatted = $fileSize ? number_format($fileSize / 1024, 1) . ' KB' : '';
                                                        if ($fileSize > 1048576) {
                                                            $fileSizeFormatted = number_format($fileSize / 1048576, 1) . ' MB';
                                                        }
                                                    @endphp
                                                    <a href="{{ $url }}" download class="attachment-item bg-file mt-2 p-3 rounded custom-radius text-decoration-none" style="display: block; cursor: pointer;">
                                                        <div class="d-flex align-items-center" style="gap: 8px;">
                                                            <div class="file-icon d-flex justify-content-center align-items-center flex-shrink-0" style="width: 40px; height: 40px; background: white; border-radius: 6px;">
                                                                @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                                    <i class="fa fa-image" style="font-size: 20px;"></i>
                                                                @elseif(in_array($fileExtension, ['pdf']))
                                                                    <i class="fa fa-file-pdf-o" style="font-size: 20px;"></i>
                                                                @elseif(in_array($fileExtension, ['doc', 'docx']))
                                                                    <i class="fa fa-file-word-o" style="font-size: 20px;"></i>
                                                                @elseif(in_array($fileExtension, ['xls', 'xlsx']))
                                                                    <i class="fa fa-file-excel-o" style="font-size: 20px;"></i>
                                                                @elseif(in_array($fileExtension, ['zip', 'rar', '7z']))
                                                                    <i class="fa fa-file-archive-o" style="font-size: 20px;"></i>
                                                                @else
                                                                    <i class="fa fa-file-o" style="font-size: 20px;"></i>
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1" style="min-width: 0;">
                                                                <div class="text-secondary" style="text-align: right; word-wrap: break-word; word-break: break-all; font-size: 10px; line-height: 1.3;">
                                                                    {{ $fileName }}
                                                                </div>
                                                                @if($fileSizeFormatted)
                                                                    <div class="text-muted" style="font-size: 9px; margin-top: 2px; text-align: right;">{{ $fileSizeFormatted }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                        @endif
                    @empty
                    @endforelse
                </div>
                @if($ticket->status != 2)
                    @if(auth()->user()->hasRole('admin'))
                    <div class="mt-auto">
                        <form id="replyForm" action="{{ route('ticket_reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="ticket_id" value="{{ $ticket->id }}">
                            {{-- جواب‌های آماده - فقط ادمین --}}
                            @if(auth()->user()->hasRole('admin'))
                            <div id="quickReplyWrapper">

                                {{-- dropdown - بالای chip، داخل flow عادی --}}
                                <div id="quickReplyDropdown" style="display:none; background:white; border:1px solid #ddd; border-radius:8px; margin-bottom:6px; overflow:hidden;">
                                    <div style="padding:7px 10px; border-bottom:1px solid #f0f0f0; background:#fafafa;">
                                        <input id="qrSearchInput" type="text" placeholder="جستجو..."
                                            oninput="filterQuickReplies(this.value)"
                                            style="width:100%; border:1px solid #e0e0e0; border-radius:6px; padding:5px 10px;
                                                font-size:12px; outline:none; font-family:Tahoma,sans-serif; text-align:right; background:white;">
                                    </div>
                                    <div id="quickReplyList" style="max-height:180px; overflow-y:auto;">
                                        <div id="quickReplyLoading" class="p-2 text-muted text-center">
                                            <i class="fa fa-spinner fa-spin"></i> در حال بارگذاری...
                                        </div>
                                    </div>
                                </div>

                                {{-- chip کلیک‌پذیر --}}
                                <div id="qrChip" onclick="toggleQuickReply()"
                                    style="display:flex; align-items:center; gap:8px; background:#f8f9fa; border:1px solid #e0e0e0;
                                        border-radius:8px; padding:7px 12px; cursor:pointer; margin-bottom:6px; transition:background .15s;">
                                    <i class="fa fa-bolt" style="font-size:12px; color:#888;"></i>
                                    <span id="quickReplyLabel" style="flex:1; font-size:12.5px; color:#666;">انتخاب جواب آماده...</span>
                                    <i class="fa fa-chevron-down" id="qrChevron" style="font-size:11px; color:#aaa; transition:transform .2s;"></i>
                                </div>

                            </div>
                            @endif
                            <div class="file-preview-wrapper" id="filePreviewWrapper" style="display: none;">
                                <div class="file-preview-header">
                                    <button type="button" class="remove-all-files-btn" id="removeAllFilesBtn" title="حذف همه فایل‌ها">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="file-preview-content" id="filePreviewContent">
                                    <!-- فایل‌های انتخاب شده اینجا نمایش داده می‌شه -->
                                </div>
                            </div>
                            <div class="search-container custom-radius" id="searchContainer">
                            <textarea name="message" id="messageInput" class="search-input" placeholder="پیام خود را وارد کنید..." rows="1"></textarea>                                <button type="button" class="search-button" id="attachButton">
                                    <img src="{{ asset('/icons/Attachment.svg') }}" style="width: 22px">
                                </button>
                                <input type="file" name="attachments[]" id="fileInput"     accept=".jpg,.jpeg,.png,.pdf,.gif,.zip,.webp,.svg,.ico"
                                       style="display: none;" multiple>
                                <button type="submit" class="search-button">
                                    <img src="{{ asset('/icons/send 1.svg') }}" style="width: 22px">
                                </button>
                            </div>
                        </form>
                    </div>
                    <span id="message_error" class="text-danger"></span>
                    <span id="attachments_error" class="text-danger"></span>
                    @endif
                        @if(auth()->user()->hasRole('seller'))
                        <div class="mt-auto">
                        <form id="replyFormUser" action="{{ route('ticket_store_reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="ticket_id" value="{{ $ticket->id }}">
                            <div class="file-preview-wrapper" id="filePreviewWrapper" style="display: none;">
                                <div class="file-preview-header">
                                    <button type="button" class="remove-all-files-btn" id="removeAllFilesBtn" title="حذف همه فایل‌ها">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="file-preview-content" id="filePreviewContent">
                                    <!-- فایل‌های انتخاب شده اینجا نمایش داده می‌شه -->
                                </div>
                            </div>

                            <div class="search-container custom-radius" id="searchContainer">
                                <textarea type="text" name="message" id="messageInput" class="search-input " placeholder="پیام خود را وارد کنید..." rows="1"></textarea>
                                <button type="button" class="search-button" id="attachButton">
                                    <img src="{{ asset('/icons/Attachment.svg') }}" style="width: 22px">
                                </button>
                                <input type="file" name="attachments[]" id="fileInput" style="display: none;"     accept=".jpg,.jpeg,.png,.pdf,.gif,.zip,.webp,.svg,.ico"
                                       multiple>
                                <button type="submit" class="search-button">
                                    <img src="{{ asset('/icons/send 1.svg') }}" style="width: 22px">
                                </button>
                            </div>
                        </form>
                    </div>
                    <span id="message_error" class="text-danger"></span>
                    <span id="attachments_error" class="text-danger"></span>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @include('templates.ticket.Modal.modal-show')
@endsection





