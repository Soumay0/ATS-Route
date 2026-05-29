@extends('layouts.dashboard')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
<div class="grid gap-6 xl:grid-cols-3">
    <div class="glass-panel rounded-[2rem] p-6 xl:col-span-2">
        <h2 class="text-2xl font-semibold text-white">Route and waypoint analytics</h2>
        <canvas id="reportChart" class="mt-6" height="120"></canvas>
    </div>
    <div class="glass-panel rounded-[2rem] p-6">
        <h2 class="text-2xl font-semibold text-white">Recent routes</h2>
        <div class="mt-5 space-y-3">
            @foreach($recentRoutes as $route)
                <div class="rounded-2xl border border-border-light bg-white/5 p-4">
                    <div class="text-white">{{ $route->route_name }}</div>
                    <div class="text-sm text-text-secondary">{{ $route->status }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('reportChart');
    if (!canvas || typeof Chart === 'undefined') return;

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: ['Waypoints', 'Aids', 'Routes'],
            datasets: [{
                label: 'Distribution',
                data: [{{ $waypointTypes->sum('total') }}, {{ $aidTypes->sum('total') }}, {{ $routeStatuses->sum('total') }}],
                backgroundColor: ['#00C2FF', '#22D3EE', '#2EE6A6'],
            }],
        },
        options: {
            plugins: { legend: { labels: { color: '#E5E7EB' } } },
            scales: {
                x: { ticks: { color: '#94A3B8' }, grid: { color: 'rgba(255,255,255,0.06)' } },
                y: { ticks: { color: '#94A3B8' }, grid: { color: 'rgba(255,255,255,0.06)' } },
            },
        },
    });
});
</script>
@endpush