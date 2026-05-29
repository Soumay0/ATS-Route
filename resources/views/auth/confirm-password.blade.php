@extends('layouts.public')

@section('title', 'Confirm Password | AeroRoute')

@section('content')
<section class="mx-auto flex min-h-[calc(100vh-92px)] max-w-7xl items-center justify-center px-4 py-16 md:px-8">
    <div class="glass-panel w-full max-w-lg rounded-[2rem] p-8">
        <h1 class="font-heading text-3xl uppercase tracking-[0.18em] text-white text-center">Confirm Password</h1>
        <p class="mt-3 text-center text-text-secondary">Please confirm your password to continue.</p>

        @if($errors->any())
            <div class="mt-6 rounded-2xl border border-red-400/30 bg-red-400/10 px-4 py-3 text-sm text-red-200">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 space-y-5">
            @csrf
            <label class="block space-y-2">
                <span class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Password</span>
                <input type="password" name="password" class="w-full rounded-2xl border border-border-light bg-white/5 px-4 py-3 text-white focus:border-accent-primary focus:outline-none" required autofocus>
            </label>
            <button type="submit" class="btn-primary w-full justify-center">Confirm</button>
        </form>
    </div>
</section>
@endsection