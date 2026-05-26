@if($isSeller && $messages->isNotEmpty())
    @foreach($messages as $message)
        <div class="dashboard-alert">
            <i class="fa fa-bullhorn"></i>
            <div>
                <span class="dashboard-alert-title">{{ $message->title }}</span>
                <span class="dashboard-alert-text">{{ $message->content }}</span>
            </div>
        </div>
    @endforeach
@endif
