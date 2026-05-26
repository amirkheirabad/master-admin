<div class="dashboard-panel">
    <h3 class="dashboard-panel-title">
        <span><i class="fa fa-life-ring" style="margin-left: 8px;"></i> آخرین تیکت‌ها</span>
        @if($isAdmin || $isSeller)
            <a href="{{ route('list_tickets') }}">مشاهده همه <i class="fa fa-angle-left"></i></a>
        @endif
    </h3>

    @if($recentTickets->isNotEmpty())
        @foreach($recentTickets as $ticket)
            @php
                $status = $ticketStatusLabels[$ticket->status] ?? ['text' => 'نامشخص', 'class' => 'open'];
            @endphp
            <a href="{{ route('show_ticket', $ticket->id) }}" class="dashboard-ticket-item">
                <div class="dashboard-ticket-body">
                    <div class="dashboard-ticket-title">{{ $ticket->title }}</div>
                    <div class="dashboard-ticket-meta">
                        @if($isAdmin && $ticket->store)
                            {{ $ticket->store->store_name }} ·
                        @endif
                        {{ Verta($ticket->updated_at)->format('Y/m/d') }}
                    </div>
                </div>
                <span class="dashboard-badge {{ $status['class'] }}">{{ $status['text'] }}</span>
            </a>
        @endforeach
    @else
        <div class="dashboard-empty">
            <i class="fa fa-inbox"></i>
            تیکتی برای نمایش وجود ندارد
        </div>
    @endif
</div>
