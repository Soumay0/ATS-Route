import ApplicationLogo from '@/Components/ApplicationLogo';
import Dropdown from '@/Components/Dropdown';
import NavLink from '@/Components/NavLink';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import CursorTrail from '@/Components/CursorTrail';
import { Link, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { Plane, Moon, Sun, Globe } from 'lucide-react';
import { useTheme } from '../Contexts/ThemeContext';
import { useTranslation } from 'react-i18next';

export default function AuthenticatedLayout({ header, children }) {
    const user = usePage().props.auth.user;
    const { theme, toggleTheme } = useTheme();
    const { t, i18n } = useTranslation();

    const [showingNavigationDropdown, setShowingNavigationDropdown] = useState(false);

    return (
        <div className="layout-container bg-gradient-radial min-h-screen relative">
            <CursorTrail />
            <nav className="navbar">
                <div className="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16 w-full">
                    <div className="flex items-center">
                        <Link href="/" className="flex items-center gap-2">
                            <Plane className="text-accent-primary" size={28} />
                            <span className="text-gradient font-heading font-bold text-xl tracking-wide">AeroRoute</span>
                        </Link>

                        <div className="hidden space-x-8 sm:-my-px sm:flex ml-10">
                            <NavLink
                                href={route('dashboard')}
                                active={route().current('dashboard')}
                                className={route().current('dashboard') ? 'nav-link active' : 'nav-link'}
                            >
                                {t('dashboard')}
                            </NavLink>
                            <NavLink
                                href={route('network')}
                                active={route().current('network')}
                                className={route().current('network') ? 'nav-link active' : 'nav-link'}
                            >
                                {t('ats_network')}
                            </NavLink>
                            <NavLink
                                href={route('slots')}
                                active={route().current('slots')}
                                className={route().current('slots') ? 'nav-link active' : 'nav-link'}
                            >
                                {t('slot_management')}
                            </NavLink>
                            <NavLink
                                href={route('weather')}
                                active={route().current('weather')}
                                className={route().current('weather') ? 'nav-link active' : 'nav-link'}
                            >
                                {t('check_weather')}
                            </NavLink>
                        </div>
                    </div>

                    <div className="hidden sm:flex sm:items-center gap-4">
                        <button 
                            onClick={toggleTheme} 
                            className="p-2 rounded-full hover:bg-hover-bg transition-colors text-text-secondary hover:text-accent-primary"
                            aria-label="Toggle Theme"
                        >
                            {theme === 'dark' ? <Sun size={20} /> : <Moon size={20} />}
                        </button>

                        <div className="relative ms-3 z-50">
                            <Dropdown>
                                <Dropdown.Trigger>
                                    <span className="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            className="inline-flex items-center rounded-full border border-border-light bg-hover-bg px-4 py-2 text-sm font-medium text-text-primary transition duration-150 ease-in-out hover:border-accent-primary focus:outline-none"
                                        >
                                            <Globe className="mr-2 text-accent-secondary" size={16} />
                                            {i18n.language ? i18n.language.toUpperCase() : 'EN'}
                                        </button>
                                    </span>
                                </Dropdown.Trigger>
                                <Dropdown.Content>
                                    <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('en')}>English (EN)</button>
                                    <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('hi')}>हिन्दी (HI)</button>
                                    <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('bn')}>বাংলা (BN)</button>
                                    <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('te')}>తెలుగు (TE)</button>
                                    <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('mr')}>मराठी (MR)</button>
                                    <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('ta')}>தமிழ் (TA)</button>
                                    <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('gu')}>ગુજરાતી (GU)</button>
                                    <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('kn')}>ಕನ್ನಡ (KN)</button>
                                    <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('or')}>ଓଡ଼ିଆ (OR)</button>
                                    <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('ml')}>മലയാളം (ML)</button>
                                    <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-text-primary hover:bg-hover-bg" onClick={() => i18n.changeLanguage('pa')}>ਪੰਜਾਬੀ (PA)</button>
                                </Dropdown.Content>
                            </Dropdown>
                        </div>

                        <div className="relative ms-3 z-50">
                            <Dropdown>
                                <Dropdown.Trigger>
                                    <span className="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            className="inline-flex items-center rounded-full border border-border-light bg-hover-bg px-4 py-2 text-sm font-medium text-text-primary transition duration-150 ease-in-out hover:border-accent-primary focus:outline-none"
                                        >
                                            {user.name}

                                            <svg
                                                className="-me-0.5 ms-2 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fillRule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clipRule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </Dropdown.Trigger>

                                <Dropdown.Content>
                                    <Dropdown.Link href={route('profile.edit')}>
                                        {t('profile')}
                                    </Dropdown.Link>
                                    <Dropdown.Link
                                        href={route('logout')}
                                        method="post"
                                        as="button"
                                    >
                                        {t('logout')}
                                    </Dropdown.Link>
                                </Dropdown.Content>
                            </Dropdown>
                        </div>
                    </div>

                    <div className="-me-2 flex items-center sm:hidden">
                        <button 
                            onClick={toggleTheme} 
                            className="p-2 mr-2 rounded-full hover:bg-hover-bg transition-colors text-text-secondary"
                        >
                            {theme === 'dark' ? <Sun size={20} /> : <Moon size={20} />}
                        </button>
                        <button
                            onClick={() => setShowingNavigationDropdown((previousState) => !previousState)}
                            className="inline-flex items-center justify-center rounded-md p-2 text-text-secondary transition duration-150 ease-in-out hover:bg-hover-bg focus:outline-none"
                        >
                            <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path
                                    className={!showingNavigationDropdown ? 'inline-flex' : 'hidden'}
                                    strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    className={showingNavigationDropdown ? 'inline-flex' : 'hidden'}
                                    strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <div className={(showingNavigationDropdown ? 'block' : 'hidden') + ' sm:hidden bg-navbar-bg border-b border-border-light w-full absolute top-16 left-0'}>
                    <div className="space-y-1 pb-3 pt-2 px-2">
                        <ResponsiveNavLink href={route('dashboard')} active={route().current('dashboard')}>
                            {t('dashboard')}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href={route('network')} active={route().current('network')}>
                            {t('ats_network')}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href={route('slots')} active={route().current('slots')}>
                            {t('slot_management')}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href={route('weather')} active={route().current('weather')}>
                            {t('check_weather')}
                        </ResponsiveNavLink>
                    </div>
                    <div className="border-t border-border-light pb-1 pt-4">
                        <div className="px-4">
                            <div className="text-base font-medium text-text-primary">{user.name}</div>
                            <div className="text-sm font-medium text-text-secondary">{user.email}</div>
                        </div>
                        <div className="mt-3 space-y-1">
                            <ResponsiveNavLink href={route('profile.edit')}>{t('profile')}</ResponsiveNavLink>
                            <ResponsiveNavLink method="post" href={route('logout')} as="button">{t('logout')}</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            {header && (
                <header className="bg-bg-card border-b border-border-light backdrop-blur">
                    <div className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {header}
                    </div>
                </header>
            )}

            <main className="main-content mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">{children}</main>
        </div>
    );
}
