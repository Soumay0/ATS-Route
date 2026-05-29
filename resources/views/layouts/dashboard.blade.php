<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'AeroRoute Dashboard'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Orbitron:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/app.jsx', 'resources/js/aviation.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @stack('head')
</head>
<body class="font-body bg-bg-primary text-text-primary antialiased">
    <div class="layout-container min-h-screen">
        <!-- Dashboard Content -->

        <div data-sidebar-overlay class="fixed inset-0 z-30 hidden bg-black/70 md:hidden"></div>

        <aside data-sidebar-panel class="fixed inset-y-0 left-0 z-40 w-80 -translate-x-full border-r border-border-light bg-sidebar-bg/95 p-6 backdrop-blur-xl transition-transform duration-300 md:translate-x-0">
            <div class="mb-8 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="logo-text">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-accent-primary/15 text-accent-primary shadow-[0_0_24px_rgba(0,194,255,0.18)]">
                        <i class="fa-solid fa-plane-up"></i>
                    </span>
                    <span class="font-heading text-lg tracking-[0.3em] text-text-primary">AeroRoute</span>
                </a>
                <button type="button" data-sidebar-toggle class="rounded-xl border border-border-light px-3 py-2 text-text-secondary md:hidden">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <nav class="space-y-2">
                <x-sidebar-link :href="route('dashboard')" icon="fa-gauge-high" :active="request()->routeIs('dashboard')">Dashboard</x-sidebar-link>
                <x-sidebar-link :href="route('waypoints.index')" icon="fa-location-dot" :active="request()->routeIs('waypoints.*')">Waypoints</x-sidebar-link>
                <x-sidebar-link :href="route('navigational-aids.index')" icon="fa-tower-cell" :active="request()->routeIs('navigational-aids.*')">Navigational Aids</x-sidebar-link>
                <x-sidebar-link :href="route('ats-routes.index')" icon="fa-route" :active="request()->routeIs('ats-routes.*')">ATS Routes</x-sidebar-link>
                <x-sidebar-link :href="route('interactive-map')" icon="fa-map-location-dot" :active="request()->routeIs('interactive-map')">Interactive Map</x-sidebar-link>
                <x-sidebar-link :href="route('reports.index')" icon="fa-chart-line" :active="request()->routeIs('reports.*')">Reports</x-sidebar-link>
                <x-sidebar-link :href="route('settings.index')" icon="fa-gear" :active="request()->routeIs('settings.*')">Settings</x-sidebar-link>
            </nav>

            <div class="mt-8 rounded-3xl border border-border-light bg-white/5 p-5">
                <div class="text-xs uppercase tracking-[0.35em] text-text-tertiary">System Status</div>
                <div class="mt-3 flex items-center gap-3 text-sm text-text-secondary">
                    <span class="h-3 w-3 rounded-full bg-emerald-400 shadow-[0_0_16px_rgba(16,185,129,0.8)]"></span>
                    Radar feed online
                </div>
                <div class="mt-2 text-sm text-text-secondary">Google Earth route overlays ready for airspace planning.</div>
            </div>
        </aside>

        <div class="md:pl-80">
            <header class="sticky top-0 z-20 border-b border-border-light bg-navbar-bg/95 backdrop-blur-xl">
                <div class="flex items-center justify-between gap-4 px-4 py-4 md:px-8">
                    <div class="flex items-center gap-3">
                        <button type="button" data-sidebar-toggle class="rounded-xl border border-border-light bg-white/5 px-3 py-2 text-text-secondary md:hidden">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <div>
                            <h1 class="font-heading text-lg tracking-[0.2em] text-text-primary">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-sm text-text-tertiary">Aviation control and navigation command center</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" data-theme-toggle class="rounded-xl border border-border-light bg-white/5 px-3 py-2 text-text-secondary hover:text-text-primary">
                            <i class="fa-solid fa-circle-half-stroke"></i>
                        </button>

                        <details class="relative">
                            <summary class="list-none cursor-pointer rounded-xl border border-border-light bg-white/5 px-3 py-2 text-sm text-text-secondary">
                                <i class="fa-regular fa-bell mr-2 text-accent-primary"></i> Alerts
                            </summary>
                            <div class="absolute right-0 mt-3 w-80 rounded-3xl border border-border-light bg-bg-card-solid p-4 shadow-2xl">
                                <p class="text-xs uppercase tracking-[0.3em] text-text-tertiary">Notifications</p>
                                <div class="mt-4 space-y-3 text-sm text-text-secondary">
                                    <div class="rounded-2xl border border-border-light bg-white/5 p-3">New ATS route submitted for review.</div>
                                    <div class="rounded-2xl border border-border-light bg-white/5 p-3">Waypoint cluster synchronized with Google Maps.</div>
                                </div>
                            </div>
                        </details>

                        <details class="relative">
                            <summary class="list-none cursor-pointer rounded-xl border border-border-light bg-white/5 px-3 py-2 text-sm text-text-secondary">
                                <i class="fa-regular fa-user mr-2 text-accent-secondary"></i> {{ auth()->user()->name ?? 'Controller' }}
                            </summary>
                            <div class="absolute right-0 mt-3 w-64 rounded-3xl border border-border-light bg-bg-card-solid p-4 shadow-2xl">
                                <a href="{{ route('profile.edit') }}" class="block rounded-2xl px-4 py-3 text-sm text-text-secondary hover:bg-hover-bg hover:text-text-primary">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="mt-2 block w-full rounded-2xl px-4 py-3 text-left text-sm text-text-secondary hover:bg-hover-bg hover:text-text-primary">Logout</button>
                                </form>
                            </div>
                        </details>
                    </div>
                </div>
            </header>

            <main class="relative z-10 px-4 py-6 md:px-8 md:py-8">
                @if(session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>