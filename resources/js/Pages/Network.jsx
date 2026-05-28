import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import Navbar from '@/Components/Navbar';
import { Radio, MapPin } from 'lucide-react';
import NetworkMap from '@/Components/Map/NetworkMap';

export default function Network({ auth }) {
    const [waypoints, setWaypoints] = useState([]);
    const [routes, setRoutes] = useState([]);
    const [liveFlights, setLiveFlights] = useState([]);

    useEffect(() => {
        // Real geographic coordinates for the mock waypoints
        const mockWaypoints = [
            { id: 'DEL', name: 'New Delhi (DEL)', lat: 28.5562, lon: 77.1000, type: 'VOR' },
            { id: 'BOM', name: 'Mumbai (BOM)', lat: 19.0896, lon: 72.8656, type: 'NDB' },
            { id: 'LHR', name: 'London (LHR)', lat: 51.4700, lon: -0.4543, type: 'VOR' },
            { id: 'JFK', name: 'New York (JFK)', lat: 40.6413, lon: -73.7781, type: 'VORTAC' },
            { id: 'DXB', name: 'Dubai (DXB)', lat: 25.2532, lon: 55.3657, type: 'VOR' },
            { id: 'SIN', name: 'Singapore (SIN)', lat: 1.3644, lon: 103.9915, type: 'NDB' },
        ];

        const mockRoutes = [
            { id: 1, from: 'DEL', to: 'LHR', status: 'active' },
            { id: 2, from: 'BOM', to: 'DXB', status: 'active' },
            { id: 3, from: 'LHR', to: 'JFK', status: 'congested' },
            { id: 4, from: 'DXB', to: 'SIN', status: 'active' },
            { id: 5, from: 'DEL', to: 'BOM', status: 'active' },
        ];

        setWaypoints(mockWaypoints);
        setRoutes(mockRoutes);

        const fetchFlights = async () => {
            try {
                const response = await axios.get('/api/flights');
                setLiveFlights(response.data);
            } catch (error) {
                console.error("Error fetching flights:", error);
            }
        };
        fetchFlights();
        const interval = setInterval(fetchFlights, 15000);
        return () => clearInterval(interval);
    }, []);

    return (
        <div className="layout-container bg-gradient-radial">
            <Head title="ATS Route Network | AeroRoute" />
            <Navbar auth={auth} />

            <main className="main-content flex flex-col items-center">
                <motion.div 
                    initial={{ opacity: 0, y: -20 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="text-center mb-10 w-full max-w-6xl"
                >
                    <h1 className="text-4xl font-bold mb-4">ATS Route Network</h1>
                    <p className="text-text-secondary max-w-2xl mx-auto">
                        Interactive presentation of Waypoints, Navigational Aids, and real-time Air Traffic Services Network.
                    </p>
                </motion.div>

                <div className="w-full max-w-7xl grid grid-cols-1 lg:grid-cols-4 gap-8">
                    
                    {/* Control Panel */}
                    <motion.div 
                        initial={{ opacity: 0, x: -30 }}
                        animate={{ opacity: 1, x: 0 }}
                        transition={{ delay: 0.2 }}
                        className="glass-panel flex flex-col gap-6 lg:h-[700px] overflow-y-auto"
                    >
                        <h3 className="text-xl border-b border-border-light pb-3 flex items-center gap-2">
                            <Radio size={20} className="text-accent-primary" />
                            Navigational Aids
                        </h3>
                        
                        <div className="flex flex-col gap-4">
                            {waypoints.map((wp, i) => (
                                <motion.div 
                                    key={wp.id}
                                    initial={{ opacity: 0, x: -20 }}
                                    animate={{ opacity: 1, x: 0 }}
                                    transition={{ delay: 0.1 * i }}
                                    whileHover={{ scale: 1.02 }}
                                    className="flex items-center gap-3 p-3 rounded-lg bg-hover-bg border border-border-light hover:border-accent-primary/50 transition-colors cursor-pointer"
                                >
                                    <div className="w-8 h-8 rounded-full bg-accent-primary/20 flex items-center justify-center text-accent-primary">
                                        <MapPin size={16} />
                                    </div>
                                    <div>
                                        <h4 className="font-semibold text-sm">{wp.name}</h4>
                                        <span className="text-xs text-text-secondary bg-white/10 px-2 py-1 rounded mt-1 inline-block">
                                            Type: {wp.type}
                                        </span>
                                    </div>
                                </motion.div>
                            ))}
                        </div>
                    </motion.div>

                    {/* Interactive Leaflet Map Area */}
                    <motion.div 
                        initial={{ opacity: 0, scale: 0.95 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{ delay: 0.4 }}
                        className="lg:col-span-3 glass-panel relative h-[500px] lg:h-[700px] p-0 border border-border-light overflow-hidden"
                    >
                        <NetworkMap waypoints={waypoints} routes={routes} liveFlights={liveFlights} />
                    </motion.div>
                </div>
            </main>
        </div>
    );
}
