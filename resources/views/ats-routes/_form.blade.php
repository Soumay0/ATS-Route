@php($routeModel = $routeModel ?? null)

<form method="POST" action="{{ $action }}" class="glass-panel space-y-6 rounded-[2rem] p-6">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Route Name</span>
            <input type="text" name="route_name" value="{{ old('route_name', $routeModel->route_name ?? '') }}" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none" required>
        </label>
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Direction</span>
            <input type="text" name="direction" value="{{ old('direction', $routeModel->direction ?? '') }}" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none" placeholder="Northbound / Eastbound" required>
        </label>
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Distance (NM)</span>
            <input type="number" step="0.01" name="distance" value="{{ old('distance', $routeModel->distance ?? '') }}" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none">
        </label>
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Status</span>
            <select name="status" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none">
                @foreach(['active', 'inactive', 'planned'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $routeModel->status ?? 'active') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <div class="space-y-2 block">
        <div class="flex items-center justify-between">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Connected Waypoints</span>
            <button type="button" onclick="openRouteMapModal_routeMap()" class="rounded-xl border border-accent-primary/50 bg-accent-primary/10 px-3 py-1.5 text-xs font-medium text-accent-primary transition-colors hover:bg-accent-primary hover:text-white">
                <i class="fa-solid fa-map-location-dot"></i> Select on Map
            </button>
        </div>
        <select name="waypoints[]" id="waypoints" multiple size="8" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none">
            @foreach($waypoints as $waypoint)
                <option value="{{ $waypoint->id }}" @selected(in_array($waypoint->id, old('waypoints', $selectedWaypoints ?? [])))>
                    {{ $waypoint->name }} - {{ $waypoint->type }}
                </option>
            @endforeach
        </select>
    </div>
    
    <x-route-map-modal id="routeMap" selectInputId="waypoints" :waypoints="$waypoints" />

    <label class="space-y-2 block">
        <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Description</span>
        <textarea name="description" rows="5" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none">{{ old('description', $routeModel->description ?? '') }}</textarea>
    </label>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn-primary">Save Route</button>
        <a href="{{ route('ats-routes.index') }}" class="btn-outline">Cancel</a>
    </div>
</form>