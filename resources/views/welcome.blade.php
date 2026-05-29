@extends('layouts.public')

@section('title', 'AeroRoute | ATS Route Network')

@section('content')
<section class="relative overflow-hidden px-4 pb-20 pt-16 md:px-8 md:pb-28 md:pt-24">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(0,194,255,0.14),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(34,211,238,0.12),transparent_25%)]"></div>

    <div class="relative mx-auto grid max-w-7xl gap-12 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
        <div class="space-y-8">
            <div class="inline-flex items-center gap-2 rounded-full border border-accent-primary/30 bg-accent-primary/10 px-4 py-2 text-xs uppercase tracking-[0.35em] text-accent-primary shadow-[0_0_28px_rgba(0,194,255,0.14)]">
                <i class="fa-solid fa-satellite-dish"></i>
                Google Earth aviation operations
            </div>

            <div class="space-y-5">
                <h1 class="max-w-3xl font-heading text-4xl font-semibold uppercase tracking-[0.1em] text-white md:text-6xl lg:text-7xl">
                    Waypoints, Aids & ATS Routes
                </h1>
                <p class="max-w-xl text-base leading-7 text-text-secondary md:text-lg">
                    Aviation route planning and navigation management, presented in a focused control-center interface.
                </p>
            </div>

            <div class="flex flex-wrap gap-4">
                @if($canLogin)
                    <a href="{{ route('login') }}" class="btn-primary">
                        <i class="fa-solid fa-right-to-bracket"></i> Login
                    </a>
                @endif
                @if($canRegister)
                    <a href="{{ route('register') }}" class="btn-outline">
                        <i class="fa-solid fa-user-plus"></i> Register
                    </a>
                @endif
                <a href="#preview" class="btn-outline">
                    <i class="fa-solid fa-location-crosshairs"></i> Live Preview
                </a>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                @foreach($stats as $label => $value)
                    <div class="glass-panel">
                        <div class="text-sm uppercase tracking-[0.35em] text-text-tertiary">{{ str_replace('_', ' ', ucfirst($label)) }}</div>
                        <div class="mt-3 text-3xl font-semibold text-white">{{ $value }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative">
            <div class="absolute inset-0 rounded-[2rem] border border-accent-primary/20 bg-white/5 blur-0"></div>
            <div class="glass-panel relative overflow-hidden rounded-[2rem] p-6">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(0,194,255,0.16),transparent_45%)]"></div>
                <div class="relative">
                    <div class="mb-4 flex items-center justify-between text-sm text-text-secondary">
                        <span>Radar sweep online</span>
                        <span>GMT+00:00</span>
                    </div>
                    <div class="relative aspect-square overflow-hidden rounded-[1.75rem] border border-border-light bg-[#09111f]">
                        <div class="absolute inset-0 opacity-50" style="background-image: linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 48px 48px;"></div>
                        <div class="absolute inset-0 rounded-full border border-accent-primary/25"></div>
                        <div class="absolute left-1/2 top-1/2 h-1/2 w-1/2 origin-bottom-left animate-[spin_10s_linear_infinite] rounded-full bg-[conic-gradient(from_180deg,transparent_0deg,rgba(0,194,255,0.45)_60deg,transparent_120deg)]"></div>
                        <div class="absolute left-[18%] top-[26%] h-3 w-3 rounded-full bg-accent-secondary shadow-[0_0_18px_rgba(34,211,238,0.95)]"></div>
                        <div class="absolute left-[64%] top-[42%] h-4 w-4 rounded-full bg-accent-primary shadow-[0_0_18px_rgba(0,194,255,0.95)]"></div>
                        <div class="absolute left-[34%] top-[68%] h-3 w-3 rounded-full bg-emerald-400 shadow-[0_0_18px_rgba(74,222,128,0.95)]"></div>
                        <svg viewBox="0 0 400 400" class="absolute inset-0 h-full w-full">
                            <path d="M75 280 C140 180, 210 170, 320 95" fill="none" stroke="#00C2FF" stroke-width="4" stroke-linecap="round" stroke-dasharray="8 10" class="route-line"></path>
                            <path d="M95 120 C170 110, 210 160, 305 240" fill="none" stroke="#22D3EE" stroke-width="3" stroke-linecap="round" stroke-dasharray="10 8" class="route-line-alt"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="mx-auto max-w-7xl px-4 pb-20 md:px-8">
    <div class="mb-10 flex items-end justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-[0.35em] text-accent-primary">Features</p>
            <h2 class="mt-3 font-heading text-3xl text-white md:text-4xl">Operational modules built for controllers</h2>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['Waypoint Visualization', 'fa-location-dot', 'Manage waypoint corridors with precision markers and route annotations.'],
            ['ATS Route Network', 'fa-route', 'Connect multi-point route chains with neon route overlays.'],
            ['Navigational Aid Management', 'fa-tower-cell', 'Maintain VOR, NDB, and DME assets across the airspace.'],
            ['Interactive Google Maps', 'fa-map-location-dot', 'Dark theme maps with live markers, filters, and route tracing.'],
        ] as $feature)
            <div class="glass-panel group hover:-translate-y-1 transition duration-300">
                <div class="mb-5 inline-flex rounded-2xl bg-accent-primary/10 p-4 text-accent-primary shadow-[0_0_24px_rgba(0,194,255,0.12)] group-hover:shadow-[0_0_34px_rgba(0,194,255,0.22)]">
                    <i class="fa-solid {{ $feature[1] }} text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-white">{{ $feature[0] }}</h3>
                <p class="mt-3 text-sm leading-7 text-text-secondary">{{ $feature[2] }}</p>
            </div>
        @endforeach
    </div>
