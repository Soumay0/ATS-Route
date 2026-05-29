@extends('layouts.dashboard')

@section('title', 'AeroRoute Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="mb-6 flex items-center justify-between rounded-2xl border border-border-light bg-white/5 p-4">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $mongoStatus === 'Connected' ? 'bg-emerald-400/20 text-emerald-400' : 'bg-red-400/20 text-red-400' }}">
            <i class="fa-solid fa-database"></i>
        </div>
        <div>
            <h3 class="text-sm font-medium text-white">Database Status</h3>
            <p class="text-xs text-text-secondary">MongoDB is {{ $mongoStatus }}</p>
        </div>
    </div>
</div>
<div class="grid gap-6 xl:grid-cols-4">
    <x-stat-card title="Total Waypoints" :value="$waypoints" icon="fa-location-dot" />
    <x-stat-card title="Total Routes" :value="$routes" icon="fa-route" accent="text-accent-secondary" ring="bg-accent-secondary/10" />
    <x-stat-card title="Navigational Aids" :value="$aids" icon="fa-tower-cell" accent="text-cyan-300" ring="bg-cyan-300/10" />
    <x-stat-card title="Active Users" :value="$users" icon="fa-user-astronaut" accent="text-emerald-300" ring="bg-emerald-300/10" />
</div>

<div class="mt-8 grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
    <div class="glass-panel rounded-[2rem] p-6">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-text-tertiary">Route activity</p>
                <h2 class="mt-2 text-2xl font-semibold text-white">ATS route activity graph</h2>
            </div>
        </div>
        <canvas id="routeActivityChart" height="120"></canvas>
    </div>

    <div class="glass-panel rounded-[2rem] p-6">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-text-tertiary">Map snapshot</p>
                <h2 class="mt-2 text-2xl font-semibold text-white">Operational radar window</h2>
            </div>
        </div>
        <div class="relative h-[320px] overflow-hidden rounded-[1.75rem] border border-border-light bg-[radial-gradient(circle_at_center,rgba(0,194,255,0.16),transparent_45%),linear-gradient(180deg,#0b1220,#111827)]">
            <div class="absolute inset-0 opacity-45" style="background-image: linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 48px 48px;"></div>
            <div class="absolute left-[22%] top-[24%] h-4 w-4 rounded-full bg-accent-primary shadow-[0_0_20px_rgba(0,194,255,0.9)]"></div>
            <div class="absolute left-[58%] top-[50%] h-4 w-4 rounded-full bg-accent-secondary shadow-[0_0_20px_rgba(34,211,238,0.9)]"></div>
            <div class="absolute left-[72%] top-[68%] h-4 w-4 rounded-full bg-emerald-400 shadow-[0_0_20px_rgba(74,222,128,0.9)]"></div>
            <svg viewBox="0 0 800 400" class="absolute inset-0 h-full w-full">
                <path d="M120 300 C240 130, 400 140, 660 90" fill="none" stroke="#00C2FF" stroke-width="5" stroke-linecap="round" stroke-dasharray="14 12" class="route-line"></path>
                <path d="M170 110 C300 150, 450 260, 620 250" fill="none" stroke="#22D3EE" stroke-width="4" stroke-linecap="round" stroke-dasharray="10 10" class="route-line-alt"></path>
            </svg>
        </div>
    </div>
</div>

<div class="mt-8 grid gap-6 xl:grid-cols-3">
    <div class="glass-panel rounded-[2rem] p-6 xl:col-span-2">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-white">Recent route updates</h2>
            <a href="{{ route('ats-routes.index') }}" class="text-sm text-accent-primary hover:text-white">View all</a>
        </div>
        <div class="space-y-4">
            @forelse($recentRoutes as $route)
                <div class="rounded-2xl border border-border-light bg-white/5 p-4">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <div class="text-lg font-medium text-white">{{ $route->route_name }}</div>
                            <div class="mt-1 text-sm text-text-secondary">{{ $route->direction }} | {{ $route->waypoints->count() }} waypoints</div>
                        </div>
                        <span class="rounded-full border border-accent-primary/30 bg-accent-primary/10 px-3 py-1 text-xs uppercase tracking-[0.3em] text-accent-primary">{{ $route->status }}</span>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-border-light bg-white/5 p-6 text-text-secondary">No routes have been created yet.</div>
            @endforelse
        </div>
    </div>

    <div class="glass-panel rounded-[2rem] p-6">
        <h2 class="text-2xl font-semibold text-white">Recent waypoint and aid activity</h2>
        <div class="mt-5 space-y-4">
            @foreach($recentWaypoints->take(3) as $waypoint)
                <div class="rounded-2xl border border-border-light bg-white/5 p-4">
                    <div class="text-white">{{ $waypoint->name }}</div>
                    <div class="text-sm text-text-secondary">{{ $waypoint->type }} | {{ $waypoint->latitude }}, {{ $waypoint->longitude }}</div>
                </div>
            @endforeach
            @foreach($recentAids->take(2) as $aid)
                <div class="rounded-2xl border border-border-light bg-white/5 p-4">
                    <div class="text-white">{{ $aid->aid_name }}</div>
                    <div class="text-sm text-text-secondary">{{ $aid->aid_type }} | {{ $aid->frequency }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('routeActivityChart');

    if (!canvas || typeof Chart === 'undefined') {
        return;
    }

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Routes created',
                data: @json($chartValues),
                borderColor: '#00C2FF',
                backgroundColor: 'rgba(0, 194, 255, 0.14)',
                tension: 0.45,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#22D3EE',
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: '#E5E7EB' } },
            },
            scales: {
                x: { ticks: { color: '#94A3B8' }, grid: { color: 'rgba(255,255,255,0.06)' } },
                y: { ticks: { color: '#94A3B8' }, grid: { color: 'rgba(255,255,255,0.06)' } },
            },
        },
    });
});
</script>
@endpush