import { useEffect } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { Mail, Lock, User, UserPlus } from 'lucide-react';
import { motion } from 'framer-motion';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
        post(route('register'));
    };

    return (
        <GuestLayout>
            <Head title="Register | AeroRoute" />

            <div className="mb-6 text-center">
                <h2 className="text-3xl font-bold font-heading mb-2 text-text-primary">Request Access</h2>
                <p className="text-text-secondary text-sm">Create an account to join the ATS network</p>
            </div>

            <form onSubmit={submit} className="flex flex-col gap-4">
                <div>
                    <label className="input-label" htmlFor="name">Full Name</label>
                    <div className="relative">
                        <User className="absolute left-3 top-1/2 transform -translate-y-1/2 text-text-secondary" size={18} />
                        <input
                            id="name"
                            name="name"
                            value={data.name}
                            className="input-field pl-10 bg-hover-bg border-border-light text-text-primary placeholder-gray-500 focus:border-accent-primary focus:ring-1 focus:ring-accent-primary w-full rounded-lg transition-all"
                            placeholder="Captain Smith"
                            autoComplete="name"
                            onChange={(e) => setData('name', e.target.value)}
                            required
                        />
                    </div>
                    {errors.name && <p className="text-red-400 text-xs mt-1">{errors.name}</p>}
                </div>

                <div>
                    <label className="input-label" htmlFor="email">Email Address</label>
                    <div className="relative">
                        <Mail className="absolute left-3 top-1/2 transform -translate-y-1/2 text-text-secondary" size={18} />
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value={data.email}
                            className="input-field pl-10 bg-hover-bg border-border-light text-text-primary placeholder-gray-500 focus:border-accent-primary focus:ring-1 focus:ring-accent-primary w-full rounded-lg transition-all"
                            placeholder="pilot@aeroroute.com"
                            autoComplete="username"
                            onChange={(e) => setData('email', e.target.value)}
                            required
                        />
                    </div>
                    {errors.email && <p className="text-red-400 text-xs mt-1">{errors.email}</p>}
                </div>

                <div>
                    <label className="input-label" htmlFor="password">Password</label>
                    <div className="relative">
                        <Lock className="absolute left-3 top-1/2 transform -translate-y-1/2 text-text-secondary" size={18} />
                        <input
                            id="password"
                            type="password"
                            name="password"
                            value={data.password}
                            className="input-field pl-10 bg-hover-bg border-border-light text-text-primary placeholder-gray-500 focus:border-accent-primary focus:ring-1 focus:ring-accent-primary w-full rounded-lg transition-all"
                            placeholder="••••••••"
                            autoComplete="new-password"
                            onChange={(e) => setData('password', e.target.value)}
                            required
                        />
                    </div>
                    {errors.password && <p className="text-red-400 text-xs mt-1">{errors.password}</p>}
                </div>

                <div>
                    <label className="input-label" htmlFor="password_confirmation">Confirm Password</label>
                    <div className="relative">
                        <Lock className="absolute left-3 top-1/2 transform -translate-y-1/2 text-text-secondary" size={18} />
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            value={data.password_confirmation}
                            className="input-field pl-10 bg-hover-bg border-border-light text-text-primary placeholder-gray-500 focus:border-accent-primary focus:ring-1 focus:ring-accent-primary w-full rounded-lg transition-all"
                            placeholder="••••••••"
                            autoComplete="new-password"
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            required
                        />
                    </div>
                    {errors.password_confirmation && <p className="text-red-400 text-xs mt-1">{errors.password_confirmation}</p>}
                </div>

                <motion.button 
                    whileHover={{ scale: 1.02 }}
                    whileTap={{ scale: 0.98 }}
                    className="btn-primary w-full mt-4 flex justify-center items-center gap-2 py-3" 
                    disabled={processing}
                >
                    <UserPlus size={18} />
                    {processing ? 'REGISTERING...' : 'CREATE ACCOUNT'}
                </motion.button>

                <p className="text-center text-sm text-text-secondary mt-2">
                    Already have an account? <Link href={route('login')} className="text-accent-secondary hover:text-text-primary transition-colors font-semibold">Login</Link>
                </p>
            </form>
        </GuestLayout>
    );
}
