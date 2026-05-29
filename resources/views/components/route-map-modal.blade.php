@props([
    'id' => 'routeMapModal',
    'selectInputId' => 'waypoints',
    'waypoints' => []
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeRouteMapModal('{{ $id }}')"></div>
    
    <!-- Modal Content -->
    <div class="absolute left-1/2 top-1/2 flex w-11/12 max-w-6xl -translate-x-1/2 -translate-y-1/2 flex-col overflow-hidden rounded-[2rem] border border-border-light bg-bg-card-solid shadow-2xl transition-all">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-border-light p-4">
            <h3 class="text-lg font-semibold text-white">Select Route Waypoints</h3>
            <button type="button" onclick="closeRouteMapModal('{{ $id }}')" class="rounded-xl px-3 py-2 text-text-secondary hover:bg-white/5 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Toolbar -->
        <div class="flex items-center gap-4 border-b border-border-light bg-white/5 p-4 text-sm text-text-secondary">
            <i class="fa-solid fa-info-circle text-accent-primary"></i>
            <span>Click on waypoints to toggle their selection. The order you click will determine the route order.</span>
        </div>

        <!-- Map Container -->
        <div class="relative h-[65vh] w-full bg-black/50">
            <div id="{{ $id }}_loading" class="absolute inset-0 z-[400] flex flex-col items-center justify-center bg-bg-card-solid/80 backdrop-blur-sm">
                <i class="fa-solid fa-circle-notch animate-spin text-4xl text-accent-primary"></i>
                <p class="mt-4 text-sm font-medium tracking-wider text-text-secondary">LOADING WAYPOINTS...</p>
            </div>
            <div id="{{ $id }}_map" class="h-full w-full"></div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between p-4">
            <div class="text-sm text-text-secondary">
                Selected: <span id="{{ $id }}_selectedCount" class="font-bold text-white">0</span> waypoints
            </div>
            <div class="flex gap-3">
                <button type="button" id="{{ $id }}_resetBtn" class="btn-outline px-5 py-2.5">Clear Selection</button>
                <button type="button" id="{{ $id }}_confirmBtn" class="btn-primary px-6 py-2.5 shadow-[0_0_20px_rgba(0,194,255,0.3)]">Confirm Route</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let {{ $id }}_mapInstance = null;
    let {{ $id }}_allWaypoints = @json($waypoints);
    let {{ $id }}_selectedIds = [];
    let {{ $id }}_markers = {};
    let {{ $id }}_polyline = null;

    function openRouteMapModal_{{ $id }}() {
        const modal = document.getElementById('{{ $id }}');
        modal.classList.remove('hidden');
        
        // Sync selected ids from the select input
        const select = document.getElementById('{{ $selectInputId }}');
        {{ $id }}_selectedIds = Array.from(select.options).filter(o => o.selected).map(o => o.value);
        
        if (!{{ $id }}_mapInstance) {
            setTimeout(() => {
                initRouteMap_{{ $id }}();
            }, 300);
        } else {
            updateRouteMapState_{{ $id }}();
        }
    }

    function closeRouteMapModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function initRouteMap_{{ $id }}() {
        const mapDiv = document.getElementById('{{ $id }}_map');
        
        // Center on India as fallback, or bounds of all waypoints
        {{ $id }}_mapInstance = L.map(mapDiv).setView([22.0, 78.0], 5);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> contributors',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo({{ $id }}_mapInstance);

        const bounds = [];

        {{ $id }}_allWaypoints.forEach(wp => {
            if(!wp.latitude || !wp.longitude) return;

            const latlng = [wp.latitude, wp.longitude];
            bounds.push(latlng);

            // Default style
            const html = `<div class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-500 bg-slate-800 text-[10px] text-white shadow-lg transition-colors"><i class="fa-solid fa-location-dot"></i></div>`;
            
            const icon = L.divIcon({
                className: 'route-wp-icon',
                html: html,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            const marker = L.marker(latlng, { icon: icon })
                .addTo({{ $id }}_mapInstance)
                .bindTooltip(`<b>${wp.name}</b><br>${wp.type}`, { direction: 'top', offset: [0, -10] });

            marker.on('click', () => {
                toggleWaypoint_{{ $id }}(wp._id || wp.id);
            });

            {{ $id }}_markers[wp._id || wp.id] = marker;
        });

        if (bounds.length > 0) {
            {{ $id }}_mapInstance.fitBounds(bounds, { padding: [50, 50] });
        }

        // Initialize polyline layer
        {{ $id }}_polyline = L.polyline([], {
            color: '#00C2FF',
            weight: 3,
            dashArray: '10, 10',
            opacity: 0.8
        }).addTo({{ $id }}_mapInstance);

        document.getElementById('{{ $id }}_loading').style.display = 'none';
        updateRouteMapState_{{ $id }}();

        // Buttons
        document.getElementById('{{ $id }}_resetBtn').addEventListener('click', () => {
            {{ $id }}_selectedIds = [];
            updateRouteMapState_{{ $id }}();
        });

        document.getElementById('{{ $id }}_confirmBtn').addEventListener('click', () => {
            const select = document.getElementById('{{ $selectInputId }}');
            
            // Unselect all
            Array.from(select.options).forEach(opt => opt.selected = false);
            
            // We can't strictly enforce selection order easily in a standard multiple select,
            // but we'll select the ones in the array.
            // If they need order, standard multi-select doesn't store order anyway unless backend uses sync order.
            {{ $id }}_selectedIds.forEach(id => {
                const opt = select.querySelector(`option[value="${id}"]`);
                if(opt) opt.selected = true;
            });

            closeRouteMapModal('{{ $id }}');
        });
    }

    function toggleWaypoint_{{ $id }}(id) {
        id = String(id);
        const index = {{ $id }}_selectedIds.indexOf(id);
        if (index > -1) {
            {{ $id }}_selectedIds.splice(index, 1);
        } else {
            {{ $id }}_selectedIds.push(id);
        }
        updateRouteMapState_{{ $id }}();
    }

    function updateRouteMapState_{{ $id }}() {
        const lineCoords = [];
        let count = 0;

        // Reset all markers
        Object.values({{ $id }}_markers).forEach(m => {
            m.setIcon(L.divIcon({
                className: 'route-wp-icon',
                html: `<div class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-500 bg-slate-800 text-[10px] text-slate-300 transition-colors hover:border-white hover:text-white"><i class="fa-solid fa-location-dot"></i></div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            }));
        });

        // Highlight selected
        {{ $id }}_selectedIds.forEach((id, idx) => {
            const wp = {{ $id }}_allWaypoints.find(w => String(w._id || w.id) === String(id));
            if (!wp) return;
            
            count++;
            lineCoords.push([wp.latitude, wp.longitude]);

            const marker = {{ $id }}_markers[id];
            if(marker) {
                marker.setIcon(L.divIcon({
                    className: 'route-wp-icon',
                    html: `<div class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-[#00C2FF] bg-[#00C2FF]/20 text-xs font-bold text-[#00C2FF] shadow-[0_0_15px_rgba(0,194,255,0.4)] transition-all scale-110">${idx + 1}</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                }));
            }
        });

        if ({{ $id }}_polyline) {
            {{ $id }}_polyline.setLatLngs(lineCoords);
        }

        document.getElementById('{{ $id }}_selectedCount').innerText = count;
    }
</script>
@endpush
