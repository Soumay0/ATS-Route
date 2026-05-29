@extends('layouts.public')

@section('title', 'Login | AeroRoute')

@section('content')
<section class="mx-auto flex min-h-[calc(100vh-92px)] max-w-7xl items-center justify-center px-4 py-16 md:px-8">
    <div class="glass-panel w-full max-w-lg rounded-[2rem] p-8 bg-bg-card-solid/90 backdrop-blur-xl border border-border-light shadow-2xl">
        <div class="mb-8 text-center">
            <h1 class="font-heading text-3xl uppercase tracking-[0.18em] text-text-primary">Control Access</h1>
            <p class="mt-3 text-text-secondary">Sign in to the aviation control dashboard.</p>
        </div>

        @if($status)
            <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-600 dark:text-emerald-200">{{ $status }}</div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-400/30 bg-red-400/10 px-4 py-3 text-sm text-red-600 dark:text-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <label class="block space-y-2">
                <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Email</span>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-border-light bg-bg-primary px-4 py-3 text-text-primary focus:border-accent-primary focus:outline-none" required autofocus>
            </label>
            <label class="block space-y-2">
                <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Password</span>
                <input type="password" name="password" class="w-full rounded-2xl border border-border-light bg-bg-primary px-4 py-3 text-text-primary focus:border-accent-primary focus:outline-none" required>
            </label>

            <label class="flex items-center gap-3 text-sm text-text-secondary">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-border-light bg-bg-primary text-accent-primary focus:ring-accent-primary">
                Remember me
            </label>

            <button type="submit" class="btn-primary w-full justify-center">Sign In</button>
        </form>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-3 text-sm text-text-secondary">
            @if($canResetPassword)
                <a href="{{ route('password.request') }}" class="text-accent-primary hover:text-text-primary">Forgot password?</a>
            @endif
            <a href="{{ route('register') }}" class="text-accent-secondary hover:text-text-primary">Create account</a>
        </div>
    </div>
</section>
@endsection