@extends('layouts.public')

@section('title', 'Forgot Password | AeroRoute')

@section('content')
<section class="mx-auto flex min-h-[calc(100vh-92px)] max-w-7xl items-center justify-center px-4 py-16 md:px-8">
    <div class="glass-panel w-full max-w-lg rounded-[2rem] p-8">
        <h1 class="font-heading text-3xl uppercase tracking-[0.18em] text-white text-center">Reset Access</h1>
        <p class="mt-3 text-center text-text-secondary">Request a password reset link for your account.</p>

        @if($status)
            <div class="mt-6 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">{{ $status }}</div>
        @endif

        @if($errors->any())
            <div class="mt-6 rounded-2xl border border-red-400/30 bg-red-400/10 px-4 py-3 text-sm text-red-200">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
            @csrf
            <label class="block space-y-2">
                <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Email</span>
                <input type="email" name="email" value="{{ old('email') }}" class="auth-input" placeholder="name@company.com" required autofocus>
            </label>
            <button type="submit" class="btn-primary w-full justify-center">Send Reset Link</button>
        </form>
    </div>
</section>
@endsection