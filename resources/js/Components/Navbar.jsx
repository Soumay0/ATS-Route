import React from 'react';
import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Plane, Map, Clock, LogIn, Moon, Sun, Globe } from 'lucide-react';
import { useTheme } from '../Contexts/ThemeContext';
import { useTranslation } from 'react-i18next';
import Dropdown from '@/Components/Dropdown';

export default function Navbar({ auth }) {
    const { theme, toggleTheme } = useTheme();
    const { t, i18n } = useTranslation();

    return (
        <motion.nav 
            className="navbar"
            initial={{ y: -100 }}
            animate={{ y: 0 }}
            transition={{ duration: 0.5 }}
        >
            <div className="logo-text">
                <Plane className="text-accent-primary" size={28} />
                <span className="text-gradient font-heading font-bold text-xl tracking-wide">AeroRoute</span>
            </div>
            
            <div className="nav-links hidden md:flex">
                <Link href="/" className={route().current('home') ? 'nav-link active' : 'nav-link'}>
                    Home
                </Link>
                <Link href="/live-flights" className={route().current('live-flights') ? 'nav-link active' : 'nav-link'}>
                    Live Radar
                </Link>
                <Link href="/weather" className={route().current('weather') ? 'nav-link active' : 'nav-link'}>
                    {t('check_weather') || 'Weather'}
                </Link>
                <Link href="/network" className={route().current('network') ? 'nav-link active' : 'nav-link'}>
                    {t('ats_network') || 'ATS Network'}
                </Link>
                <Link href="/slots" className={route().current('slots') ? 'nav-link active' : 'nav-link'}>
                    {t('slot_management') || 'Slot Allocation'}
                </Link>
            </div>

            <div className="flex items-center gap-6">
                <button 
                    onClick={toggleTheme} 
                    className="p-2 rounded-full hover:bg-hover-bg transition-colors text-text-secondary hover:text-accent-primary"
                    aria-label="Toggle Theme"
                >
                    {theme === 'dark' ? <Sun size={20} /> : <Moon size={20} />}
                </button>

                <div className="relative z-50">
                    <Dropdown>
                        <Dropdown.Trigger>
                            <span className="inline-flex rounded-md">
                                <button type="button" className="inline-flex items-center rounded-full border border-border-light bg-hover-bg px-3 py-1.5 text-sm font-medium text-text-primary transition hover:border-accent-primary">
                                    <Globe className="mr-2 text-accent-secondary" size={16} />
                                    {i18n.language ? i18n.language.toUpperCase() : 'EN'}
                                </button>
                            </span>
                        </Dropdown.Trigger>
                        <Dropdown.Content>
                            <button className="block w-full px-4 py-2 text-left text-sm text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('en')}>English</button>
                            <button className="block w-full px-4 py-2 text-left text-sm text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('hi')}>हिन्दी</button>
                            <button className="block w-full px-4 py-2 text-left text-sm text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('bn')}>বাংলা</button>
                            <button className="block w-full px-4 py-2 text-left text-sm text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('te')}>తెలుగు</button>
                            <button className="block w-full px-4 py-2 text-left text-sm text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('mr')}>मराठी</button>
                            <button className="block w-full px-4 py-2 text-left text-sm text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('ta')}>தமிழ்</button>
                            <button className="block w-full px-4 py-2 text-left text-sm text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('gu')}>ગુજરાતી</button>
                            <button className="block w-full px-4 py-2 text-left text-sm text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('kn')}>ಕನ್ನಡ</button>
                            <button className="block w-full px-4 py-2 text-left text-sm text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('or')}>ଓଡ଼ିଆ</button>
                            <button className="block w-full px-4 py-2 text-left text-sm text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('ml')}>മലയാളം</button>
                            <button className="block w-full px-4 py-2 text-left text-sm text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('pa')}>ਪੰਜਾਬੀ</button>
                        </Dropdown.Content>
                    </Dropdown>
                </div>

                {auth?.user ? (
                    <Link href={route('dashboard')} className="btn-primary">
                        {t('dashboard') || 'Dashboard'}
                    </Link>
                ) : (
                    <div className="flex gap-4">
                        <Link href={route('login')} className="nav-link flex items-center gap-2">
                            <LogIn size={18} /> Login
                        </Link>
                        <Link href={route('register')} className="btn-primary">
                            Sign Up
                        </Link>
                    </div>
                )}
            </div>
        </motion.nav>
    );
}
