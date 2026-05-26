@if($isAdmin || $isSeller)
    <div class="dashboard-panel">
        <h3 class="dashboard-panel-title">
            <span><i class="fa fa-file-text" style="margin-left: 8px;"></i> فاکتورهای اخیر</span>
            <a href="{{ route('factor-list') }}">مشاهده همه <i class="fa fa-angle-left"></i></a>
        </h3>

        @if($recentFactors->isNotEmpty())
            @foreach($recentFactors as $factor)
                @php
                    $priceStatus = $priceStatusLabels[$factor->price_status] ?? ['text' => 'نامشخص', 'class' => 'open'];
                @endphp
                <a href="{{ route('factor-show', $factor->id) }}" class="dashboard-ticket-item" target="_blank">
                    <div style="min-width: 0;">
                        <div class="dashboard-ticket-title">
                            {{ $factor->name ?: 'فاکتور #' . $factor->id }}
                        </div>
                        <div class="dashboard-ticket-meta">
                            @if($factor->store)
                                {{ $factor->store->store_name }} ·
                            @endif
                            {{ number_format($factor->price) }} تومان ·
                            {{ Verta($factor->factor_date)->format('Y/m/d') }}
                        </div>
                    </div>
                    <span class="dashboard-badge {{ $priceStatus['class'] }}">{{ $priceStatus['text'] }}</span>
                </a>
            @endforeach
        @else
            <div class="dashboard-empty">
                <i class="fa fa-inbox"></i>
                فاکتوری برای نمایش وجود ندارد
            </div>
        @endif
    </div>
@endif
