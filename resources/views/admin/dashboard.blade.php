<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Admin Dashboard - EventTicket Management System">

    <title>Admin Dashboard - {{ config('app.name', 'EventTicket') }}</title>

    {{-- =======================================================================
         1. ASSETS & LIBRARIES
    ======================================================================= --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- FIX: rel="stylesheet" --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Animate.css + AOS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- Icons, Alpine, Chart.js, VanillaTilt --}}
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>

    {{-- =======================================================================
         2. CUSTOM STYLES (Bright + Dark Panels, Interaktif)
    ======================================================================= --}}
    <style>
        /* Base typography & layout */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, .font-display {
            font-family: 'Outfit', sans-serif;
        }
        .font-mono-num {
            font-family: 'Space Grotesk', monospace;
        }

        /* --- 2.1 Aurora + Noise Background (Light, tidak polos) --- */
        .aurora-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: radial-gradient(circle at top left, #e0f2fe 0, transparent 55%),
                        radial-gradient(circle at bottom right, #dcfce7 0, transparent 60%),
                        #f8fafc;
            overflow: hidden;
        }
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.6;
            mix-blend-mode: multiply;
            animation: float 25s infinite ease-in-out;
        }
        .orb-1 {
            top: -15%;
            left: -10%;
            width: 45vw;
            height: 45vw;
            background: #bfdbfe;
            animation-delay: 0s;
        }
        .orb-2 {
            bottom: -20%;
            right: -18%;
            width: 55vw;
            height: 55vw;
            background: #bbf7d0;
            animation-delay: -5s;
        }
        .orb-3 {
            top: 40%;
            left: 35%;
            width: 35vw;
            height: 35vw;
            background: #fee2e2;
            opacity: 0.35;
            animation-delay: -10s;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%      { transform: translate(30px, -35px) scale(1.08); }
        }
        .noise-texture {
            position: absolute;
            inset: 0;
            opacity: 0.3;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.08'/%3E%3C/svg%3E");
        }

        /* --- 2.2 Solid Dark Sidebar & Panels --- */
        .solid-dark-sidebar {
            background: #020617;
            border-right: 1px solid rgba(15, 23, 42, 0.9);
            color: #E2E8F0;
            backdrop-filter: blur(20px);
            background-image:
                radial-gradient(circle at top, rgba(129, 140, 248, 0.2), transparent 55%),
                radial-gradient(circle at bottom, rgba(56, 189, 248, 0.18), transparent 55%);
        }
        .solid-dark-panel {
            background: rgba(15, 23, 42, 0.96);
            border: 1px solid rgba(15, 23, 42, 0.9);
            box-shadow:
                0 18px 60px -25px rgba(15, 23, 42, 0.9),
                0 0 0 1px rgba(15, 23, 42, 0.9);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(24px);
        }
        .solid-dark-panel:hover {
            transform: translateY(-4px);
            box-shadow:
                0 24px 80px -26px rgba(15, 23, 42, 0.95),
                0 0 0 1px rgba(129, 140, 248, 0.4);
        }

        /* --- 2.3 Stat Cards (white, floating) --- */
        .stat-card {
            position: relative;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.75rem;
            padding: 1.4rem 1.5rem;
            transition: all 0.35s ease;
            box-shadow:
                0 8px 20px -10px rgba(15, 23, 42, 0.25),
                0 0 0 1px rgba(226, 232, 240, 0.9);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(129, 140, 248, 0.12), transparent 55%);
            opacity: 0;
            transition: opacity 0.35s ease;
        }
        .stat-card:hover {
            transform: translateY(-8px) scale(1.01);
            border-color: #6366f1;
            box-shadow:
                0 18px 45px -18px rgba(56, 189, 248, 0.45),
                0 0 0 1px rgba(129, 140, 248, 0.5);
        }
        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card-highlight {
            position: relative;
            overflow: hidden;
            background: radial-gradient(circle at top left, #0f172a, #020617);
            color: white;
            border-radius: 1.9rem;
            border: 1px solid rgba(30, 64, 175, 0.8);
            box-shadow:
                0 18px 60px -18px rgba(15, 23, 42, 0.9),
                0 0 0 1px rgba(37, 99, 235, 0.7);
        }
        .stat-card-highlight::before {
            content: '';
            position: absolute;
            inset: -40%;
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.3), transparent 60%),
                radial-gradient(circle at bottom right, rgba(45, 212, 191, 0.22), transparent 65%);
            opacity: 0.7;
            mix-blend-mode: screen;
            pointer-events: none;
        }
        .stat-card-highlight::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.07'%3E%3Ccircle cx='8' cy='8' r='2'/%3E%3Ccircle cx='80' cy='80' r='2'/%3E%3Ccircle cx='152' cy='152' r='2'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.4;
            pointer-events: none;
        }

        /* --- 2.4 Circular Progress Badge --- */
        .circular-progress {
            position: relative;
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3px;
            background:
                conic-gradient(
                    var(--p-color, #4f46e5) calc(var(--p-value, 0) * 1%),
                    rgba(148, 163, 184, 0.16) 0
                );
            box-shadow:
                0 0 0 1px rgba(148, 163, 184, 0.35),
                0 10px 25px -12px rgba(15, 23, 42, 0.4);
        }
        .circular-progress::before {
            content: '';
            position: absolute;
            inset: 4px;
            border-radius: inherit;
            background: #ffffff;
        }
        .circular-progress.dark-center::before {
            background: #020617;
        }
        .progress-content {
            position: relative;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.03em;
        }

        /* --- 2.5 Chart Container --- */
        .chart-wrapper {
            border-radius: 2rem;
            padding: 2.2rem 2rem;
            background: radial-gradient(circle at top left, rgba(129, 140, 248, 0.16), transparent 55%),
                        #020617;
            box-shadow:
                0 24px 80px -25px rgba(15, 23, 42, 0.95),
                0 0 0 1px rgba(15, 23, 42, 0.9);
        }

        /* --- 2.6 Sidebar Nav Links --- */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1rem;
            margin-bottom: 0.1rem;
            border-radius: 0.9rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: #94a3b8;
            text-decoration: none;
            transition:
                color 0.2s ease,
                background-color 0.2s ease,
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }
        .nav-link i {
            font-size: 1.25rem;
        }
        .nav-link:hover {
            background: rgba(129, 140, 248, 0.22);
            color: #e5e7eb;
            transform: translateX(3px);
            box-shadow: 0 12px 25px -15px rgba(129, 140, 248, 0.7);
            border: none;
        }
        .nav-link.active {
            background: linear-gradient(135deg, rgba(129, 140, 248, 0.45), rgba(45, 212, 191, 0.35));
            color: #f9fafb;
            font-weight: 700;
            box-shadow:
                0 12px 30px -18px rgba(79, 70, 229, 0.9),
                0 0 0 1px rgba(191, 219, 254, 0.9);
        }

        /* --- 2.7 Scrollbar (main content) --- */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.7);
            border-radius: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 116, 139, 0.9);
        }
    </style>
