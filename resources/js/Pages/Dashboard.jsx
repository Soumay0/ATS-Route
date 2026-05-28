import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { useEffect, useState } from 'react';
import { Plane, Activity, CloudLightning, Map as MapIcon, Calendar, Thermometer } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import axios from 'axios';

export default function Dashboard() {
    const { t } = useTranslation();
    const user = usePage().props.auth.user;
    const [flights, setFlights] = useState([]);
    const [loading, setLoading] = useState(true);

    const fetchFlights = async () => {
        try {
            const response = await axios.get('/api/flights');
            setFlights(response.data || []);
            setLoading(false);
        } catch (error) {
            console.error("Error fetching flights:", error);
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchFlights();
        const interval = setInterval(fetchFlights, 10000); // Auto-refresh every 10s
        return () => clearInterval(interval);
    }, []);

    const activeFlightsCount = flights.length;
    const groundedCount = flights.filter(f => f.on_ground).length;
    const avgSpeedMs = activeFlightsCount > 0 ? flights.reduce((acc, f) => acc + (f.velocity || 0), 0) / activeFlightsCount : 0;
    const avgSpeedKnots = Math.round(avgSpeedMs * 1.94384);
    const recentFlights = flights.slice(0, 8);

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-2xl font-bold font-heading text-text-primary leading-tight">
                    {t('dashboard')}
                </h2>
            }
        >
            <Head title={`${t('dashboard')} | AeroRoute`} />

            <div className="flex flex-col gap-6">
                <motion.div initial={{ opacity: 0, y: 10 }} animate={{ opacity: 1, y: 0 }} className="mb-4">
                    <h1 className="text-4xl font-heading font-extrabold text-text-primary tracking-tight">
                        Welcome back, <span className="text-gradient">{user.name.split(' ')[0]}</span>.
                    </h1>
                    <p className="text-text-secondary mt-2">Live global tracking is active. The network is optimal.</p>
                </motion.div>

                {/* Stats Row */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <StatCard 
                        title={t('active_flights')} 
                        value={loading ? '...' : activeFlightsCount.toLocaleString()} 
                        icon={<Plane size={24} />} 
                        colorClass="text-accent-primary bg-accent-primary/20"
                        delay={0.1}
                    />
                    <StatCard 
                        title="Grounded Planes" 
                        value={loading ? '...' : groundedCount.toLocaleString()} 
                        icon={<Activity size={24} />} 
                        colorClass="text-accent-secondary bg-accent-secondary/20"
                        delay={0.2}
                    />
                    <StatCard 
                        title="Average Speed" 
                        value={loading ? '...' : `${avgSpeedKnots} kts`} 
                        icon={<Plane size={24} />} 
                        colorClass="text-accent-tertiary bg-accent-tertiary/20"
                        delay={0.3}
                    />
                    <StatCard 
                        title={t('active_weather')} 
                        value="0 Alerts" 
                        icon={<CloudLightning size={24} />} 
                        colorClass="text-yellow-500 bg-yellow-500/20"
                        delay={0.4}
                    />
                </div>

                <div className="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-4">
                    {/* Recent Flights Table */}
                    <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.5 }} className="glass-panel xl:col-span-2">
                        <div className="flex justify-between items-center mb-6 border-b border-border-light pb-4">
                            <h3 className="text-xl font-semibold text-text-primary">{t('recent_flights')}</h3>
                        </div>
                        
                        <div className="overflow-x-auto">
                            <table className="w-full text-left">
                                <thead>
                                    <tr className="text-text-tertiary text-sm uppercase tracking-wider border-b border-border-light">
                                        <th className="pb-3">{t('callsign')}</th>
                                        <th className="pb-3">{t('origin_country')}</th>
                                        <th className="pb-3">{t('altitude')}</th>
                                        <th className="pb-3">{t('speed')}</th>
                                        <th className="pb-3 text-right">{t('status')}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {loading ? (
                                        <tr><td colSpan="5" className="text-center py-8 text-text-secondary">Syncing with radar network...</td></tr>
                                    ) : recentFlights.map((f, idx) => (
                                        <tr key={idx} className="border-b border-border-light hover:bg-hover-bg transition-colors">
                                            <td className="py-4 font-bold text-text-primary">{f.callsign || f.icao24}</td>
                                            <td className="py-4 text-text-secondary">{f.origin_country}</td>
                                            <td className="py-4 text-text-secondary">{f.altitude ? `${Math.round(f.altitude * 3.28084)} ft` : '-'}</td>
                                            <td className="py-4 text-text-secondary">{f.velocity ? `${Math.round(f.velocity * 1.94384)} kts` : '-'}</td>
                                            <td className="py-4 text-right">
                                                <span className={`inline-flex px-3 py-1 rounded-full text-xs font-bold ${f.on_ground ? 'bg-text-tertiary/20 text-text-tertiary' : 'bg-accent-primary/20 text-accent-primary border border-accent-primary/30 shadow-[0_0_10px_rgba(0,240,255,0.2)]'}`}>
                                                    {f.on_ground ? t('on_ground') : t('in_air')}
                                                </span>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </motion.div>

                    {/* Quick Actions */}
                    <div className="flex flex-col gap-6">
                        <QuickAction 
                            href={route('network')} 
                            icon={<MapIcon size={32} />} 
                            title={t('launch_map')} 
                            colorClass="text-accent-primary bg-accent-primary/10 border-accent-primary" 
                            delay={0.6}
                        />
                        <QuickAction 
                            href={route('slots')} 
                            icon={<Calendar size={32} />} 
                            title={t('slot_management')} 
                            colorClass="text-accent-secondary bg-accent-secondary/10 border-accent-secondary" 
                            delay={0.7}
                        />
                        <QuickAction 
                            href={route('weather')} 
                            icon={<Thermometer size={32} />} 
                            title={t('check_weather')} 
                            colorClass="text-accent-tertiary bg-accent-tertiary/10 border-accent-tertiary" 
                            delay={0.8}
                        />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

function StatCard({ title, value, icon, colorClass, delay }) {
    return (
        <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay }} className="glass-panel flex items-center gap-4">
            <div className={`p-4 rounded-xl ${colorClass}`}>
                {icon}
            </div>
            <div>
                <p className="text-text-secondary text-sm font-semibold uppercase">{title}</p>
                <h3 className="text-2xl font-bold text-text-primary mt-1">{value}</h3>
            </div>
        </motion.div>
    );
}

function QuickAction({ href, icon, title, colorClass, delay }) {
    return (
        <motion.div initial={{ opacity: 0, x: 20 }} animate={{ opacity: 1, x: 0 }} transition={{ delay }} className="glass-panel flex flex-col items-center justify-center text-center gap-4 cursor-pointer hover:-translate-y-1 hover:shadow-lg transition-transform duration-300">
            <a href={href} className="flex flex-col items-center gap-3 w-full">
                <div className={`p-4 rounded-full ${colorClass}`}>{icon}</div>
                <h4 className="font-bold text-lg text-text-primary">{title}</h4>
            </a>
        </motion.div>
    );
}
