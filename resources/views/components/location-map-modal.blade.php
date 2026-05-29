@props([
    'id' => 'mapModal',
    'latInputId' => 'latitude',
    'lngInputId' => 'longitude',
    'markerType' => 'waypoint' // waypoint, vor, ndb, dme
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeMapModal('{{ $id }}')"></div>
    
    <!-- Modal Content -->
    <div class="absolute left-1/2 top-1/2 flex w-11/12 max-w-5xl -translate-x-1/2 -translate-y-1/2 flex-col overflow-hidden rounded-[2rem] border border-border-light bg-bg-card-solid shadow-2xl transition-all">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-border-light p-4">
            <h3 class="text-lg font-semibold text-white">Select Location on Map</h3>
            <button type="button" onclick="closeMapModal('{{ $id }}')" class="rounded-xl px-3 py-2 text-text-secondary hover:bg-white/5 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Toolbar -->
        <div class="flex items-center gap-4 border-b border-border-light bg-white/5 p-4">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-text-tertiary"></i>
                <input type="text" id="{{ $id }}_searchInput" class="w-full rounded-xl border border-border-light bg-black/20 py-2.5 pl-10 pr-4 text-sm text-white placeholder:text-text-tertiary focus:border-accent-primary focus:outline-none" placeholder="Search city, airport, or location (e.g. Heathrow)...">
            </div>
            <button type="button" id="{{ $id }}_searchBtn" class="btn-outline whitespace-nowrap px-4 py-2 text-sm">Search</button>
            <div class="h-6 w-px bg-border-light"></div>
            <div class="flex items-center gap-2 text-sm text-text-secondary">
                <i class="fa-solid fa-location-crosshairs text-accent-primary"></i>
                <span id="{{ $id }}_liveCoords">0.000000, 0.000000</span>
            </div>
        </div>

        <!-- Map Container -->
        <div class="relative h-[60vh] w-full bg-black/50">
            <div id="{{ $id }}_loading" class="absolute inset-0 z-[400] flex flex-col items-center justify-center bg-bg-card-solid/80 backdrop-blur-sm">
                <i class="fa-solid fa-circle-notch animate-spin text-4xl text-accent-primary"></i>
                <p class="mt-4 text-sm font-medium tracking-wider text-text-secondary">INITIALIZING MAP...</p>
            </div>
            <div id="{{ $id }}_map" class="h-full w-full"></div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 p-4">
            <button type="button" id="{{ $id }}_resetBtn" class="btn-outline px-5 py-2.5">Reset Marker</button>
            <button type="button" id="{{ $id }}_confirmBtn" class="btn-primary px-6 py-2.5 shadow-[0_0_20px_rgba(0,194,255,0.3)]">Confirm Location</button>
        </div>
    </div>
</div>

@push('scripts')
<style>
    /* Leaflet Dark Theme Overrides */
    .leaflet-container { background: #0b1220; font-family: 'Inter', sans-serif; }
    .leaflet-bar a { background-color: #111827 !important; border-color: #334155 !important; color: #94a3b8 !important; }
    .leaflet-bar a:hover { background-color: #1e293b !important; color: #fff !important; }
    .leaflet-popup-content-wrapper { background: #111827; color: #e2e8f0; border: 1px solid #334155; border-radius: 12px; }
    .leaflet-popup-tip { background: #111827; border-top: 1px solid #334155; border-left: 1px solid #334155; }
    
    /* Custom Icons */
    .custom-map-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(17, 24, 39, 0.9);
        border: 2px solid;
        border-radius: 50%;
        color: white;
        box-shadow: 0 0 15px rgba(0,0,0,0.5);
    }
    .icon-waypoint { border-color: #00C2FF; box-shadow: 0 0 15px rgba(0, 194, 255, 0.4); }
    .icon-vor { border-color: #4ADE80; box-shadow: 0 0 15px rgba(74, 222, 128, 0.4); }
    .icon-ndb { border-color: #F472B6; box-shadow: 0 0 15px rgba(244, 114, 182, 0.4); }
    .icon-dme { border-color: #A78BFA; box-shadow: 0 0 15px rgba(167, 139, 250, 0.4); }
</style>
<script>
    let {{ $id }}_mapInstance = null;
    let {{ $id }}_marker = null;

    function openMapModal_{{ $id }}() {
        const modal = document.getElementById('{{ $id }}');
        modal.classList.remove('hidden');
        
        // Init map if not exists
        if (!{{ $id }}_mapInstance) {
            setTimeout(() => {
                initMap_{{ $id }}();
            }, 300); // Wait for modal animation
        } else {
            // Re-sync coords if user typed them manually before opening
            const lat = parseFloat(document.getElementById('{{ $latInputId }}').value);
            const lng = parseFloat(document.getElementById('{{ $lngInputId }}').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                const latlng = new L.LatLng(lat, lng);
                {{ $id }}_marker.setLatLng(latlng);
                {{ $id }}_mapInstance.setView(latlng, 7);
                updateLiveCoords_{{ $id }}(latlng);
            }
        }
    }

    function closeMapModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function initMap_{{ $id }}() {
        const mapDiv = document.getElementById('{{ $id }}_map');
        
        // Start somewhere (e.g. center of US/Europe/Asia depending on user data, let's use a default if empty)
        let lat = parseFloat(document.getElementById('{{ $latInputId }}').value);
        let lng = parseFloat(document.getElementById('{{ $lngInputId }}').value);
        
        if (isNaN(lat) || isNaN(lng)) {
            lat = 28.556; // Default somewhere around DEL
            lng = 77.100;
        }

        {{ $id }}_mapInstance = L.map(mapDiv).setView([lat, lng], 6);

        // Dark matter tiles from CartoDB
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo({{ $id }}_mapInstance);

        // Icon configuration based on type
        const type = '{{ $markerType }}';
        let iconClass = 'icon-waypoint';
        let iconFa = 'fa-location-dot';
        
        if (type === 'vor') { iconClass = 'icon-vor'; iconFa = 'fa-satellite-dish'; }
        if (type === 'ndb') { iconClass = 'icon-ndb'; iconFa = 'fa-broadcast-tower'; }
        if (type === 'dme') { iconClass = 'icon-dme'; iconFa = 'fa-podcast'; }

        const customIcon = L.divIcon({
            className: `custom-map-icon ${iconClass}`,
            html: `<i class="fa-solid ${iconFa}"></i>`,
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -18]
        });

        {{ $id }}_marker = L.marker([lat, lng], {
            icon: customIcon,
            draggable: true
        }).addTo({{ $id }}_mapInstance)
          .bindPopup(`<b>Selected ${type.toUpperCase()}</b><br>Drag me to adjust location.`)
          .openPopup();

        updateLiveCoords_{{ $id }}({{ $id }}_marker.getLatLng());

        // Event Listeners
        {{ $id }}_marker.on('drag', function(e) {
            updateLiveCoords_{{ $id }}(e.latlng);
        });

        {{ $id }}_mapInstance.on('click', function(e) {
            {{ $id }}_marker.setLatLng(e.latlng);
            updateLiveCoords_{{ $id }}(e.latlng);
        });

        // Hide loader
        document.getElementById('{{ $id }}_loading').style.display = 'none';

        // Search logic
        const searchInput = document.getElementById('{{ $id }}_searchInput');
        const searchBtn = document.getElementById('{{ $id }}_searchBtn');
        
        const performSearch = () => {
            const query = searchInput.value.trim();
            if(!query) return;
            
            searchBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const res = data[0];
                        const latlng = new L.LatLng(res.lat, res.lon);
                        {{ $id }}_mapInstance.flyTo(latlng, 10);
                        {{ $id }}_marker.setLatLng(latlng);
                        updateLiveCoords_{{ $id }}(latlng);
                    } else {
                        alert("Location not found.");
                    }
                })
                .finally(() => {
                    searchBtn.innerHTML = 'Search';
                });
        };

        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', (e) => {
            if(e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });

        // Confirm
        document.getElementById('{{ $id }}_confirmBtn').addEventListener('click', () => {
            const pos = {{ $id }}_marker.getLatLng();
            document.getElementById('{{ $latInputId }}').value = pos.lat.toFixed(7);
            document.getElementById('{{ $lngInputId }}').value = pos.lng.toFixed(7);
            closeMapModal('{{ $id }}');
        });

        // Reset
        document.getElementById('{{ $id }}_resetBtn').addEventListener('click', () => {
            const center = {{ $id }}_mapInstance.getCenter();
            {{ $id }}_marker.setLatLng(center);
            updateLiveCoords_{{ $id }}(center);
        });
    }

    function updateLiveCoords_{{ $id }}(latlng) {
        document.getElementById('{{ $id }}_liveCoords').innerText = `${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`;
    }
</script>
@endpush
