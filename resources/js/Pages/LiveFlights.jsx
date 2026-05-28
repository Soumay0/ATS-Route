import React, { useEffect, useState } from 'react';
import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import Navbar from '@/Components/Navbar';
import { Plane, Loader2 } from 'lucide-react';
import axios from 'axios';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import L from 'leaflet';
import { renderToStaticMarkup } from 'react-dom/server';

export default function LiveFlights({ auth }) {
    const [flights, setFlights] = useState([]);
    const [loading, setLoading] = useState(true);

    const fetchFlights = async () => {
        try {
            const response = await axios.get('/api/flights');
            setFlights(response.data);
            setLoading(false);
        } catch (error) {
            console.error("Error fetching live flights:", error);
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchFlights();
        const interval = setInterval(fetchFlights, 15000); // Poll every 15s
        return () => clearInterval(interval);
    }, []);

    // Create a custom icon for the plane
    const createPlaneIcon = (heading) => {
        const iconMarkup = renderToStaticMarkup(
            <div style={{ transform: `rotate(${heading || 0}deg)`, color: '#00f0ff', filter: 'drop-shadow(0 0 5px #00f0ff)' }}>
                <Plane size={20} fill="currentColor" />
            </div>
        );

        return L.divIcon({
            html: iconMarkup,
            className: 'plane-icon',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
        });
    };

    return (
        <div className="layout-container bg-gradient-radial min-h-screen flex flex-col">
            <Head title="Live Radar | AeroRoute" />
            <Navbar auth={auth} />

            <main className="main-content flex flex-col items-center flex-grow w-full">
                <motion.div 
                    initial={{ opacity: 0, y: -20 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="text-center mb-6 w-full max-w-6xl"
                >
                    <h1 className="text-4xl font-bold mb-2">Live Flight Radar</h1>
                    <p className="text-text-secondary">Tracking real-time ADS-B signals (Europe / Asia region).</p>
                </motion.div>

                {loading && flights.length === 0 ? (
                    <div className="flex flex-col items-center justify-center mt-20 flex-grow">
                        <Loader2 className="animate-spin text-accent-primary mb-4" size={48} />
                        <p className="text-text-secondary animate-pulse">Establishing connection to OpenSky Network...</p>
                    </div>
                ) : (
                    <motion.div 
                        initial={{ opacity: 0, scale: 0.98 }}
                        animate={{ opacity: 1, scale: 1 }}
                        className="w-full max-w-7xl glass-panel relative flex-grow overflow-hidden border border-white/10 p-0"
                    >
                        {/* Leaflet Map */}
                        <MapContainer 
                            center={[40.0, 35.0]} 
                            zoom={4} 
                            style={{ height: '100%', minHeight: '600px', width: '100%', background: '#040510' }}
                            scrollWheelZoom={true}
                        >
                            {/* Dark mode filter applied via CSS in index.css, or use a dark tileset like CartoDB Dark Matter */}
                            <TileLayer
                                url="https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png"
                                attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
                            />

                            {flights.map((flight) => {
                                if (!flight.latitude || !flight.longitude) return null;
                                return (
                                    <Marker 
                                        key={flight.icao24} 
                                        position={[flight.latitude, flight.longitude]}
                                        icon={createPlaneIcon(flight.true_track)}
                                    >
                                        <Popup className="custom-popup">
                                            <div className="text-gray-800 font-mono">
                                                <strong className="text-lg text-blue-600 block">{flight.callsign || 'UNKNOWN'}</strong>
                                                <span>Alt: {Math.round(flight.altitude || 0)}m</span><br/>
                                                <span>Spd: {Math.round(flight.velocity || 0)}m/s</span><br/>
                                                <span>Track: {Math.round(flight.true_track || 0)}°</span>
                                            </div>
                                        </Popup>
                                    </Marker>
                                );
                            })}
                        </MapContainer>

                        {/* UI Overlay */}
                        <div className="absolute top-6 left-6 bg-black/60 backdrop-blur px-4 py-2 rounded-lg border border-white/10 flex items-center gap-3 z-[400] shadow-xl">
                            <div className="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                            <span className="text-sm font-mono text-white">{flights.length} Live Aircraft</span>
                        </div>
                    </motion.div>
                )}
            </main>
        </div>
    );
}
