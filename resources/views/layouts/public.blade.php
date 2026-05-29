<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'AeroRoute'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Orbitron:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/app.jsx', 'resources/js/aviation.js'])
    @stack('head')
</head>
<body class="font-body bg-bg-primary text-text-primary antialiased">
    <div class="layout-container min-h-screen">
        <div class="absolute inset-0 bg-gradient-radial"></div>

        <header class="navbar sticky top-0 z-40 border-b border-border-light/70 px-4 py-4 md:px-8">
            <a href="{{ route('home') }}" class="logo-text">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-accent-primary/15 text-accent-primary shadow-[0_0_24px_rgba(0,194,255,0.18)]">
                    <i class="fa-solid fa-plane-up"></i>
                </span>
                <span class="font-heading text-xl tracking-[0.3em] text-text-primary">AeroRoute</span>
            </a>

            <div class="hidden items-center gap-3 md:flex">
                <a href="#features" class="nav-link">Features</a>
                <a href="#preview" class="nav-link">Map Preview</a>
                <a href="#stack" class="nav-link">Tech Stack</a>
                <button type="button" data-theme-toggle class="btn-outline">
                    <i class="fa-solid fa-moon"></i>
                    Theme
                </button>
                @if(Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-outline">Login</a>
                @endif
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Register</a>
                @endif
            </div>
        </header>

        <main class="relative z-10">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>