@if(!empty($stats))
    <div class="dashboard-stats">
        @foreach($stats as $stat)
            <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon {{ $stat['color'] }}">
                    <i class="fa {{ $stat['icon'] }}"></i>
                </div>
                <div>
                    <div class="dashboard-stat-value">{{ number_format($stat['value']) }}</div>
                    <div class="dashboard-stat-label">{{ $stat['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
@endif
