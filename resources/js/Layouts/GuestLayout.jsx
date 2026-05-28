import { Link } from '@inertiajs/react';
import { Plane } from 'lucide-react';
import { motion } from 'framer-motion';
import CursorTrail from '@/Components/CursorTrail';

export default function GuestLayout({ children }) {
    return (
        <div className="flex min-h-screen bg-bg-primary font-sans text-text-primary overflow-hidden">
            <CursorTrail />
            
            {/* Left Side: Visual / Branding */}
            <div className="hidden lg:flex lg:w-1/2 relative bg-gradient-radial items-center justify-center p-12 overflow-hidden border-r border-border-light">
                {/* Map Grid Background */}
                <div className="absolute inset-0 opacity-20" 
                     style={{ 
                        backgroundImage: 'linear-gradient(rgba(255,255,255,0.2) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.2) 1px, transparent 1px)',
                        backgroundSize: '40px 40px' 
                     }}>
                </div>

                {/* Animated radar sweep */}
                <div className="absolute top-1/2 left-1/2 w-[800px] h-[800px] -mt-[400px] -ml-[400px] rounded-full border border-accent-primary/20 pointer-events-none">
                    <motion.div 
                        animate={{ rotate: 360 }}
                        transition={{ duration: 10, repeat: Infinity, ease: "linear" }}
                        className="w-1/2 h-1/2 origin-bottom-right"
                        style={{ background: 'conic-gradient(from 180deg, transparent 0deg, rgba(0, 240, 255, 0.3) 90deg, transparent 90deg)' }}
                    />
                </div>

                <motion.div 
                    initial={{ opacity: 0, x: -50 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ duration: 0.8 }}
                    className="relative z-10 flex flex-col gap-6"
                >
                    <Link href="/" className="logo-text text-5xl">
                        <Plane className="text-accent-primary" size={56} />
                        <span className="text-gradient font-bold tracking-tight">AeroRoute</span>
                    </Link>
                    <p className="text-xl text-text-secondary max-w-md font-light leading-relaxed">
                        The ultimate intelligent ATS Network map and Slot Allocation Rationalizer. 
                        Experience real-time aviation data like never before.
                    </p>
                </motion.div>
            </div>

            {/* Right Side: Form */}
            <div className="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative">
                {/* Mobile Logo (only shows on small screens) */}
                <div className="absolute top-8 left-8 lg:hidden">
                    <Link href="/" className="logo-text text-2xl">
                        <Plane className="text-accent-primary" size={28} />
                        <span className="text-gradient font-bold">AeroRoute</span>
                    </Link>
                </div>

                {/* Glassmorphism Form Container */}
                <motion.div 
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, delay: 0.2 }}
                    className="w-full max-w-md glass-panel relative z-10"
                >
                    {children}
                </motion.div>
                
                {/* Decorative background blurs for right side */}
                <div className="absolute top-1/4 right-1/4 w-64 h-64 bg-accent-secondary/20 rounded-full blur-[100px] pointer-events-none"></div>
                <div className="absolute bottom-1/4 left-1/4 w-64 h-64 bg-accent-primary/20 rounded-full blur-[100px] pointer-events-none"></div>
            </div>
        </div>
    );
}