</section>

<section id="preview" class="mx-auto max-w-7xl px-4 pb-20 md:px-8">
    <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
        <div class="space-y-6">
            <p class="text-sm uppercase tracking-[0.35em] text-accent-secondary">Live map preview</p>
            <h2 class="font-heading text-3xl text-white md:text-4xl">A cockpit-style map frame for route planning</h2>
            <p class="text-text-secondary leading-8">
                Sample markers and route lines can be expanded into Google Maps overlays, making the dashboard useful for waypoint planning, route validation, and navigation aid monitoring.
            </p>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="glass-panel">
                    <div class="text-xs uppercase tracking-[0.3em] text-text-tertiary">Markers</div>
                    <div class="mt-3 text-lg text-white">Animated waypoint pins</div>
                </div>
                <div class="glass-panel">
                    <div class="text-xs uppercase tracking-[0.3em] text-text-tertiary">Routes</div>
                    <div class="mt-3 text-lg text-white">Neon polyline overlays</div>
                </div>
            </div>
        </div>

        <div class="glass-panel overflow-hidden rounded-[2rem] p-4" style="min-height: 380px;">
            <div class="mb-4 flex items-center justify-between text-sm text-text-secondary">
                <span>Google Map embed</span>
                <span>Dark mode enabled</span>
            </div>
            <div class="relative h-[320px] overflow-hidden rounded-[1.5rem] border border-border-light bg-[radial-gradient(circle_at_center,rgba(0,194,255,0.14),transparent_45%),linear-gradient(180deg,#0b1220,#111827)]">
                <div class="absolute inset-0 opacity-40" style="background-image: linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 48px 48px;"></div>
                <div class="absolute left-[20%] top-[30%] h-4 w-4 rounded-full bg-accent-primary shadow-[0_0_20px_rgba(0,194,255,0.9)]"></div>
                <div class="absolute left-[52%] top-[55%] h-4 w-4 rounded-full bg-accent-secondary shadow-[0_0_20px_rgba(34,211,238,0.9)]"></div>
                <div class="absolute left-[78%] top-[40%] h-4 w-4 rounded-full bg-emerald-400 shadow-[0_0_20px_rgba(74,222,128,0.9)]"></div>
                <svg viewBox="0 0 800 400" class="absolute inset-0 h-full w-full">
                    <path d="M150 160 C280 90, 420 260, 610 150" fill="none" stroke="#00C2FF" stroke-width="5" stroke-linecap="round" stroke-dasharray="14 12" class="route-line"></path>
                </svg>
            </div>
        </div>
    </div>
</section>

<section id="stack" class="mx-auto max-w-7xl px-4 pb-20 md:px-8">
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['Laravel', 'fa-laravel'],
            ['MySQL', 'fa-database'],
            ['Google Maps API', 'fa-map'],
            ['Tailwind CSS', 'fa-wind'],
        ] as $tech)
            <div class="glass-panel flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/5 text-2xl text-accent-primary">
                    <i class="fa-solid {{ $tech[1] }}"></i>
                </div>
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-text-tertiary">Stack</p>
                    <h3 class="mt-1 text-lg text-white">{{ $tech[0] }}</h3>
                </div>
            </div>
        @endforeach
    </div>
</section>

<footer class="border-t border-border-light bg-black/20 px-4 py-10 text-sm text-text-secondary md:px-8">
    <div class="mx-auto grid max-w-7xl gap-8 md:grid-cols-3">
        <div>
            <h3 class="font-heading text-lg tracking-[0.25em] text-white">Project Details</h3>
            <p class="mt-3 leading-7">Aviation control and navigation dashboard for academic and prototype ATS route planning workflows.</p>
        </div>
        <div>
            <h3 class="font-heading text-lg tracking-[0.25em] text-white">Team</h3>
            <p class="mt-3 leading-7">Add team member names, roll numbers, and university details here for submission formatting.</p>
        </div>
        <div>
            <h3 class="font-heading text-lg tracking-[0.25em] text-white">Contact</h3>
            <p class="mt-3 leading-7">Email: project@example.com<br>Phone: +91 00000 00000</p>
        </div>
    </div>
</footer>
@endsection