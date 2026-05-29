@php($aidModel = $aid ?? null)

<form method="POST" action="{{ $action }}" class="glass-panel space-y-6 rounded-[2rem] p-6">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Aid Name</span>
            <input type="text" name="aid_name" value="{{ old('aid_name', $aidModel->aid_name ?? '') }}" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none" required>
        </label>
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Aid Type</span>
            <select name="aid_type" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none" required>
                @foreach(['VOR', 'NDB', 'DME'] as $type)
                    <option value="{{ $type }}" @selected(old('aid_type', $aidModel->aid_type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </label>
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Frequency</span>
            <input type="text" name="frequency" value="{{ old('frequency', $aidModel->frequency ?? '') }}" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none" required>
        </label>
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Latitude</span>
            <input type="number" step="0.0000001" id="latitude" name="latitude" value="{{ old('latitude', $aidModel->latitude ?? '') }}" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none" required>
        </label>
        <label class="space-y-2">
            <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Longitude</span>
            <input type="number" step="0.0000001" id="longitude" name="longitude" value="{{ old('longitude', $aidModel->longitude ?? '') }}" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none" required>
        </label>
        <div class="md:col-span-2">
            <button type="button" onclick="openMapModal_aidMap()" class="flex w-full items-center justify-center gap-2 rounded-2xl border border-accent-primary/50 bg-accent-primary/10 px-4 py-3 text-sm font-medium text-accent-primary transition-colors hover:bg-accent-primary hover:text-white">
                <i class="fa-solid fa-map-location-dot"></i> Select Location on Map
            </button>
        </div>
    </div>
    
    <x-location-map-modal id="aidMap" latInputId="latitude" lngInputId="longitude" markerType="vor" />

    <label class="space-y-2 block">
        <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Description</span>
        <textarea name="description" rows="5" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none">{{ old('description', $aidModel->description ?? '') }}</textarea>
    </label>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn-primary">Save Aid</button>
        <a href="{{ route('navigational-aids.index') }}" class="btn-outline">Cancel</a>
    </div>
</form>