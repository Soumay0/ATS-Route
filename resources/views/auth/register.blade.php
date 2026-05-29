@extends('layouts.public')

@section('title', 'Register | AeroRoute')

@section('content')
<section class="mx-auto flex min-h-[calc(100vh-92px)] max-w-7xl items-center justify-center px-4 py-16 md:px-8">
    <div class="glass-panel w-full max-w-lg rounded-[2rem] p-8 bg-bg-card-solid/90 backdrop-blur-xl border border-border-light shadow-2xl">
        <div class="mb-8 text-center">
            <h1 class="font-heading text-3xl uppercase tracking-[0.18em] text-text-primary">Create Access</h1>
            <p class="mt-3 text-text-secondary">Register for the aviation command dashboard.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-400/30 bg-red-400/10 px-4 py-3 text-sm text-red-600 dark:text-red-200">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <label class="block space-y-2">
                <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Name</span>
                <input type="text" name="name" value="{{ old('name') }}" class="auth-input" placeholder="Full name" required autofocus>
            </label>
            <label class="block space-y-2">
                <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Email</span>
                <input type="email" name="email" value="{{ old('email') }}" class="auth-input" placeholder="name@company.com" required>
            </label>
            <label class="block space-y-2">
                <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Password</span>
                <input type="password" name="password" class="auth-input" placeholder="••••••••" required>
            </label>
            <label class="block space-y-2">
                <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Confirm Password</span>
                <input type="password" name="password_confirmation" class="auth-input" placeholder="••••••••" required>
            </label>

            <button type="submit" class="btn-primary w-full justify-center">Create Account</button>
        </form>

        <div class="mt-6 text-sm text-text-secondary">
            Already have an account? <a href="{{ route('login') }}" class="text-accent-primary hover:text-text-primary">Sign in</a>
        </div>
    </div>
</section>
@endsection