@extends('layouts.dashboard')

@section('title', 'Interactive Map')
@section('page-title', 'Live Interactive Map')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<style>
    /* Full Map Layout adjustments */
    body, html { height: 100%; margin: 0; overflow: hidden; }
    main { 
        height: calc(100vh - 73px) !important; 
        padding: 0 !important; 
        margin: 0 !important; 
        position: relative; 
        overflow: hidden; 
    }
    
    /* Leaflet Overrides */
    .leaflet-container { font-family: var(--font-body); background: var(--map-bg); }
    .leaflet-popup-content-wrapper { background: var(--popup-bg); color: var(--text-primary); border: 1px solid var(--border-light); border-radius: 12px; backdrop-filter: blur(10px); box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.3); }
    .leaflet-popup-tip { background: var(--popup-bg); border-top: 1px solid var(--border-light); border-left: 1px solid var(--border-light); }
    .leaflet-bar a { background-color: var(--popup-bg) !important; color: var(--text-secondary) !important; border-color: var(--border-light) !important; backdrop-filter: blur(8px); }
    .leaflet-bar a:hover { background-color: var(--hover-bg) !important; color: var(--text-primary) !important; }
    .leaflet-control-zoom { border: none !important; margin-right: 20px !important; margin-bottom: 30px !important; }

    /* Marker Clusters styling */
    .marker-cluster-small, .marker-cluster-medium, .marker-cluster-large {
        background-color: rgba(0, 194, 255, 0.2);
    }
    .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div {
        background-color: rgba(0, 194, 255, 0.8);
        color: white;
        font-weight: bold;
    }

    /* Custom Aviation Markers */
    .aviation-marker {
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; border: 2px solid; color: currentColor;
        transition: all 0.3s ease;
        background-color: var(--popup-bg);
        box-shadow: 0 0 15px currentColor;
    }
    .aviation-marker:hover { transform: scale(1.3); z-index: 1000 !important; box-shadow: 0 0 25px currentColor; }
    
    .marker-waypoint { border-color: var(--accent-primary); color: var(--accent-primary); }
    .marker-vor { border-color: var(--accent-secondary); color: var(--accent-secondary); border-radius: 4px; }
    .marker-ndb { border-color: #F472B6; color: #F472B6; }
    .marker-dme { border-color: #A78BFA; color: #A78BFA; border-radius: 8px 0 8px 0; }
    .marker-aircraft { border-color: #eab308; color: #eab308; box-shadow: 0 0 20px #eab308; animation: pulse-aircraft 1s infinite; background: rgba(234, 179, 8, 0.2); }
    .marker-waypoint { border-color: var(--accent-primary); color: var(--accent-primary); animation: pulse-waypoint 2s infinite; }

    /* Polyline Flow Animation */
    .route-line {
        stroke-dasharray: 10, 20;
        animation: flow 10s linear infinite, route-glow 2s infinite alternate;
        filter: drop-shadow(0 0 3px rgba(0,194,255,0.8));
    }
    @keyframes flow {
        to { stroke-dashoffset: -1000; }
    }
    @keyframes pulse-waypoint {
        0% { box-shadow: 0 0 5px var(--accent-primary); }
        50% { box-shadow: 0 0 15px var(--accent-primary); }
        100% { box-shadow: 0 0 5px var(--accent-primary); }
    }
    @keyframes route-glow {
        from { opacity: 0.7; }
        to { opacity: 1; filter: drop-shadow(0 0 8px rgba(0,194,255,1)); }
    }
    @keyframes pulse-aircraft {
        0% { transform: scale(1); box-shadow: 0 0 10px #eab308; }
        50% { transform: scale(1.1); box-shadow: 0 0 25px #eab308; }
        100% { transform: scale(1); box-shadow: 0 0 10px #eab308; }
    }

    /* Custom Toast */
    .toast-enter { animation: toastIn 0.3s ease-out forwards; }
    .toast-leave { animation: toastOut 0.3s ease-in forwards; }
    @keyframes toastIn { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @keyframes toastOut { from { transform: translateY(0); opacity: 1; } to { transform: translateY(100%); opacity: 0; } }

    /* Spinner */
    .spinner { border: 3px solid rgba(0,194,255,0.1); border-left-color: var(--accent-primary); border-radius: 50%; width: 20px; height: 20px; animation: spin 1s linear infinite; }
    @keyframes spin { 100% { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<!-- Fixed Viewport Map Wrapper to bypass layout collapse bugs -->
<div class="fixed inset-0 top-[73px] z-0 md:left-[320px]">
    <!-- Map Container -->
    <div id="aviationMap" class="absolute inset-0 h-full w-full bg-[#0f172a]"></div>

    <!-- Live Info Panel -->
    <div class="absolute top-6 left-6 z-[400] w-80 max-h-[calc(100vh-120px)] overflow-y-auto rounded-3xl border border-border-light bg-bg-card-solid/90 p-5 shadow-2xl backdrop-blur-xl transition-all">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-accent-primary/20 text-accent-primary">
                    <i class="fa-solid fa-satellite"></i>
                </span>
                <div>
                    <h2 class="text-lg font-bold tracking-wider text-text-primary font-heading">LIVE RADAR</h2>
                    <div class="flex items-center gap-2 text-xs text-accent-secondary">
                        <span class="h-2 w-2 rounded-full bg-accent-secondary animate-pulse"></span>
                        System Online
                    </div>
                </div>
            </div>
            
            <div id="loadingIndicator" class="hidden" title="Fetching Data">
                <div class="spinner"></div>
            </div>
        </div>

        <!-- Search -->
        <div class="relative mb-6">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-text-tertiary"></i>
            <input type="text" id="mapSearch" placeholder="Search network..." class="w-full rounded-xl border border-border-light bg-bg-primary py-2.5 pl-10 pr-4 text-sm text-text-primary placeholder:text-text-tertiary focus:border-accent-primary focus:outline-none focus:ring-1 focus:ring-accent-primary">
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="rounded-2xl border border-border-light bg-bg-primary p-3 text-center">
                <div class="text-[10px] uppercase tracking-widest text-text-tertiary">Waypoints</div>
                <div class="mt-1 font-heading text-xl font-bold text-accent-primary" id="statWaypoints">-</div>
            </div>
            <div class="rounded-2xl border border-border-light bg-bg-primary p-3 text-center">
                <div class="text-[10px] uppercase tracking-widest text-text-tertiary">Navaids</div>
                <div class="mt-1 font-heading text-xl font-bold text-accent-secondary" id="statAids">-</div>
            </div>
            <div class="col-span-2 rounded-2xl border border-border-light bg-bg-primary p-3 text-center flex justify-between items-center px-5">
                <div class="text-[10px] uppercase tracking-widest text-text-tertiary">Active Routes</div>
                <div class="font-heading text-xl font-bold text-accent-tertiary" id="statRoutes">-</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="space-y-2">
            <div class="text-[10px] uppercase tracking-widest text-text-tertiary mb-2">Network Layers</div>
            <label class="flex items-center gap-3 rounded-xl border border-border-light bg-bg-primary p-3 cursor-pointer hover:bg-hover-bg transition-colors">
                <input type="checkbox" id="filterWaypoints" checked class="h-4 w-4 rounded border-border-light bg-bg-secondary text-accent-primary focus:ring-accent-primary focus:ring-offset-0">
                <div class="flex items-center gap-2 text-sm text-text-primary">
                    <span class="h-3 w-3 rounded-full bg-accent-primary"></span> Waypoints
                </div>
            </label>
            <label class="flex items-center gap-3 rounded-xl border border-border-light bg-bg-primary p-3 cursor-pointer hover:bg-hover-bg transition-colors">
                <input type="checkbox" id="filterAids" checked class="h-4 w-4 rounded border-border-light bg-bg-secondary text-accent-secondary focus:ring-accent-secondary focus:ring-offset-0">
                <div class="flex items-center gap-2 text-sm text-text-primary">
                    <span class="h-3 w-3 rounded-sm bg-accent-secondary"></span> Navigational Aids
                </div>
            </label>
            <label class="flex items-center gap-3 rounded-xl border border-border-light bg-bg-primary p-3 cursor-pointer hover:bg-hover-bg transition-colors">
                <input type="checkbox" id="filterRoutes" checked class="h-4 w-4 rounded border-border-light bg-bg-secondary text-accent-tertiary focus:ring-accent-tertiary focus:ring-offset-0">
                <div class="flex items-center gap-2 text-sm text-text-primary">
                    <span class="h-1 w-4 rounded-full bg-accent-tertiary"></span> ATS Routes
                </div>
            </label>
            <label class="flex items-center gap-3 rounded-xl border border-border-light bg-bg-primary p-3 cursor-pointer hover:bg-hover-bg transition-colors">
                <input type="checkbox" id="filterFlights" checked class="h-4 w-4 rounded border-border-light bg-bg-secondary text-yellow-500 focus:ring-yellow-500 focus:ring-offset-0">
                <div class="flex items-center gap-2 text-sm text-text-primary">
                    <i class="fa-solid fa-plane text-yellow-500"></i> Simulated Flights
                </div>
            </label>
        </div>
        
        <div class="mt-6 text-[10px] text-center text-text-tertiary">
            Updates every 10s • Last sync: <span id="lastUpdated">-</span>
        </div>
    </div>

    <!-- Bottom HUD -->
    <div class="absolute bottom-6 left-1/2 z-[400] -translate-x-1/2 flex items-center gap-6 rounded-2xl border border-border-light bg-bg-card-solid/90 px-6 py-3 shadow-2xl backdrop-blur-md pointer-events-none">
        <div class="text-center">
            <div class="text-[9px] uppercase tracking-[0.2em] text-text-tertiary">Latitude</div>
            <div class="font-mono text-sm font-bold text-text-primary tracking-wider" id="hudLat">00.000000</div>
        </div>
        <div class="h-8 w-px bg-border-light"></div>
        <div class="text-center">
            <div class="text-[9px] uppercase tracking-[0.2em] text-text-tertiary">Longitude</div>
            <div class="font-mono text-sm font-bold text-text-primary tracking-wider" id="hudLng">00.000000</div>
        </div>
        <div class="h-8 w-px bg-border-light"></div>
        <div class="text-center">
            <div class="text-[9px] uppercase tracking-[0.2em] text-text-tertiary">Zoom</div>
            <div class="font-mono text-sm font-bold text-accent-primary tracking-wider" id="hudZoom">Z06</div>
        </div>
    </div>

    <!-- Toasts Container -->
    <div id="toastContainer" class="absolute bottom-6 right-6 z-[500] flex flex-col gap-3 pointer-events-none"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        try {
            // Elements
            const hudLat = document.getElementById('hudLat');
            const hudLng = document.getElementById('hudLng');
            const hudZoom = document.getElementById('hudZoom');
            const searchInput = document.getElementById('mapSearch');
            const filterWaypoints = document.getElementById('filterWaypoints');
            const filterAids = document.getElementById('filterAids');
            const filterRoutes = document.getElementById('filterRoutes');
            const filterFlights = document.getElementById('filterFlights');
            
            const statWaypoints = document.getElementById('statWaypoints');
            const statAids = document.getElementById('statAids');
            const statRoutes = document.getElementById('statRoutes');
            const lastUpdated = document.getElementById('lastUpdated');
            const loadingIndicator = document.getElementById('loadingIndicator');

            // Map Setup
            const mapContainer = document.getElementById('aviationMap');
            if (!mapContainer) throw new Error("Map container #aviationMap not found in DOM");

            const map = L.map('aviationMap', {
                zoomControl: false,
                attributionControl: false
            }).setView([20.0, 78.0], 5);

            L.control.zoom({ position: 'bottomright' }).addTo(map);
            
            // Tile Layers: Esri World Imagery (High-res satellite, No API Key needed)
            const tileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
                maxZoom: 19
            }).addTo(map);

            // Bulletproof Resize Handling via Explicit Pixels
            const updateMapSize = () => {
                // Header is roughly 73px
                const newHeight = window.innerHeight - 73;
                mapContainer.style.height = newHeight + 'px';
                mapContainer.style.width = '100%';
                if (map) {
                    map.invalidateSize();
                }
            };
            
            window.addEventListener('resize', updateMapSize);
            // Run immediately and after short delays to ensure DOM is settled
            updateMapSize();
            setTimeout(updateMapSize, 100);
            setTimeout(updateMapSize, 500);

            // Layer Groups with Clustering
            const waypointCluster = L.markerClusterGroup({ maxClusterRadius: 40, disableClusteringAtZoom: 10 }).addTo(map);
            const aidCluster = L.markerClusterGroup({ maxClusterRadius: 40, disableClusteringAtZoom: 10 }).addTo(map);
            const routeLayer = L.layerGroup().addTo(map);
            const aircraftLayer = L.layerGroup().addTo(map);

            // State Memory for Diffing
            let currentLayers = { waypoints: new Map(), aids: new Map(), routes: new Map(), aircraft: new Map() };
            let activeFlights = [];
            let simulationFrameId = null;

            function startFlightSimulation() {
                if (simulationFrameId) cancelAnimationFrame(simulationFrameId);
                let lastTime = performance.now();
                
                function animate(time) {
                    const dt = (time - lastTime) / 1000;
                    lastTime = time;
                    
                    if (filterFlights && !filterFlights.checked) {
                        activeFlights.forEach(f => {
                            if (map.hasLayer(f.marker)) map.removeLayer(f.marker);
                        });
                        simulationFrameId = requestAnimationFrame(animate);
                        return;
                    }
                    
                    activeFlights.forEach(flight => {
                        if (!flight.points || flight.points.length < 2) return;
                        
                        if (!map.hasLayer(flight.marker)) {
                            flight.marker.addTo(map);
                        }
                        
                        const p1 = flight.points[flight.segment];
                        const p2 = flight.points[flight.segment + 1];
                        const dist = map.distance(p1, p2);
                        const speedMetersPerSec = flight.speed * 0.514444; // knots to m/s
                        
                        flight.progress += (speedMetersPerSec * dt);
                        
                        if (flight.progress >= dist) {
                            flight.progress = 0;
                            flight.segment++;
                            if (flight.segment >= flight.points.length - 1) {
                                flight.segment = 0; // Loop back
                            }
                        }
                        
                        const currentP1 = flight.points[flight.segment];
                        const currentP2 = flight.points[flight.segment + 1];
                        const segmentDist = map.distance(currentP1, currentP2) || 1;
                        const ratio = flight.progress / segmentDist;
                        
                        const lat = currentP1.lat + (currentP2.lat - currentP1.lat) * ratio;
                        const lng = currentP1.lng + (currentP2.lng - currentP1.lng) * ratio;
                        
                        flight.marker.setLatLng([lat, lng]);
                    });
                    
                    simulationFrameId = requestAnimationFrame(animate);
                }
                simulationFrameId = requestAnimationFrame(animate);
            }
            startFlightSimulation();

            // HUD Events
            map.on('mousemove', (e) => {
                if(hudLat) hudLat.innerText = e.latlng.lat.toFixed(6).padStart(10, '0');
                if(hudLng) hudLng.innerText = e.latlng.lng.toFixed(6).padStart(10, '0');
            });
            map.on('zoomend', () => {
                if(hudZoom) hudZoom.innerText = `Z${map.getZoom().toString().padStart(2, '0')}`;
            });
            if(hudZoom) hudZoom.innerText = `Z${map.getZoom().toString().padStart(2, '0')}`;

            // Toast System
            function showToast(message, type = 'info') {
                const container = document.getElementById('toastContainer');
                if (!container) return;
                const toast = document.createElement('div');
                
                let colorClass = 'border-accent-primary bg-accent-primary/10 text-accent-primary';
                let icon = 'fa-info-circle';
                
                if (type === 'success') { colorClass = 'border-accent-secondary bg-accent-secondary/10 text-accent-secondary'; icon = 'fa-check-circle'; }
                if (type === 'error') { colorClass = 'border-red-500 bg-red-500/10 text-red-500'; icon = 'fa-triangle-exclamation'; }

                toast.className = `toast-enter flex items-center gap-3 rounded-2xl border p-4 shadow-xl backdrop-blur-md bg-bg-card-solid ${colorClass}`;
                toast.innerHTML = `<i class="fa-solid ${icon}"></i><span class="text-sm font-medium text-text-primary">${message}</span>`;
                
                container.appendChild(toast);
                
                setTimeout(() => {
                    toast.classList.replace('toast-enter', 'toast-leave');
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            }

            function getIcon(type) {
                let className = 'marker-waypoint';
                let iconClass = 'fa-location-dot';
                
                if (['vor', 'ndb', 'dme'].includes(type)) {
                    if (type === 'vor') { className = 'marker-vor'; iconClass = 'fa-satellite-dish'; }
                    if (type === 'ndb') { className = 'marker-ndb'; iconClass = 'fa-broadcast-tower'; }
                    if (type === 'dme') { className = 'marker-dme'; iconClass = 'fa-podcast'; }
                } else if (type === 'aircraft') {
                    className = 'marker-aircraft'; iconClass = 'fa-plane';
                }

                return L.divIcon({
                    className: `aviation-marker ${className}`,
                    html: `<i class="fa-solid ${iconClass} text-[10px]"></i>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12],
                    popupAnchor: [0, -12]
                });
            }

            function calculateDistance(latlngs) {
                let dist = 0;
                for (let i = 0; i < latlngs.length - 1; i++) {
                    dist += map.distance(latlngs[i], latlngs[i+1]);
                }
                return (dist / 1852).toFixed(1); 
            }

            function syncData(apiData) {
                const query = searchInput && searchInput.value ? searchInput.value.toLowerCase().trim() : '';

                const newWpMap = new Map();
                if (filterWaypoints && filterWaypoints.checked && apiData.waypoints) {
                    apiData.waypoints.forEach(wp => {
                        if (query && !wp.name.toLowerCase().includes(query)) return;
                        newWpMap.set(wp.id, wp);
                    });
                }
                
                for (const [id, wp] of newWpMap) {
                    if (!currentLayers.waypoints.has(id)) {
                        const marker = L.marker([wp.lat, wp.lng], { icon: getIcon('waypoint') });
                        marker.bindPopup(`
                            <div class="p-2 min-w-[200px]">
                                <div class="text-[10px] uppercase tracking-[0.2em] text-accent-primary mb-1"><i class="fa-solid fa-location-dot mr-1"></i> WAYPOINT &bull; ${wp.type}</div>
                                <div class="text-xl font-heading font-bold text-text-primary mb-2">${wp.name}</div>
                                <div class="grid grid-cols-2 gap-2 text-xs border-t border-border-light pt-2 mt-2">
                                    <div><span class="text-text-tertiary block">LAT</span><span class="font-mono text-text-primary">${wp.lat.toFixed(5)}</span></div>
                                    <div><span class="text-text-tertiary block">LNG</span><span class="font-mono text-text-primary">${wp.lng.toFixed(5)}</span></div>
                                </div>
                            </div>
                        `);
                        marker.bindTooltip(wp.name, { permanent: true, direction: 'right', className: 'bg-transparent border-0 shadow-none text-text-primary text-[10px] font-bold drop-shadow-md', offset: [10, 0] });
                        waypointCluster.addLayer(marker);
                        currentLayers.waypoints.set(id, marker);
                    }
                }
                for (const [id, marker] of currentLayers.waypoints) {
                    if (!newWpMap.has(id)) {
                        waypointCluster.removeLayer(marker);
                        currentLayers.waypoints.delete(id);
                    }
                }

                const newAidMap = new Map();
                if (filterAids && filterAids.checked && apiData.aids) {
                    apiData.aids.forEach(aid => {
                        if (query && !aid.name.toLowerCase().includes(query)) return;
                        newAidMap.set(aid.id, aid);
                    });
                }
                
                for (const [id, aid] of newAidMap) {
                    if (!currentLayers.aids.has(id)) {
                        const marker = L.marker([aid.lat, aid.lng], { icon: getIcon(aid.type) });
                        const freqLine = aid.frequency ? `<div><span class="text-text-tertiary block">FREQ</span><span class="font-mono text-accent-primary">${aid.frequency}</span></div>` : '';
                        marker.bindPopup(`
                            <div class="p-2 min-w-[200px]">
                                <div class="text-[10px] uppercase tracking-[0.2em] text-accent-secondary mb-1"><i class="fa-solid fa-broadcast-tower mr-1"></i> NAVAID &bull; ${aid.type.toUpperCase()}</div>
                                <div class="text-xl font-heading font-bold text-text-primary mb-2">${aid.name}</div>
                                <div class="grid grid-cols-2 gap-2 text-xs border-t border-border-light pt-2 mt-2">
                                    <div><span class="text-text-tertiary block">LAT</span><span class="font-mono text-text-primary">${aid.lat.toFixed(5)}</span></div>
                                    <div><span class="text-text-tertiary block">LNG</span><span class="font-mono text-text-primary">${aid.lng.toFixed(5)}</span></div>
                                    ${freqLine}
                                </div>
                            </div>
                        `);
                        marker.bindTooltip(aid.name, { permanent: true, direction: 'right', className: 'bg-transparent border-0 shadow-none text-text-primary text-[10px] font-bold drop-shadow-md', offset: [10, 0] });
                        aidCluster.addLayer(marker);
                        currentLayers.aids.set(id, marker);
                    }
                }
                for (const [id, marker] of currentLayers.aids) {
                    if (!newAidMap.has(id)) {
                        aidCluster.removeLayer(marker);
                        currentLayers.aids.delete(id);
                    }
                }

                const newRouteMap = new Map();
                if (filterRoutes && filterRoutes.checked && apiData.routes) {
                    apiData.routes.forEach(route => {
                        if (query && !route.name.toLowerCase().includes(query)) return;
                        if (route.points && route.points.length >= 2) newRouteMap.set(route.id, route);
                    });
                }
                
                for (const [id, route] of newRouteMap) {
                    if (!currentLayers.routes.has(id)) {
                        const latlngs = route.points.map(p => L.latLng(p.lat, p.lng));
                        const distance = calculateDistance(latlngs);
                        
                        const hitArea = L.polyline(latlngs, { color: 'transparent', weight: 20 });
                        
                        const polyline = L.polyline(latlngs, {
                            color: '#00c2ff', 
                            weight: 2,
                            opacity: 0.9,
                            className: 'route-line'
                        });

                        const vertexGroup = L.layerGroup();
                        latlngs.forEach(ll => {
                            L.circleMarker(ll, { radius: 4, fillColor: '#00c2ff', color: '#0b1220', weight: 2, fillOpacity: 1 }).addTo(vertexGroup);
                        });

                        hitArea.on('mouseover', () => { polyline.setStyle({ weight: 4, color: '#2ee6a6' }); });
                        hitArea.on('mouseout', () => { polyline.setStyle({ weight: 2, color: '#00c2ff' }); });

                        hitArea.bindPopup(`
                            <div class="p-2">
                                <div class="text-[10px] uppercase tracking-[0.2em] text-accent-tertiary mb-1"><i class="fa-solid fa-route mr-1"></i> ATS ROUTE</div>
                                <div class="text-lg font-heading font-bold text-text-primary">${route.name}</div>
                                <div class="mt-2 text-xs text-text-tertiary border-t border-border-light pt-2">
                                    Connects ${route.points.length} waypoints.<br>
                                    Total Distance: ${distance} NM
                                </div>
                            </div>
                        `);

                        const routeGroup = L.featureGroup([polyline, hitArea, vertexGroup]);
                        routeLayer.addLayer(routeGroup);
                        currentLayers.routes.set(id, routeGroup);

                        // Spawn Mock Flights on this route
                        if (!activeFlights.some(f => f.routeId === id)) {
                            const flightId = `FL-${Math.floor(Math.random() * 9000) + 1000}`;
                            const speed = Math.floor(Math.random() * (500 - 300 + 1)) + 300; // 300-500 knots
                            const alt = Math.floor(Math.random() * (400 - 200 + 1)) + 200; // FL200-400
                            
                            const marker = L.marker(latlngs[0], { icon: getIcon('aircraft') });
                            marker.bindPopup(`
                                <div class="p-2 min-w-[200px]">
                                    <div class="text-[10px] uppercase tracking-[0.2em] text-yellow-500 mb-1"><i class="fa-solid fa-plane mr-1"></i> FLIGHT SIMULATION</div>
                                    <div class="text-xl font-heading font-bold text-text-primary mb-2">${flightId}</div>
                                    <div class="grid grid-cols-2 gap-2 text-xs border-t border-border-light pt-2 mt-2">
                                        <div><span class="text-text-tertiary block">ROUTE</span><span class="font-mono text-text-primary">${route.name}</span></div>
                                        <div><span class="text-text-tertiary block">SPEED</span><span class="font-mono text-text-primary">${speed} kts</span></div>
                                        <div><span class="text-text-tertiary block">ALTITUDE</span><span class="font-mono text-text-primary">FL${alt}</span></div>
                                    </div>
                                </div>
                            `);
                            marker.bindTooltip(flightId, { permanent: true, direction: 'right', className: 'bg-transparent border-0 shadow-none text-yellow-500 text-[10px] font-bold drop-shadow-md', offset: [10, 0] });
                            
                            activeFlights.push({
                                id: flightId,
                                routeId: id,
                                points: latlngs,
                                segment: 0,
                                progress: 0,
                                speed: speed * 15, // Speed up visually for demo effect
                                marker: marker
                            });
                        }
                    }
                }
                for (const [id, marker] of currentLayers.routes) {
                    if (!newRouteMap.has(id)) {
                        routeLayer.removeLayer(marker);
                        currentLayers.routes.delete(id);
                        activeFlights = activeFlights.filter(f => {
                            if (f.routeId === id) {
                                if (map.hasLayer(f.marker)) map.removeLayer(f.marker);
                                return false;
                            }
                            return true;
                        });
                    }
                }

                if(statWaypoints && apiData.waypoints) statWaypoints.innerText = apiData.waypoints.length;
                if(statAids && apiData.aids) statAids.innerText = apiData.aids.length;
                if(statRoutes && apiData.routes) statRoutes.innerText = apiData.routes.length;
                
                if(lastUpdated) lastUpdated.innerText = new Date().toLocaleTimeString();
            }

            async function fetchMapData(isInitial = false) {
                if(loadingIndicator) loadingIndicator.classList.remove('hidden');
                try {
                    const response = await fetch('/api/map-data');
                    if (!response.ok) throw new Error('API Error: ' + response.statusText);
                    const data = await response.json();
                    
                    if (!isInitial) {
                        data.waypoints.forEach(wp => {
                            if (!currentLayers.waypoints.has(wp.id)) showToast(`Waypoint Sync: ${wp.name}`, 'success');
                        });
                    }
                    
                    syncData(data);
                } catch (error) {
                    console.error('Failed to fetch map data:', error);
                    if (!isInitial) showToast('Radar connection unstable. Retrying...', 'error');
                } finally {
                    if(loadingIndicator) loadingIndicator.classList.add('hidden');
                }
            }

            if(searchInput) searchInput.addEventListener('input', () => fetchMapData(true));
            if(filterWaypoints) filterWaypoints.addEventListener('change', () => fetchMapData(true));
            if(filterAids) filterAids.addEventListener('change', () => fetchMapData(true));
            if(filterRoutes) filterRoutes.addEventListener('change', () => fetchMapData(true));
            if(filterFlights) filterFlights.addEventListener('change', () => {
                if (!filterFlights.checked) {
                    activeFlights.forEach(f => { if (map.hasLayer(f.marker)) map.removeLayer(f.marker); });
                }
            });

            fetchMapData(true);
            setInterval(() => fetchMapData(false), 10000); 

        } catch (e) {
            console.error("Critical Leaflet Map Initialization Error:", e);
            document.getElementById('toastContainer').innerHTML = `<div class="bg-red-500 text-white p-4 rounded-xl shadow-lg">Map Initialization Failed: ${e.message}</div>`;
        }
    });
</script>
@endpush