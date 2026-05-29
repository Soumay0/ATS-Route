@extends('layouts.public')

@section('title', 'Verify Email | AeroRoute')

@section('content')
<section class="mx-auto flex min-h-[calc(100vh-92px)] max-w-7xl items-center justify-center px-4 py-16 md:px-8">
    <div class="glass-panel w-full max-w-lg rounded-[2rem] p-8 text-center">
        <h1 class="font-heading text-3xl uppercase tracking-[0.18em] text-white">Verify Email</h1>
        <p class="mt-4 text-text-secondary">A verification link has been sent to your email address.</p>

        @if($status)
            <div class="mt-6 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">{{ $status }}</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mt-8">
            @csrf
            <button type="submit" class="btn-primary w-full justify-center">Resend Verification Email</button>
        </form>
    </div>
</section>
@endsection