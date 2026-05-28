import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import Navbar from '@/Components/Navbar';
import { Globe, Plane, Navigation, Clock, Map } from 'lucide-react';

export default function Welcome({ auth }) {
    return (
        <div className="layout-container bg-gradient-radial">
            <Head title="AeroRoute | Multi-City Slot Allocation & ATS Network" />
            
            <Navbar auth={auth} />

            <main className="main-content flex flex-col items-center justify-center relative min-h-[90vh]">
                
                {/* Abstract Background Animation elements */}
                <div className="absolute inset-0 overflow-hidden pointer-events-none">
                    <motion.div 
                        animate={{ 
                            x: [0, 100, 0],
                            y: [0, -50, 0],
                            opacity: [0.1, 0.3, 0.1]
                        }}
                        transition={{ duration: 10, repeat: Infinity, ease: "linear" }}
                        className="absolute top-20 left-20 w-64 h-64 bg-accent-primary rounded-full filter blur-[100px]"
                    />
                    <motion.div 
                        animate={{ 
                            x: [0, -100, 0],
                            y: [0, 50, 0],
                            opacity: [0.1, 0.2, 0.1]
                        }}
                        transition={{ duration: 15, repeat: Infinity, ease: "linear" }}
                        className="absolute bottom-20 right-20 w-96 h-96 bg-accent-secondary rounded-full filter blur-[120px]"
                    />
                </div>

                <div className="z-10 text-center max-w-4xl mx-auto px-4 flex flex-col items-center">
                    <motion.div
                        initial={{ opacity: 0, scale: 0.8 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{ duration: 0.8 }}
                        className="mb-6"
                    >
                        <div className="inline-block p-4 rounded-full glass-panel mb-6 animate-pulse-glow">
                            <Globe size={48} className="text-accent-primary" />
                        </div>
                    </motion.div>

                    <motion.h1 
                        initial={{ opacity: 0, y: 30 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.8, delay: 0.2 }}
                        className="text-5xl md:text-7xl font-bold mb-6 tracking-tight leading-tight"
                    >
                        Next-Gen <span className="text-gradient">Aviation</span> Routing
                    </motion.h1>
                    
                    <motion.p 
                        initial={{ opacity: 0, y: 30 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.8, delay: 0.4 }}
                        className="text-xl text-text-secondary mb-10 max-w-3xl leading-relaxed"
                    >
                        Advanced Multiple City Pair Slot Allocation to Airlines with Rationalized Block Time. 
                        Presenting Waypoints, Navigational Aids and creating ATS Route Networks on a global scale.
                    </motion.p>

                    <motion.div 
                        initial={{ opacity: 0, y: 30 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.8, delay: 0.6 }}
                        className="flex flex-wrap justify-center gap-6"
                    >
                        <Link href="/network" className="btn-primary group">
                            <Navigation className="group-hover:rotate-45 transition-transform duration-300" />
                            Explore ATS Network
                        </Link>
                        <Link href="/slots" className="btn-outline">
                            <Clock />
                            Slot Allocation
                        </Link>
                    </motion.div>
                </div>

                {/* Features Grid */}
                <motion.div 
                    initial={{ opacity: 0, y: 50 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.8, delay: 0.8 }}
                    className="grid grid-cols-1 md:grid-cols-3 gap-8 mt-24 z-10 w-full max-w-6xl px-4"
                >
                    <div className="glass-panel text-center flex flex-col items-center">
                        <div className="p-3 rounded-full bg-accent-secondary/20 mb-4 text-accent-secondary">
                            <Plane size={32} />
                        </div>
                        <h3 className="text-xl mb-3 text-white">Rationalized Block Time</h3>
                        <p className="text-text-secondary">Optimized flight scheduling across multiple city pairs ensuring fuel efficiency and minimum delays.</p>
                    </div>

                    <div className="glass-panel text-center flex flex-col items-center transform md:-translate-y-8">
                        <div className="p-3 rounded-full bg-accent-primary/20 mb-4 text-accent-primary">
                            <Map size={32} />
                        </div>
                        <h3 className="text-xl mb-3 text-white">Interactive Waypoints</h3>
                        <p className="text-text-secondary">3D visualization of navigational aids and waypoints structured dynamically for realistic planning.</p>
                    </div>

                    <div className="glass-panel text-center flex flex-col items-center">
                        <div className="p-3 rounded-full bg-accent-tertiary/20 mb-4 text-accent-tertiary">
                            <Clock size={32} />
                        </div>
                        <h3 className="text-xl mb-3 text-white">Slot Allocation Engine</h3>
                        <p className="text-text-secondary">Automated, fair, and high-performance slot distribution matrix for competing airlines.</p>
                    </div>
                </motion.div>
            </main>
        </div>
    );
}
