@extends('layouts.dashboard')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="grid gap-6 xl:grid-cols-2">
    <div class="glass-panel rounded-[2rem] p-6 space-y-4">
        <h2 class="text-2xl font-semibold text-white">Interface</h2>
        <div class="rounded-2xl border border-border-light bg-white/5 p-4 text-text-secondary">Theme toggle is available in the top bar and persists in local storage.</div>
        <div class="rounded-2xl border border-border-light bg-white/5 p-4 text-text-secondary">Configure dashboard preferences, notifications, and map defaults here.</div>
    </div>
    <div class="glass-panel rounded-[2rem] p-6 space-y-4">
        <h2 class="text-2xl font-semibold text-white">Deployment checklist</h2>
        <div class="rounded-2xl border border-border-light bg-white/5 p-4 text-text-secondary">Set `GOOGLE_MAPS_API_KEY` in your environment.</div>
        <div class="rounded-2xl border border-border-light bg-white/5 p-4 text-text-secondary">Point the application to MySQL in the `.env` file before migration.</div>
    </div>
</div>
@endsection