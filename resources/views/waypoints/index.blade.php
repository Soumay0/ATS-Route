@extends('layouts.dashboard')

@section('title', 'Waypoints')
@section('page-title', 'Waypoints')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <form method="GET" class="flex w-full max-w-xl gap-3">
        <input type="search" name="search" value="{{ $search }}" placeholder="Search waypoints" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white placeholder:text-text-tertiary focus:border-accent-primary focus:outline-none">
        <button class="btn-outline" type="submit">Search</button>
    </form>
    <a href="{{ route('waypoints.create') }}" class="btn-primary">Add Waypoint</a>
</div>

<div class="glass-panel overflow-hidden rounded-[2rem] p-0">
    <div class="border-b border-border-light px-6 py-4 text-text-secondary">Waypoint registry and map-ready coordinates</div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="text-xs uppercase tracking-[0.3em] text-text-tertiary">
                <tr>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Coordinates</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($waypoints as $waypoint)
                    <tr class="border-t border-border-light/70 hover:bg-white/5">
                        <td class="px-6 py-4 text-white">{{ $waypoint->name }}</td>
                        <td class="px-6 py-4 text-text-secondary">{{ $waypoint->type }}</td>
                        <td class="px-6 py-4 font-mono text-text-secondary">{{ $waypoint->latitude }}, {{ $waypoint->longitude }}</td>
                        <td class="px-6 py-4">
                            <span class="rounded-full border border-accent-primary/30 bg-accent-primary/10 px-3 py-1 text-xs uppercase tracking-[0.3em] text-accent-primary">
                                {{ $waypoint->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('waypoints.edit', $waypoint) }}" class="btn-outline px-3 py-2 text-xs">Edit</a>
                                <form action="{{ route('waypoints.destroy', $waypoint) }}" method="POST" onsubmit="return confirm('Delete this waypoint?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-full border border-red-400/30 bg-red-400/10 px-3 py-2 text-xs text-red-200">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-text-secondary">No waypoints found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $waypoints->links() }}
</div>
@endsection