@php($waypointModel = $waypoint ?? null)

<form method="POST" action="{{ $action }}" class="glass-panel space-y-6 rounded-[2rem] p-6">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Waypoint Name</span>
            <input type="text" name="name" value="{{ old('name', $waypointModel->name ?? '') }}" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white placeholder:text-text-tertiary focus:border-accent-primary focus:outline-none" placeholder="e.g. DELTA" required>
        </label>
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Type</span>
            <input type="text" name="type" value="{{ old('type', $waypointModel->type ?? '') }}" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white placeholder:text-text-tertiary focus:border-accent-primary focus:outline-none" placeholder="Intersection / Fix / VOR" required>
        </label>
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Latitude</span>
            <input type="number" step="0.0000001" id="latitude" name="latitude" value="{{ old('latitude', $waypointModel->latitude ?? '') }}" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white placeholder:text-text-tertiary focus:border-accent-primary focus:outline-none" required>
        </label>
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Longitude</span>
            <input type="number" step="0.0000001" id="longitude" name="longitude" value="{{ old('longitude', $waypointModel->longitude ?? '') }}" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white placeholder:text-text-tertiary focus:border-accent-primary focus:outline-none" required>
        </label>
        <div class="md:col-span-2">
            <button type="button" onclick="openMapModal_waypointMap()" class="flex w-full items-center justify-center gap-2 rounded-2xl border border-accent-primary/50 bg-accent-primary/10 px-4 py-3 text-sm font-medium text-accent-primary transition-colors hover:bg-accent-primary hover:text-white">
                <i class="fa-solid fa-map-location-dot"></i> Select Location on Map
            </button>
        </div>
    </div>
    
    <x-location-map-modal id="waypointMap" latInputId="latitude" lngInputId="longitude" markerType="waypoint" />

    <label class="space-y-2 block">
        <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Description</span>
        <textarea name="description" rows="5" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white placeholder:text-text-tertiary focus:border-accent-primary focus:outline-none">{{ old('description', $waypointModel->description ?? '') }}</textarea>
    </label>

    <label class="flex items-center gap-3 text-sm text-text-secondary">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $waypointModel->is_active ?? true)) class="h-4 w-4 rounded border-border-light bg-white/5 text-accent-primary focus:ring-accent-primary">
        Active on map overlay
    </label>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn-primary">Save Waypoint</button>
        <a href="{{ route('waypoints.index') }}" class="btn-outline">Cancel</a>
    </div>
</form>