import React, { useEffect, useState } from 'react';
import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import Navbar from '@/Components/Navbar';
import { CloudRain, Wind, Thermometer, MapPin, Loader2 } from 'lucide-react';
import axios from 'axios';

export default function Weather({ auth }) {
    const [weatherData, setWeatherData] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchWeather = async () => {
            try {
                const response = await axios.get('/api/weather');
                setWeatherData(response.data);
                setLoading(false);
            } catch (error) {
                console.error("Error fetching weather:", error);
                setLoading(false);
            }
        };

        fetchWeather();
        // Refresh every 5 minutes
        const interval = setInterval(fetchWeather, 300000);
        return () => clearInterval(interval);
    }, []);

    // Helper to determine flight category based on rules
    const getFlightCategory = (metar) => {
        const rules = metar.flight_category || 'VFR';
        const colors = {
            'VFR': 'text-green-400 border-green-400/30 bg-green-400/10',
            'MVFR': 'text-blue-400 border-blue-400/30 bg-blue-400/10',
            'IFR': 'text-red-400 border-red-400/30 bg-red-400/10',
            'LIFR': 'text-purple-400 border-purple-400/30 bg-purple-400/10'
        };
        return { category: rules, style: colors[rules] || colors['VFR'] };
    };

    return (
        <div className="layout-container bg-gradient-radial min-h-screen">
            <Head title="Live Weather | AeroRoute" />
            <Navbar auth={auth} />

            <main className="main-content flex flex-col items-center">
                <motion.div 
                    initial={{ opacity: 0, y: -20 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="text-center mb-10 w-full max-w-6xl"
                >
                    <h1 className="text-4xl font-bold mb-4">Live Airport Weather (METAR)</h1>
                    <p className="text-text-secondary max-w-2xl mx-auto">
                        Real-time aviation weather conditions at major global hubs.
                    </p>
                </motion.div>

                {loading ? (
                    <div className="flex flex-col items-center justify-center mt-20">
                        <Loader2 className="animate-spin text-accent-primary mb-4" size={48} />
                        <p className="text-text-secondary animate-pulse">Fetching live METAR data...</p>
                    </div>
                ) : (
                    <div className="w-full max-w-6xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {weatherData.map((station, i) => {
                            const cat = getFlightCategory(station);
                            return (
                                <motion.div 
                                    key={station.icaoId || i}
                                    initial={{ opacity: 0, scale: 0.95 }}
                                    animate={{ opacity: 1, scale: 1 }}
                                    transition={{ delay: i * 0.1 }}
                                    className="glass-panel relative overflow-hidden group"
                                >
                                    <div className="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    
                                    <div className="flex justify-between items-start mb-4">
                                        <div className="flex items-center gap-2">
                                            <MapPin className="text-accent-primary" size={20} />
                                            <h3 className="text-2xl font-bold font-mono">{station.icaoId}</h3>
                                        </div>
                                        <div className={`px-3 py-1 rounded border font-bold text-sm ${cat.style}`}>
                                            {cat.category}
                                        </div>
                                    </div>

                                    <p className="text-xs font-mono text-text-secondary bg-black/30 p-2 rounded mb-4 break-words border border-white/5">
                                        {station.rawOb}
                                    </p>

                                    <div className="grid grid-cols-2 gap-4">
                                        <div className="flex items-center gap-2">
                                            <Thermometer className="text-orange-400" size={16} />
                                            <span className="text-sm">{station.tempC}°C</span>
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <Wind className="text-blue-300" size={16} />
                                            <span className="text-sm">{station.wspd} kt @ {station.wdir}°</span>
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <CloudRain className="text-gray-400" size={16} />
                                            <span className="text-sm">Vis: {station.visib !== undefined ? station.visib : '?'} sm</span>
                                        </div>
                                    </div>
                                    <div className="mt-4 pt-3 border-t border-white/10 text-xs text-text-secondary flex justify-between">
                                        <span>Elev: {station.elev}m</span>
                                        <span>{new Date(station.obsTime).toLocaleTimeString()}</span>
                                    </div>
                                </motion.div>
                            );
                        })}
                    </div>
                )}
            </main>
        </div>
    );
}
