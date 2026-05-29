@extends('layouts.dashboard')

@section('title', 'ATS Routes')
@section('page-title', 'ATS Routes')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <form method="GET" class="flex w-full max-w-xl gap-3">
        <input type="search" name="search" value="{{ $search }}" placeholder="Search ATS routes" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white placeholder:text-text-tertiary focus:border-accent-primary focus:outline-none">
        <button class="btn-outline" type="submit">Search</button>
    </form>
    <a href="{{ route('ats-routes.create') }}" class="btn-primary">Create Route</a>
</div>

<div class="glass-panel overflow-hidden rounded-[2rem] p-0">
    <div class="border-b border-border-light px-6 py-4 text-text-secondary">Route networks and waypoint connections</div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="text-xs uppercase tracking-[0.3em] text-text-tertiary">
                <tr>
                    <th class="px-6 py-4">Route</th>
                    <th class="px-6 py-4">Direction</th>
                    <th class="px-6 py-4">Waypoints</th>
                    <th class="px-6 py-4">Distance</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($routes as $route)
                    <tr class="border-t border-border-light/70 hover:bg-white/5">
                        <td class="px-6 py-4 text-white">{{ $route->route_name }}</td>
                        <td class="px-6 py-4 text-text-secondary">{{ $route->direction }}</td>
                        <td class="px-6 py-4 text-text-secondary">{{ $route->waypoints->pluck('name')->join(' → ') }}</td>
                        <td class="px-6 py-4 font-mono text-text-secondary">{{ $route->distance ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="rounded-full border border-accent-secondary/30 bg-accent-secondary/10 px-3 py-1 text-xs uppercase tracking-[0.3em] text-accent-secondary">
                                {{ $route->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('ats-routes.edit', $route) }}" class="btn-outline px-3 py-2 text-xs">Edit</a>
                                <form action="{{ route('ats-routes.destroy', $route) }}" method="POST" onsubmit="return confirm('Delete this route?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-full border border-red-400/30 bg-red-400/10 px-3 py-2 text-xs text-red-200">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-text-secondary">No routes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $routes->links() }}
</div>
@endsection