</head>
<body class="antialiased text-slate-800 flex min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- LOGIC PHP: Menghitung Data Real-Time (SSR) --}}
    @php
        $totalUsers       = \App\Models\User::count();
        $totalOrganizers  = \App\Models\User::where('role', 'organizer')->count();
        $totalEvents      = \App\Models\Event::count();
        $totalBookings    = \App\Models\Booking::count();
        $organizerRatio   = $totalUsers > 0 ? round(($totalOrganizers / $totalUsers) * 100) : 0;

        // organizer pending (untuk panel approval)
        $pendingOrganizers = \App\Models\User::where('role', 'organizer')
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
    @endphp

    {{-- BACKGROUND --}}
    <div class="aurora-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="noise-texture"></div>
    </div>

    {{-- =======================================================================
         3. SIDEBAR NAVIGATION (DESKTOP)
    ======================================================================= --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-72 solid-dark-sidebar hidden lg:flex flex-col transition-transform duration-300">

        {{-- Logo --}}
        <div class="h-24 flex items-center px-7 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-11 h-11 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-900 font-extrabold text-xl shadow-[0_15px_40px_-20px_rgba(15,23,42,0.9)]">
                        AD
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-400 border-2 border-slate-900"></div>
                </div>
                <div class="flex flex-col">
                    <span class="font-display font-bold text-xl tracking-tight text-white">
                        Event<span class="text-indigo-400">Ticket</span>
                    </span>
                    <span class="text-[11px] text-slate-400 uppercase tracking-[0.2em] font-semibold">
                        Admin Control Center
                    </span>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <div class="flex-1 px-4 py-7 space-y-6 overflow-y-auto custom-scrollbar">
            <div>
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.25em] mb-2">
                    Main Control
                </p>
                <nav class="space-y-0.5">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                        <i class="ph-duotone ph-squares-four"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="ph-duotone ph-users"></i>
                        <span>User Management</span>
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="nav-link">
                        <i class="ph-duotone ph-calendar-plus"></i>
                        <span>Kelola Event</span>
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="nav-link">
                        <i class="ph-duotone ph-ticket"></i>
                        <span>Kelola Booking</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.25em] mb-2">
                    System
                </p>
                <nav class="space-y-0.5">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link">
                        <i class="ph-duotone ph-tag"></i>
                        <span>Kategori</span>
                    </a>
                    <a href="{{ route('admin.notifications.index') }}" class="nav-link">
                        <i class="ph-duotone ph-bell"></i>
                        <span>Notifikasi</span>
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="nav-link">
                        <i class="ph-duotone ph-chart-line-up"></i>
                        <span>Analytics</span>
                    </a>
                </nav>
            </div>
        </div>

        {{-- Profile & Logout --}}
        <div class="p-4 border-t border-white/10">
            <div
                class="bg-white/10 border border-white/10 p-3 rounded-2xl flex items-center gap-3 cursor-pointer hover:bg-white/15 transition-all relative"
                x-data="{ open: false }"
            >
                <div class="h-10 w-10 rounded-full bg-slate-700 p-[2px] overflow-hidden">
                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=020617&color=fff"
                        alt="{{ Auth::user()->name }}"
                        class="rounded-full w-full h-full object-cover"
                    >
                </div>
                <div class="flex-1 min-w-0" @click="open = !open">
                    <p class="text-sm font-semibold text-white truncate">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-[11px] text-slate-400 truncate">
                        System Administrator
                    </p>
                </div>
                <button
                    type="button"
                    class="text-slate-400 hover:text-slate-100 transition-colors"
                    @click="open = !open"
                >
                    <i class="ph-bold ph-caret-up text-xs"></i>
                </button>

                {{-- Dropdown --}}
                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute bottom-full left-0 w-full mb-2 bg-slate-950/95 rounded-xl shadow-xl border border-white/10 p-1 z-50"
                    style="display: none;"
                >
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full flex items-center gap-2 px-3 py-2 text-sm text-rose-400 hover:bg-rose-500/10 rounded-lg font-semibold transition-colors"
                        >
                            <i class="ph-bold ph-sign-out"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- =======================================================================
         3B. MOBILE SIDEBAR (SLIDE-IN)
    ======================================================================= --}}
    {{-- Overlay --}}
    <div
        class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm lg:hidden"
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        style="display: none;"
    ></div>

    {{-- Mobile aside --}}
    <aside
        class="fixed inset-y-0 left-0 z-40 w-72 solid-dark-sidebar flex flex-col lg:hidden transform transition-transform duration-300"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="h-20 flex items-center px-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center text-slate-900 font-bold shadow-lg">
                    AD
                </div>
                <div class="flex flex-col">
                    <span class="font-display font-semibold text-base text-white">
                        EventTicket
                    </span>
                    <span class="text-[10px] text-slate-400 uppercase tracking-[0.18em]">
                        Admin
                    </span>
                </div>
            </div>
        </div>

        <div class="flex-1 px-3 py-4 space-y-4 overflow-y-auto custom-scrollbar">
            <nav class="space-y-0.5">
                <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                    <i class="ph-duotone ph-squares-four"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link">
                    <i class="ph-duotone ph-users"></i>
                    <span>User Management</span>
                </a>
                <a href="{{ route('admin.events.index') }}" class="nav-link">
                    <i class="ph-duotone ph-calendar-plus"></i>
                    <span>Kelola Event</span>
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="nav-link">
                    <i class="ph-duotone ph-ticket"></i>
                    <span>Kelola Booking</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link">
                    <i class="ph-duotone ph-tag"></i>
                    <span>Kategori</span>
                </a>
                <a href="{{ route('admin.notifications.index') }}" class="nav-link">
                    <i class="ph-duotone ph-bell"></i>
                    <span>Notifikasi</span>
                </a>
                <a href="{{ route('admin.analytics.index') }}" class="nav-link">
                    <i class="ph-duotone ph-chart-line-up"></i>
                    <span>Analytics</span>
                </a>
            </nav>
        </div>

        <div class="p-3 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-rose-500/90 px-3 py-2 text-xs font-semibold text-white shadow-md hover:bg-rose-500 transition-colors"
                >
                    <i class="ph-bold ph-sign-out"></i>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- =======================================================================
         4. MAIN CONTENT AREA
    ======================================================================= --}}
    <div class="lg:ml-72 flex-1 flex flex-col min-h-screen relative z-10 transition-all">

        {{-- Top header (mobile) --}}
        <header class="h-20 flex items-center justify-between px-5 lg:px-10 lg:hidden bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors"
                >
                    <i class="ph-bold ph-list text-2xl"></i>
                </button>
                <div class="flex flex-col">
                    <span class="text-xs uppercase tracking-[0.2em] text-slate-400 font-semibold">
                        Admin
                    </span>
                    <span class="font-semibold text-slate-900 text-sm">
                        System Overview
                    </span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="p-2 rounded-xl text-rose-500 hover:bg-rose-50 transition-colors"
                >
                    <i class="ph-bold ph-sign-out text-2xl"></i>
                </button>
            </form>
        </header>

        {{-- Main content --}}
        <main class="p-5 sm:p-6 lg:p-10 space-y-10 custom-scrollbar">

            {{-- A. Header Section + Quick Info --}}
            <section
                class="flex flex-col lg:flex-row justify-between gap-6 lg:gap-8 items-start lg:items-end"
                data-aos="fade-down"
            >
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 border border-slate-200 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[11px] font-semibold tracking-[0.18em] uppercase text-slate-500">
                            Global System Overview
                        </span>
                    </div>

                    <div class="space-y-1">
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 leading-tight">
                            Dashboard Admin
                        </h1>
                        <p class="text-sm sm:text-base text-slate-500 max-w-2xl">
                            Pantau kesehatan sistem, pengguna, event, dan transaksi tiket dalam satu tampilan
                            yang modern dan interaktif.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                    <div class="flex items-center gap-3 bg-white/90 border border-slate-200 rounded-2xl px-4 py-3 shadow-sm">
                        <div class="h-9 w-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-700">
                            <i class="ph-duotone ph-lightning text-lg"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[11px] uppercase tracking-[0.18em] text-slate-400 font-semibold">
                                System Status
                            </span>
                            <span class="text-xs font-semibold text-slate-700">
                                Online • Realtime Analytics
                            </span>
                        </div>
                    </div>

                    <a
                        href="{{ route('admin.analytics.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-900 text-slate-50 px-4 py-2.5 text-xs font-semibold shadow-lg shadow-slate-900/40 hover:bg-slate-800 transition"
                    >
                        <i class="ph-duotone ph-pulse text-lg text-emerald-300"></i>
                        <span>Lihat detail analytics</span>
                    </a>
                </div>
            </section>

            {{-- B. Stats Cards Row --}}
            <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6" data-aos="fade-up">

                {{-- Total Users --}}
                <article class="stat-card group" data-tilt data-tilt-max="7" data-tilt-speed="400">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="circular-progress"
                                style="--p-value: 100; --p-color: #2563eb;"
                            >
                                <div class="progress-content text-blue-600">
                                    100%
                                </div>
                            </div>
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-semibold tracking-[0.18em] uppercase text-slate-400">
                                    Total users
                                </p>
                                <p class="text-xs text-slate-500">
                                    Semua user terdaftar di sistem
                                </p>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-3xl font-mono-num font-bold text-slate-900 mb-1" id="ad-analytics-total-users">
                        {{ $totalUsers }}
                    </h3>
                    <p class="text-xs font-medium text-slate-500">
                        Pengguna dari semua role (admin, organizer, registered user)
                    </p>
                </article>

                {{-- Total Organizers + Ratio --}}
                <article class="stat-card group" data-tilt data-tilt-max="7" data-tilt-speed="400">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="circular-progress"
                                style="--p-value: {{ $organizerRatio }}; --p-color: #9333ea;"
                            >
                                <div class="progress-content text-purple-600">
                                    {{ $organizerRatio }}%
                                </div>
                            </div>
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-semibold tracking-[0.18em] uppercase text-slate-400">
                                    Organizers
                                </p>
                                <p class="text-xs text-slate-500">
                                    Persentase organizer dibanding total user
                                </p>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-3xl font-mono-num font-bold text-slate-900 mb-1" id="ad-analytics-total-organizers">
                        {{ $totalOrganizers }}
                    </h3>
                    <p class="text-xs font-medium text-slate-500">
                        Mitra penyelenggara event terverifikasi
                    </p>
                </article>

                {{-- Total Events --}}
                <article class="stat-card group" data-tilt data-tilt-max="7" data-tilt-speed="400">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-2xl bg-indigo-50 text-indigo-600 shadow-sm">
                                <i class="ph-duotone ph-calendar-star text-2xl"></i>
                            </div>
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-semibold tracking-[0.18em] uppercase text-slate-400">
                                    Event aktif
                                </p>
                                <p class="text-xs text-slate-500">
                                    Semua event yang terdaftar di platform
                                </p>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-3xl font-mono-num font-bold text-slate-900 mb-1" id="ad-analytics-total-events">
                        {{ $totalEvents }}
                    </h3>
                    <p class="text-xs font-medium text-slate-500">
                        Termasuk event publik maupun terbatas
                    </p>
                </article>

                {{-- Total Bookings (Highlight Card) --}}
                <article
                    class="stat-card-highlight group relative overflow-hidden p-6"
                    data-tilt
                    data-tilt-max="8"
                    data-tilt-speed="400"
                >
                    <div class="relative z-10 flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-2xl bg-white/10 text-white shadow-inner">
                                <i class="ph-duotone ph-ticket text-2xl"></i>
                            </div>
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-semibold tracking-[0.18em] uppercase text-slate-300">
                                    Total booking
                                </p>
                                <p class="text-xs text-slate-300/90">
                                    Jumlah seluruh transaksi tiket
                                </p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-xl bg-emerald-500/20 text-[10px] font-semibold text-emerald-200 border border-emerald-400/40">
                            Live
                        </span>
                    </div>

                    <div class="relative z-10">
                        <h3 class="text-3xl font-mono-num font-bold text-white mb-1" id="ad-analytics-total-bookings">
                            {{ $totalBookings }}
                        </h3>
                        <p class="text-xs text-slate-300 font-medium">
                            Termasuk pending, approved, dan cancelled
                        </p>
                    </div>
                </article>
            </section>

            {{-- C. Chart + Quick Actions --}}
            <section class="grid grid-cols-1 xl:grid-cols-[minmax(0,2.2fr),minmax(0,1.1fr)] gap-8 items-start">

                {{-- Chart Panel --}}
                <div
                    class="solid-dark-panel rounded-[2.4rem] p-6 sm:p-7 md:p-8 space-y-6"
                    data-aos="fade-up"
                    data-aos-delay="50"
                >
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-[11px] font-semibold tracking-[0.22em] uppercase text-slate-400 mb-1">
                                Analytics
                            </p>
                            <h3 class="font-bold text-xl sm:text-2xl text-white">
                                Booking & Revenue Overview
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-400 mt-1 max-w-md">
                                Visualisasi booking tiket dan total revenue per bulan untuk seluruh event
                                di platform ini.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 bg-white/5 rounded-full border border-white/10 px-1 py-1">
                            <button
                                type="button"
                                class="px-3 py-1.5 rounded-full bg-slate-900/70 text-[11px] font-semibold text-slate-100 shadow-sm"
                            >
                                Bulanan
                            </button>
                            <button
                                type="button"
                                class="px-3 py-1.5 rounded-full text-[11px] font-semibold text-slate-300/80 hover:text-slate-50 transition-colors"
                            >
                                Tahunan
                            </button>
                        </div>
                    </div>

                    <div class="chart-wrapper mt-2">
                        <div class="h-80 w-full relative">
                            <canvas id="admin-analytics-chart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions + System Snapshot --}}
                <div class="space-y-6" data-aos="fade-up" data-aos-delay="100">

                    {{-- Quick Actions --}}
                    <div class="bg-white/90 backdrop-blur-xl border border-slate-200 rounded-3xl p-5 shadow-xl">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-[11px] font-semibold tracking-[0.22em] uppercase text-slate-400 mb-1">
                                    Quick Actions
                                </p>
                                <h4 class="font-semibold text-slate-900 text-base">
                                    Aksi cepat admin
                                </h4>
                            </div>
                            <div class="h-8 w-8 rounded-full bg-slate-900 text-slate-50 flex items-center justify-center text-xs">
                                <i class="ph-duotone ph-magic-wand"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a
                                href="{{ route('admin.events.index') }}"
                                class="group flex items-center gap-3 px-3.5 py-3 rounded-2xl bg-slate-50 hover:bg-sky-50 border border-slate-200 hover:border-sky-200 transition-colors"
                            >
                                <div class="h-9 w-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center">
                                    <i class="ph-duotone ph-calendar-plus text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-semibold text-slate-800">
                                        Kelola Event
                                    </span>
                                    <span class="text-[11px] text-slate-500">
                                        Tambah, edit, dan hapus event
                                    </span>
                                </div>
                            </a>

                            <a
                                href="{{ route('admin.users.index') }}"
                                class="group flex items-center gap-3 px-3.5 py-3 rounded-2xl bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-200 transition-colors"
                            >
                                <div class="h-9 w-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                    <i class="ph-duotone ph-users text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-semibold text-slate-800">
                                        Kelola User
                                    </span>
                                    <span class="text-[11px] text-slate-500">
                                        Approve organizer & manajemen user
                                    </span>
                                </div>
                            </a>

                            <a
                                href="{{ route('admin.bookings.index') }}"
                                class="group flex items-center gap-3 px-3.5 py-3 rounded-2xl bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 transition-colors"
                            >
                                <div class="h-9 w-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center">
                                    <i class="ph-duotone ph-ticket text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-semibold text-slate-800">
                                        Kelola Booking
                                    </span>
                                    <span class="text-[11px] text-slate-500">
                                        Approve / cancel booking tiket
                                    </span>
                                </div>
                            </a>

                            <a
                                href="{{ route('admin.notifications.index') }}"
                                class="group flex items-center gap-3 px-3.5 py-3 rounded-2xl bg-slate-50 hover:bg-amber-50 border border-slate-200 hover:border-amber-200 transition-colors"
                            >
                                <div class="h-9 w-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                                    <i class="ph-duotone ph-bell text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-semibold text-slate-800">
                                        Notifikasi Sistem
                                    </span>
                                    <span class="text-[11px] text-slate-500">
                                        Lihat event baru dan update penting
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Pending Organizer Approvals --}}
                    <div class="solid-dark-panel rounded-3xl p-5 border-t-4 border-emerald-500/80" data-aos="fade-up" data-aos-delay="150">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-[11px] font-semibold tracking-[0.22em] uppercase text-emerald-300 mb-1">
                                    Pending Approvals
                                </p>
                                <h4 class="font-semibold text-white text-base">
                                    Pengajuan organizer baru
                                </h4>
                            </div>
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/15 border border-emerald-400/40 px-2.5 py-1 text-[11px] font-semibold text-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                {{ $pendingOrganizers->count() }} waiting
                            </span>
                        </div>

                        @if ($pendingOrganizers->isEmpty())
                            <p class="text-xs text-slate-400">
                                Belum ada organizer baru yang mengajukan approval saat ini.
                            </p>
                        @else
                            <div class="space-y-3">
                                @foreach ($pendingOrganizers as $pending)
                                    <div class="flex items-center justify-between bg-white/5 px-3.5 py-2.5 rounded-2xl border border-white/10">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="h-8 w-8 rounded-full bg-slate-900 flex items-center justify-center text-[11px] text-slate-100 font-semibold">
                                                {{ strtoupper(substr($pending->name, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-slate-50 truncate">
                                                    {{ $pending->name }}
                                                </p>
                                                <p class="text-[11px] text-slate-400 truncate">
                                                    {{ $pending->email }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('admin.users.approve', $pending->id) }}">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="px-3 py-1.5 rounded-xl text-[11px] font-semibold bg-emerald-500 text-white hover:bg-emerald-400 transition-colors"
                                                >
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.reject', $pending->id) }}">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="px-3 py-1.5 rounded-xl text-[11px] font-semibold bg-rose-500/90 text-white hover:bg-rose-500 transition-colors"
                                                >
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </section>

        </main>
    </div>

    {{-- =======================================================================
         5. SCRIPT: AOS, VanillaTilt, Chart.js
    ======================================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // AOS
            AOS.init({
                duration: 800,
                once: true,
                offset: 60,
            });

            // Vanilla Tilt (untuk card interaktif)
            VanillaTilt.init(document.querySelectorAll('[data-tilt]'), {
                max: 10,
                speed: 400,
                glare: true,
                'max-glare': 0.18,
                scale: 1.02
            });

            // Fetch data untuk Chart & update angka secara dinamis
            fetch("{{ route('admin.analytics.json') }}")
                .then(response => response.json())
                .then(data => {
                    // Update angka stat jika tersedia dari endpoint JSON
                    if (typeof data.total_users !== 'undefined') {
                        const el = document.getElementById('ad-analytics-total-users');
                        if (el) el.innerText = data.total_users;
                    }
                    if (typeof data.total_organizers !== 'undefined') {
                        const el = document.getElementById('ad-analytics-total-organizers');
                        if (el) el.innerText = data.total_organizers;

                        // Update ratio di progress ring jika memungkinkan
                        if (typeof data.total_users !== 'undefined' && data.total_users > 0) {
                            const newRatio = Math.round((data.total_organizers / data.total_users) * 100);
                            const ring = document.querySelector('.circular-progress[style*="--p-color: #9333ea"]');
                            if (ring) {
                                ring.style.setProperty('--p-value', newRatio);
                                const label = ring.querySelector('.progress-content');
                                if (label) label.innerText = newRatio + '%';
                            }
                        }
                    }
                    if (typeof data.total_events !== 'undefined') {
                        const el = document.getElementById('ad-analytics-total-events');
                        if (el) el.innerText = data.total_events;
                    }
                    if (typeof data.total_bookings !== 'undefined') {
                        const el = document.getElementById('ad-analytics-total-bookings');
                        if (el) el.innerText = data.total_bookings;
                    }

                    // Setup Chart.js jika monthly data ada
                    if (data.monthly) {
                        const ctx = document.getElementById('admin-analytics-chart').getContext('2d');

                        const gradientBookings = ctx.createLinearGradient(0, 0, 0, 400);
                        gradientBookings.addColorStop(0, 'rgba(129, 140, 248, 0.55)');
                        gradientBookings.addColorStop(1, 'rgba(129, 140, 248, 0.02)');

                        const gradientRevenue = ctx.createLinearGradient(0, 0, 0, 400);
                        gradientRevenue.addColorStop(0, 'rgba(52, 211, 153, 0.6)');
                        gradientRevenue.addColorStop(1, 'rgba(52, 211, 153, 0.02)');

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.monthly.labels,
                                datasets: [
                                    {
                                        label: 'Booking',
                                        data: data.monthly.bookings,
                                        borderColor: '#818cf8',
                                        backgroundColor: gradientBookings,
                                        borderWidth: 3,
                                        tension: 0.35,
                                        fill: true,
                                        pointRadius: 3,
                                        pointBackgroundColor: '#fff',
                                        pointBorderColor: '#818cf8',
                                        pointHoverRadius: 5,
                                        yAxisID: 'y'
                                    },
                                    {
                                        label: 'Revenue (IDR)',
                                        data: data.monthly.revenue,
                                        borderColor: '#34d399',
                                        backgroundColor: gradientRevenue,
                                        borderWidth: 3,
                                        tension: 0.35,
                                        fill: true,
                                        pointRadius: 3,
                                        pointBackgroundColor: '#fff',
                                        pointBorderColor: '#34d399',
                                        pointHoverRadius: 5,
                                        yAxisID: 'y1'
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'index',
                                    intersect: false
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: '#e5e7eb',
                                            font: { size: 11 }
                                        },
                                        display: true,
                                        position: 'top',
                                        align: 'start'
                                    },
                                    tooltip: {
                                        backgroundColor: '#020617',
                                        borderColor: 'rgba(148, 163, 184, 0.4)',
                                        borderWidth: 1,
                                        titleColor: '#f9fafb',
                                        bodyColor: '#e5e7eb',
                                        padding: 10,
                                        cornerRadius: 10,
                                        displayColors: true,
                                        callbacks: {
                                            label: function (ctx) {
                                                if (ctx.dataset.label.includes('Revenue')) {
                                                    return ctx.dataset.label + ': Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                                                }
                                                return ctx.dataset.label + ': ' + ctx.parsed.y;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            color: 'rgba(148, 163, 184, 0.18)',
                                            borderDash: [4, 4]
                                        },
                                        ticks: {
                                            color: '#94a3b8',
                                            font: { size: 10 }
                                        }
                                    },
                                    y: {
                                        type: 'linear',
                                        display: true,
                                        position: 'left',
                                        grid: {
                                            color: 'rgba(148, 163, 184, 0.18)',
                                            borderDash: [4, 4]
                                        },
                                        ticks: {
                                            color: '#94a3b8',
                                            font: { size: 10 }
                                        }
                                    },
                                    y1: {
                                        type: 'linear',
                                        display: true,
                                        position: 'right',
                                        grid: {
                                            drawOnChartArea: false
                                        },
                                        ticks: {
                                            color: '#94a3b8',
                                            font: { size: 10 },
                                            callback: function (value) {
                                                return 'Rp ' + value.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                })
                .catch(() => {
                    console.log('Data analytics tidak tersedia atau error. Abaikan jika belum ada data.');
                });
        });
    </script>
</body>
</html>
