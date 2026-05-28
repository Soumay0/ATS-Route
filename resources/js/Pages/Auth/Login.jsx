import { useEffect } from 'react';
import Checkbox from '@/Components/Checkbox';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { Mail, Lock, LogIn } from 'lucide-react';
import { motion } from 'framer-motion';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
        post(route('login'));
    };

    return (
        <GuestLayout>
            <Head title="Log in | AeroRoute" />

            <div className="mb-8 text-center">
                <h2 className="text-3xl font-bold font-heading mb-2 text-text-primary">Welcome Back</h2>
                <p className="text-text-secondary text-sm">Enter your credentials to access the network</p>
            </div>

            {status && <div className="mb-4 font-medium text-sm text-green-400 bg-green-400/10 p-3 rounded border border-green-400/20">{status}</div>}

            <form onSubmit={submit} className="flex flex-col gap-5">
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
                            autoComplete="current-password"
                            onChange={(e) => setData('password', e.target.value)}
                            required
                        />
                    </div>
                    {errors.password && <p className="text-red-400 text-xs mt-1">{errors.password}</p>}
                </div>

                <div className="flex items-center justify-between mt-2">
                    <label className="flex items-center cursor-pointer group">
                        <div className="relative flex items-center">
                            <input
                                type="checkbox"
                                name="remember"
                                className="peer h-4 w-4 cursor-pointer appearance-none rounded border border-border-light bg-hover-bg checked:border-accent-primary checked:bg-accent-primary transition-all"
                                checked={data.remember}
                                onChange={(e) => setData('remember', e.target.checked)}
                            />
                            <svg className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-black opacity-0 peer-checked:opacity-100 w-3 h-3 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="4" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <span className="ml-2 text-sm text-text-secondary group-hover:text-text-primary transition-colors">Remember me</span>
                    </label>

                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="text-sm text-accent-primary hover:text-text-primary transition-colors underline-offset-4 hover:underline"
                        >
                            Forgot password?
                        </Link>
                    )}
                </div>

                <motion.button 
                    whileHover={{ scale: 1.02 }}
                    whileTap={{ scale: 0.98 }}
                    className="btn-primary w-full mt-4 flex justify-center items-center gap-2 py-3" 
                    disabled={processing}
                >
                    <LogIn size={18} />
                    {processing ? 'AUTHENTICATING...' : 'SECURE LOGIN'}
                </motion.button>
                
                <p className="text-center text-sm text-text-secondary mt-4">
                    Don't have an clearance? <Link href={route('register')} className="text-accent-secondary hover:text-text-primary transition-colors font-semibold">Request Access</Link>
                </p>
            </form>
        </GuestLayout>
    );
}
