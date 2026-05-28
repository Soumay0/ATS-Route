import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Head } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import Navbar from '@/Components/Navbar';
import { Clock, Calendar, Search, Filter, AlertCircle, CheckCircle, Plane, Loader2 } from 'lucide-react';

export default function Slots({ auth }) {
    const [selectedCityPair, setSelectedCityPair] = useState('DEL-LHR');
    const [slots, setSlots] = useState([]);
    const [loading, setLoading] = useState(true);
    const [generating, setGenerating] = useState(false);

    const cityPairs = [
        'DEL-LHR', 'BOM-DXB', 'LHR-JFK', 'DXB-SIN', 'DEL-BOM'
    ];

    const fetchSlots = async () => {
        try {
            const response = await axios.get('/api/slots');
            setSlots(response.data);
            setLoading(false);
        } catch (error) {
            console.error("Error fetching slots:", error);
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchSlots();
    }, []);

    const generateSlots = async () => {
        setGenerating(true);
        try {
            await axios.post('/api/slots/generate');
            await fetchSlots();
        } catch (error) {
            console.error("Error generating slots:", error);
        } finally {
            setGenerating(false);
        }
    };

    const updateStatus = async (id, status) => {
        try {
            // Optimistic update
            setSlots(slots.map(s => s.id === id ? { ...s, status } : s));
            
            await axios.patch(`/api/slots/${id}`, { status });
        } catch (error) {
            console.error("Error updating slot:", error);
            fetchSlots(); // Revert on failure
        }
    };

    return (
        <div className="layout-container bg-gradient-radial min-h-screen">
            <Head title="Slot Allocation | AeroRoute" />
            <Navbar auth={auth} />

            <main className="main-content">
                <motion.div 
                    initial={{ opacity: 0, y: -20 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="text-center mb-10"
                >
                    <h1 className="text-4xl font-bold mb-4">Slot Allocation Matrix</h1>
                    <p className="text-text-secondary max-w-2xl mx-auto">
                        Manage and allocate multiple city pair slots to airlines based on real-time inbound traffic.
                    </p>
                </motion.div>

                <div className="w-full max-w-6xl mx-auto">
                    {/* Controls */}
                    <div className="glass-panel mb-8 flex flex-wrap gap-4 items-center justify-between">
                        <div className="flex gap-4 items-center">
                            <div className="relative">
                                <Filter className="absolute left-3 top-1/2 transform -translate-y-1/2 text-text-secondary" size={18} />
                                <select 
                                    className="input-field pl-10 appearance-none bg-transparent"
                                    value={selectedCityPair}
                                    onChange={(e) => setSelectedCityPair(e.target.value)}
                                >
                                    {cityPairs.map(cp => (
                                        <option key={cp} value={cp} className="bg-bg-primary text-white">{cp} Pair</option>
                                    ))}
                                </select>
                            </div>
                        </div>

                        <div className="relative flex-grow max-w-md hidden md:block">
                            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-text-secondary" size={18} />
                            <input type="text" placeholder="Search by Airline or Flight..." className="input-field pl-10" />
                        </div>
                        
                        <button 
                            className="btn-primary" 
                            onClick={generateSlots}
                            disabled={generating}
                        >
                            {generating ? <Loader2 className="animate-spin" size={18} /> : <Clock size={18} />}
                            Run Rationalization Algorithm
                        </button>
                    </div>

                    {/* Stats */}
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        {[
                            { label: 'Total DB Slots', value: slots.length, icon: <Plane className="text-accent-primary" /> },
                            { label: 'Approved', value: slots.filter(s => s.status === 'approved').length, icon: <CheckCircle className="text-green-400" /> },
                            { label: 'Pending', value: slots.filter(s => s.status === 'pending').length, icon: <Clock className="text-accent-secondary" /> },
                            { label: 'Rejected', value: slots.filter(s => s.status === 'rejected').length, icon: <AlertCircle className="text-accent-tertiary" /> },
                        ].map((stat, i) => (
                            <motion.div 
                                key={stat.label}
                                initial={{ opacity: 0, scale: 0.9 }}
                                animate={{ opacity: 1, scale: 1 }}
                                whileHover={{ scale: 1.05 }}
                                transition={{ delay: 0.1 * i }}
                                className="glass-panel flex items-center justify-between p-6 cursor-default"
                            >
                                <div>
                                    <p className="text-text-secondary text-sm mb-1">{stat.label}</p>
                                    <h4 className="text-2xl font-bold">{stat.value}</h4>
                                </div>
                                <div className="p-3 rounded-full bg-white/5">
                                    {stat.icon}
                                </div>
                            </motion.div>
                        ))}
                    </div>

                    {/* Data Table */}
                    <motion.div 
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ delay: 0.5 }}
                        className="glass-panel overflow-x-auto"
                    >
                        <h3 className="text-xl mb-6 flex items-center gap-2">
                            <span className="w-2 h-6 bg-accent-primary rounded-full"></span>
                            Allocation Requests for {selectedCityPair}
                        </h3>
                        
                        <table className="data-table w-full">
                            <thead>
                                <tr>
                                    <th>Slot ID</th>
                                    <th>Airline</th>
                                    <th>Flight No.</th>
                                    <th>Req. Time (UTC)</th>
                                    <th>Block Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <AnimatePresence>
                                    {loading ? (
                                        <tr><td colSpan="7" className="text-center py-8 text-text-secondary">Loading database slots...</td></tr>
                                    ) : slots.length === 0 ? (
                                        <tr><td colSpan="7" className="text-center py-8 text-text-secondary">No slots found. Click 'Run Rationalization Algorithm' to generate slots from live flights.</td></tr>
                                    ) : slots.map((slot, i) => (
                                        <motion.tr 
                                            key={slot.id}
                                            initial={{ opacity: 0, x: -20 }}
                                            animate={{ opacity: 1, x: 0 }}
                                            exit={{ opacity: 0, x: 20 }}
                                            whileHover={{ backgroundColor: 'rgba(255,255,255,0.05)', scale: 1.01 }}
                                            transition={{ delay: 0.05 * i, duration: 0.2 }}
                                        >
                                            <td className="font-mono text-text-secondary text-sm">{slot.slot_id}</td>
                                            <td className="font-semibold flex items-center gap-2">
                                                <div className="w-6 h-6 rounded bg-white/10 flex items-center justify-center text-xs border border-white/5">
                                                    {slot.airline.charAt(0)}
                                                </div>
                                                {slot.airline}
                                            </td>
                                            <td className="font-mono">{slot.flight}</td>
                                            <td className="font-mono">{slot.time}</td>
                                            <td className="font-mono text-accent-primary">{slot.blockTime || slot.block_time}</td>
                                            <td>
                                                <motion.span 
                                                    layout
                                                    className={`status-badge status-${slot.status}`}
                                                >
                                                    {slot.status}
                                                </motion.span>
                                            </td>
                                            <td className="flex gap-3">
                                                {slot.status !== 'approved' && (
                                                    <button 
                                                        onClick={() => updateStatus(slot.id, 'approved')}
                                                        className="text-green-400 hover:text-green-300 hover:underline transition-colors text-sm font-semibold"
                                                    >
                                                        Approve
                                                    </button>
                                                )}
                                                {slot.status !== 'rejected' && (
                                                    <button 
                                                        onClick={() => updateStatus(slot.id, 'rejected')}
                                                        className="text-red-400 hover:text-red-300 hover:underline transition-colors text-sm font-semibold"
                                                    >
                                                        Reject
                                                    </button>
                                                )}
                                                {slot.status !== 'pending' && (
                                                    <button 
                                                        onClick={() => updateStatus(slot.id, 'pending')}
                                                        className="text-yellow-400 hover:text-yellow-300 hover:underline transition-colors text-sm font-semibold"
                                                    >
                                                        Reset
                                                    </button>
                                                )}
                                            </td>
                                        </motion.tr>
                                    ))}
                                </AnimatePresence>
                            </tbody>
                        </table>
                    </motion.div>
                </div>
            </main>
        </div>
    );
}
