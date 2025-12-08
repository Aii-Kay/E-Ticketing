<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Dashboard Premium - EventTicket">

    <title>Dashboard - {{ config('app.name', 'EventTicket') }}</title>

    {{-- =======================================================================
         1. FONTS & ASSETS
    ======================================================================= --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;700;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- =======================================================================
         2. MASTERPIECE STYLES
    ======================================================================= --}}
    <style>
        :root {
            --glass-border: rgba(255, 255, 255, 0.6);
            --glass-bg: rgba(255, 255, 255, 0.65);
            --neon-accent: #6366f1;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* --- Aurora Alive Background --- */
        .aurora-bg { position: fixed; inset: 0; z-index: -1; background: #ffffff; overflow: hidden; }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.5;
            animation: float 25s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
            mix-blend-mode: multiply;
        }
        .orb-1 { top: -10%; left: -10%; width: 60vw; height: 60vw; background: #a5b4fc; }
        .orb-2 { bottom: -10%; right: -10%; width: 50vw; height: 50vw; background: #93c5fd; animation-delay: -5s; }
        .orb-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: #f9a8d4; opacity: 0.3; animation-delay: -10s; }

        .noise-texture {
            position: absolute; inset: 0; opacity: 0.05;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='1'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(30px, -30px) rotate(10deg); }
        }

        /* --- Premium Components --- */
        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(24px) saturate(140%);
            -webkit-backdrop-filter: blur(24px) saturate(140%);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px -8px rgba(31, 38, 135, 0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Dark Glass for Sidebar contrast */
        .glass-dark {
            background: rgba(15, 23, 42, 0.85); /* Slate 900 */
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.3);
        }

        /* Holographic Card Effect */
        .holo-card {
            position: relative; overflow: hidden;
            background: rgba(255,255,255,0.8);
            border-radius: 2rem;
            border: 1px solid rgba(255,255,255,0.8);
            transition: all 0.4s ease;
        }
        .holo-card::before {
            content: ""; position: absolute; top: 0; left: -100%; width: 150%; height: 100%;
            background: linear-gradient(115deg, transparent 40%, rgba(255,255,255,0.8) 50%, transparent 60%);
            transform: skewX(-25deg); transition: 0.7s; pointer-events: none; z-index: 10;
        }
        .holo-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.15);
            border-color: #818cf8;
        }
        .holo-card:hover::before { left: 100%; }

        /* Radiant Button */
        .btn-radiant {
            background-image: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%);
            color: white; position: relative; overflow: hidden;
            transition: all 0.4s ease;
        }
        .btn-radiant:hover {
            box-shadow: 0 10px 25px -5px rgba(67, 56, 202, 0.4);
            transform: translateY(-2px) scale(1.02);
        }

        /* Heart Button Alive */
        .btn-heart {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .btn-heart:hover {
            background-color: #fff1f2; border-color: #fda4af; color: #e11d48;
            transform: scale(1.1);
        }
        .btn-heart:active { transform: scale(0.9); }
        .btn-heart.active i {
            color: #e11d48; font-weight: bold;
            animation: heartbeat 1.5s infinite;
        }
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            15% { transform: scale(1.2); }
            30% { transform: scale(1); }
            45% { transform: scale(1.2); }
            60% { transform: scale(1); }
        }

        /* Searchable Dropdown Styling */
        .custom-select-trigger { cursor: pointer; transition: 0.2s ease; }
        .custom-dropdown-menu {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
        }
        .custom-dropdown-item { transition: all 0.15s ease; position: relative; }
        .custom-dropdown-item:hover { background-color: #f8fafc; color: #4338ca; padding-left: 1.25rem; }
        .custom-dropdown-item.selected { background-color: #eef2ff; color: #4338ca; font-weight: 700; }

        /* Chart & Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="antialiased text-slate-800" x-data="{ searchFocus: false }">

    {{-- ALIVE BACKGROUND --}}
    <div class="aurora-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="noise-texture"></div>
    </div>

    {{-- =======================================================================
         3. NAVBAR (Minimalist Glass)
    ======================================================================= --}}
    <nav class="fixed top-0 w-full z-50 glass-panel border-b-0 rounded-b-3xl mx-auto max-w-[98%] mt-2 left-0 right-0 transition-all duration-300 shadow-sm" data-aos="fade-down">
        <div class="px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                {{-- Logo --}}
                <div class="flex items-center gap-12">
                    <a href="{{ route('home') }}" class="group flex items-center gap-3">
                        <div class="relative w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white shadow-lg group-hover:rotate-12 transition-transform duration-500">
                            <i class="ph-bold ph-ticket text-xl"></i>
                        </div>
                        <div class="hidden md:flex flex-col">
                            <span class="font-display font-extrabold text-xl text-slate-900 leading-none">Event<span class="text-indigo-600">Ticket</span></span>
                            <span class="text-[10px] font-bold tracking-[0.2em] text-slate-400 uppercase mt-0.5">Dashboard</span>
                        </div>
                    </a>

                    {{-- Menu --}}
                    <div class="hidden lg:flex items-center p-1.5 rounded-full bg-slate-100/60 border border-slate-200/60 backdrop-blur-md">
                        <a href="{{ route('user.dashboard') }}" class="px-6 py-2 rounded-full text-sm font-bold text-white bg-slate-900 shadow-lg shadow-slate-900/20 transition-all">Jelajah</a>
                        <a href="{{ route('user.bookings.index') }}" class="px-6 py-2 rounded-full text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-white transition-all">Tiket Saya</a>
                        <a href="{{ route('user.favorites.index') }}" class="px-6 py-2 rounded-full text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-white transition-all">Favorit</a>
                    </div>
                </div>

                {{-- User Actions --}}
                <div class="flex items-center gap-4">
                    <a href="{{ route('user.notifications.index') }}" class="relative p-3 rounded-full text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-md transition-all group">
                        <span class="absolute top-3 right-3 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white animate-pulse"></span>
                        <i class="ph-bold ph-bell text-xl group-hover:swing"></i>
                    </a>

                    <div class="relative ml-2" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 pl-1.5 pr-3 py-1.5 rounded-full border border-slate-200/80 hover:bg-white hover:shadow-md transition-all bg-white/50 backdrop-blur-sm group">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 p-[2px]">
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=fff&color=4f46e5" alt="User" class="rounded-full w-full h-full object-cover">
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-xs font-bold text-slate-900 leading-none">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-wide mt-0.5">Explorer</p>
                            </div>
                            <i class="ph-bold ph-caret-down text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="open" x-transition class="absolute right-0 mt-4 w-60 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-100 py-3 z-50 overflow-hidden" style="display: none;">
                            <div class="px-5 py-3 border-b border-slate-50">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="p-2 space-y-1">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition-colors"><i class="ph-bold ph-user"></i> Profil</a>
                                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-rose-500 hover:bg-rose-50 hover:text-rose-600 rounded-xl transition-colors"><i class="ph-bold ph-sign-out"></i> Keluar</button></form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- ==========================================
         MAIN CONTENT
    ========================================== --}}
    @php
        $filters = $filters ?? [
            'keyword'     => request('keyword'),
            'location'    => request('location'),
            'category_id' => request('category_id'),
        ];
        $events = $events ?? collect();

        // PHP Calculation (Safe Logic)
        $totalSpending = \App\Models\Booking::with('ticketType')
            ->where('user_id', Auth::id())
            ->where('status', 'approved')
            ->get()
            ->sum(function($booking) {
                return $booking->quantity * ($booking->ticketType->price ?? 0);
            });

        $totalTickets = \App\Models\Booking::where('user_id', Auth::id())->where('status', 'approved')->sum('quantity');
        $activeEventsCount = \App\Models\Event::where('date', '>=', now())->count();
    @endphp

    <main class="pt-32 pb-24 px-4 sm:px-6 max-w-[1500px] mx-auto min-h-screen relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- LEFT COLUMN (Main Content - 9 Cols) --}}
            <div class="lg:col-span-9 space-y-10">

                {{-- A. Welcome & Spending (Bento Grid) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-aos="fade-up">

                    {{-- 1. Hero Greeting --}}
                    <div class="md:col-span-2 glass-panel rounded-[2.5rem] p-10 relative overflow-hidden flex flex-col justify-center min-h-[260px] group transition-all duration-500 hover:shadow-2xl">
                        <div class="absolute top-0 right-0 w-80 h-80 bg-gradient-to-bl from-indigo-500/20 to-purple-500/10 rounded-full blur-[80px] -mr-10 -mt-10 group-hover:scale-110 transition-transform duration-1000"></div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="px-3 py-1 rounded-lg bg-white/60 border border-white/50 text-[10px] font-bold tracking-wider text-indigo-600 uppercase shadow-sm">
                                    👋 Dashboard
                                </span>
                                <span class="text-xs text-slate-500 font-medium font-mono-num">{{ now()->format('l, d F Y') }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900 mb-4 leading-tight">
                                Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">{{ Auth::user()->name }}</span>!
                            </h1>
                            <p class="text-slate-600 max-w-md text-lg leading-relaxed">
                                Ada <span class="font-bold text-slate-900 border-b-2 border-indigo-200">{{ $events->count() }} event</span> seru menanti. Jangan sampai kehabisan tiket momen spesialmu.
                            </p>
                        </div>
                    </div>

                    {{-- 2. Spending Widget (Dark Glass Contrast) --}}
                    <div class="rounded-[2.5rem] p-8 flex flex-col justify-between relative overflow-hidden group glass-dark transition-transform hover:-translate-y-2">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                        <div class="relative z-10 flex justify-between items-start mb-2">
                            <div>
                                <p class="text-[10px] text-slate-300 font-bold uppercase tracking-wider font-mono-num mb-1">Total Pengeluaran</p>
                                <h3 class="text-3xl font-mono-num font-bold text-white tracking-tight">
                                    Rp {{ number_format($totalSpending, 0, ',', '.') }}
                                </h3>
                            </div>
                             <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-indigo-300 group-hover:text-white transition-colors">
                                <i class="ph-bold ph-wallet text-2xl"></i>
                            </div>
                        </div>

                        <div class="h-24 w-full mt-4 relative">
                            <canvas id="spendingSparkline"></canvas>
                        </div>

                        <div class="relative z-10 flex items-center gap-2 mt-4">
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold border border-emerald-500/30">Aktif</span>
                            <span class="text-[10px] text-slate-400">Akumulasi tiket disetujui</span>
                        </div>
                    </div>
                </div>

                {{-- B. FILTER ISLAND (Searchable) --}}
                <div class="glass-panel rounded-[2rem] p-2 shadow-lg border border-white/80 sticky top-24 z-40" data-aos="fade-up" data-aos-delay="100">
                    <form method="GET" action="{{ route('user.dashboard') }}" class="flex flex-col md:flex-row gap-2">

                        <div class="flex-grow relative group">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <i class="ph-bold ph-magnifying-glass text-xl"></i>
                            </div>
                            <input type="text" name="keyword" value="{{ $filters['keyword'] }}"
                                class="glass-input-group w-full pl-14 pr-4 py-4 rounded-2xl text-base font-medium text-slate-800 placeholder-slate-400 focus:ring-0 focus:outline-none bg-white/50"
                                placeholder="Cari nama event, artis...">
                        </div>

                        <div class="md:w-1/4 relative"
                             x-data="{ open: false, search: '', selected: '{{ $filters['location'] ?? '' }}', options: ['Jakarta', 'Bali', 'Bandung', 'Surabaya', 'Yogyakarta', 'Medan'], get filtered() { return this.search === '' ? this.options : this.options.filter(o => o.toLowerCase().includes(this.search.toLowerCase())); }}">
                            <input type="hidden" name="location" x-model="selected">
                            <div @click="open = !open; if(open) $nextTick(() => $refs.locInput.focus())" @click.outside="open = false"
                                 class="custom-select-trigger glass-input-group w-full px-6 py-4 flex justify-between items-center rounded-2xl bg-white/50 font-medium text-base h-full"
                                 :class="open ? 'border-indigo-500 ring-2 ring-indigo-100' : ''">
                                <span x-text="selected ? selected : 'Pilih Lokasi'" :class="selected ? 'text-slate-900 font-bold' : 'text-slate-500'"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </div>
                            <div x-show="open" x-transition.opacity.duration.200ms class="custom-dropdown-menu absolute top-full mt-2 w-full rounded-2xl z-50 p-2 bg-white/95 backdrop-blur-xl border border-slate-100 shadow-2xl" style="display: none;">
                                <input x-ref="locInput" x-model="search" type="text" class="w-full border-0 border-b border-slate-100 px-3 py-2 text-sm focus:ring-0 bg-transparent placeholder-slate-400 font-medium" placeholder="Ketik kota...">
                                <div class="max-h-56 overflow-y-auto mt-2">
                                    <div @click="selected = ''; open = false" class="custom-dropdown-item px-4 py-2.5 text-sm text-slate-500 rounded-xl cursor-pointer hover:bg-slate-50">Semua Lokasi</div>
                                    <template x-for="option in filtered" :key="option">
                                        <div @click="selected = option; open = false" class="custom-dropdown-item px-4 py-2.5 text-sm text-slate-700 rounded-xl cursor-pointer hover:bg-indigo-50 hover:text-indigo-600 font-medium" x-text="option"></div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="md:w-1/4 relative" x-data="{ open: false, selectedId: '{{ $filters['category_id'] ?? '' }}' }">
                            <input type="hidden" name="category_id" x-model="selectedId">
                            <div @click="open = !open" @click.outside="open = false"
                                 class="custom-select-trigger glass-input-group w-full px-6 py-4 flex justify-between items-center rounded-2xl bg-white/50 font-medium text-base h-full"
                                 :class="open ? 'border-indigo-500 ring-2 ring-indigo-100' : ''">
                                <span x-text="selectedId ? 'Kategori Terpilih' : 'Kategori'" :class="selectedId ? 'text-indigo-600 font-bold' : 'text-slate-500'"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </div>
                            <div x-show="open" x-transition.opacity.duration.200ms class="custom-dropdown-menu absolute top-full mt-2 w-full rounded-2xl z-50 p-2 bg-white/95 backdrop-blur-xl border border-slate-100 shadow-2xl" style="display: none;">
                                <div @click="selectedId = ''; open = false" class="custom-dropdown-item px-4 py-2.5 text-sm text-slate-500 rounded-xl cursor-pointer hover:bg-slate-50">Semua Kategori</div>
                                @foreach (($categories ?? []) as $category)
                                    <div @click="selectedId = '{{ $category->id }}'; open = false" class="custom-dropdown-item px-4 py-2.5 text-sm text-slate-700 rounded-xl cursor-pointer font-medium hover:bg-indigo-50 hover:text-indigo-600 transition-colors" :class="selectedId == '{{ $category->id }}' ? 'bg-indigo-50 text-indigo-600' : ''">
                                        {{ $category->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn-radiant px-10 rounded-2xl font-bold shadow-lg shadow-indigo-500/30 text-base flex items-center justify-center gap-2 group transition-transform active:scale-95">
                            <span>Cari</span>
                            <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>

                {{-- C. EVENTS GRID --}}
                <div class="space-y-8">
                    <div class="flex items-center justify-between" data-aos="fade-left">
                         <h2 class="text-2xl font-display font-bold text-slate-900 flex items-center gap-3">
                            <span class="flex h-3 w-3 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                            </span>
                            Event Terbaru
                        </h2>
                    </div>

                    @if($events->isEmpty())
                        <div class="glass-panel rounded-[2.5rem] p-20 text-center border-dashed border-2 border-slate-300/70">
                            <div class="inline-flex p-6 rounded-full bg-slate-100/80 mb-6 text-slate-400 shadow-inner">
                                <i class="ph-duotone ph-magnifying-glass text-5xl"></i>
                            </div>
                            <h3 class="text-2xl font-heading font-bold text-slate-900 mb-3">Oops, Tidak Ada Hasil</h3>
                            <p class="text-slate-500 max-w-md mx-auto mb-8">Coba gunakan kata kunci lain atau reset filter pencarian Anda.</p>
                            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 text-white rounded-full font-bold text-sm shadow-lg hover:bg-slate-800 transition-all">
                                Reset Filter
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                            @foreach ($events as $event)
                                @php
                                    $cheapest = $event->ticketTypes->sortBy('price')->first();
                                    $price = $cheapest ? 'Rp ' . number_format($cheapest->price, 0, ',', '.') : 'Gratis';
                                    $isFavorited = false;
                                @endphp

                                <article class="holo-card group flex flex-col h-full bg-white/60" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                                    {{-- Image --}}
                                    <div class="relative h-64 overflow-hidden bg-slate-200">
                                        <img src="{{ $event->image ? asset($event->image) : 'https://source.unsplash.com/random/600x500?concert,event&sig='.$event->id }}"
                                             class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="{{ $event->name }}">

                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-transparent opacity-90"></div>

                                        <div class="absolute top-5 left-5">
                                            <span class="px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider border border-white/25 shadow-sm">
                                                {{ $event->category->name ?? 'Event' }}
                                            </span>
                                        </div>

                                        {{-- Date Badge --}}
                                        <div class="absolute bottom-5 left-5 text-white">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-3 text-center min-w-[55px] shadow-lg group-hover:bg-white/20 transition-colors">
                                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-indigo-200">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</span>
                                                    <span class="block text-2xl font-extrabold leading-none tracking-tight">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-white flex items-center gap-1.5"><i class="ph-fill ph-clock"></i> {{ $event->time }}</p>
                                                    <p class="text-xs font-medium text-slate-300 flex items-center gap-1 mt-0.5"><i class="ph-fill ph-map-pin"></i> {{ \Illuminate\Support\Str::limit($event->location, 20) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Body --}}
                                    <div class="p-7 flex-1 flex flex-col">
                                        <h3 class="text-xl font-display font-bold text-slate-900 mb-3 line-clamp-2 leading-snug group-hover:text-indigo-600 transition-colors">
                                            <a href="{{ route('user.bookings.create', ['event_id' => $event->id]) }}" class="focus:outline-none">
                                                <span class="absolute inset-0"></span>{{ $event->name }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-slate-500 line-clamp-2 mb-6 flex-1 leading-relaxed">{{ $event->description }}</p>

                                        <div class="pt-5 border-t border-slate-200/60 flex items-center justify-between relative z-20">
                                            <div>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Mulai Dari</p>
                                                <p class="text-xl font-bold text-slate-900 font-mono-num">{{ $price }}</p>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                {{-- Heart Button --}}
                                                <form method="POST" action="{{ route('user.favorites.store') }}" x-data="{ liked: {{ $isFavorited ? 'true' : 'false' }} }">
                                                    @csrf
                                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                                    <button type="submit" @click.stop="liked = !liked" class="btn-heart w-11 h-11 rounded-2xl border border-slate-200 flex items-center justify-center text-slate-400 hover:border-rose-200 bg-white shadow-sm" :class="{'active': liked}" title="Simpan ke Favorit">
                                                        <i class="ph-fill ph-heart text-xl transition-transform duration-300" :class="liked ? 'scale-110' : ''"></i>
                                                    </button>
                                                </form>

                                                <a href="{{ route('user.bookings.create', ['event_id' => $event->id]) }}" class="btn-radiant px-6 py-3 rounded-2xl text-xs font-bold shadow-xl shadow-indigo-500/20 uppercase tracking-wider">
                                                    Beli Tiket
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if(method_exists($events, 'hasPages') && $events->hasPages())
                            <div class="mt-16 flex justify-center">
                                <div class="glass-panel rounded-full p-2 shadow-sm">
                                    {{ $events->links() }}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- RIGHT COLUMN (Sidebar) --}}
            <div class="hidden lg:block lg:col-span-3 space-y-8 sticky top-28 h-fit">

                {{-- Calendar --}}
                <div class="glass-dark rounded-[2rem] p-6 text-center" data-aos="fade-left" data-aos-delay="200">
                    <h3 class="font-bold text-white mb-4 text-lg font-display">Kalender Event</h3>
                    <div class="bg-slate-800/50 rounded-2xl p-2 border border-slate-700">
                        <div id="mini-calendar" class="dark-theme"></div>
                    </div>
                </div>

                 {{-- Stats Grid --}}
                 <div class="grid grid-cols-2 gap-4" data-aos="fade-left" data-aos-delay="300">
                    <div class="glass-panel rounded-3xl p-5 flex flex-col items-center justify-center text-center bg-white/60">
                         <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Total Tiket</span>
                         <span class="text-3xl font-mono-num font-bold text-indigo-600">{{ $totalTickets }}</span>
                    </div>
                    <div class="glass-panel rounded-3xl p-5 flex flex-col items-center justify-center text-center bg-white/60">
                         <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Event Aktif</span>
                         <span class="text-3xl font-mono-num font-bold text-emerald-500">{{ $activeEventsCount }}</span>
                    </div>
                 </div>

                 {{-- Support --}}
                 <div class="rounded-[2rem] p-8 bg-gradient-to-br from-indigo-600 to-purple-700 text-white relative overflow-hidden group shadow-2xl shadow-indigo-500/30" data-aos="fade-left" data-aos-delay="400">
                      <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-white/20 transition-colors"></div>
                      <i class="ph-duotone ph-headset text-4xl mb-4 opacity-80"></i>
                      <h3 class="font-bold text-xl mb-2 relative z-10 font-display">Butuh Bantuan?</h3>
                      <p class="text-indigo-100 text-sm mb-6 relative z-10 leading-relaxed">Tim support kami siap membantu Anda 24/7 untuk segala kendala.</p>
                      <button class="w-full py-3 bg-white text-indigo-700 text-sm font-bold rounded-xl hover:bg-indigo-50 transition-all relative z-10 shadow-lg">Chat Support</button>
                 </div>
            </div>
        </div>
    </main>

    {{-- =======================================================================
         6. FOOTER
    ======================================================================= --}}
    <footer class="bg-white border-t border-slate-200/80 pt-12 pb-8 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white font-bold text-sm">ET</div>
                    <span class="font-display font-bold text-lg text-slate-900">EventTicket</span>
                </div>
                <div class="flex gap-6 text-sm text-slate-500 font-medium">
                    <a href="#" class="hover:text-indigo-600 transition-colors">Tentang Kami</a>
                    <a href="#" class="hover:text-indigo-600 transition-colors">Bantuan</a>
                    <a href="#" class="hover:text-indigo-600 transition-colors">Privasi</a>
                </div>
                <p class="text-xs text-slate-400">
                    &copy; {{ date('Y') }} EventTicket Inc.
                </p>
            </div>
        </div>
    </footer>

    {{-- =======================================================================
         7. SCRIPTS
    ======================================================================= --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Init Animations
            AOS.init({ duration: 800, once: true, offset: 50 });

            // Init Calendar
            flatpickr("#mini-calendar", {
                inline: true, dateFormat: "Y-m-d", locale: { firstDayOfWeek: 1 }, disableMobile: "true",
                nextArrow: '<i class="ph-bold ph-caret-right text-white"></i>',
                prevArrow: '<i class="ph-bold ph-caret-left text-white"></i>'
            });

            // Init Chart (Sparkline Style White)
            const ctx = document.getElementById('spendingSparkline').getContext('2d');
            let gradient = ctx.createLinearGradient(0, 0, 0, 100);
            gradient.addColorStop(0, 'rgba(255, 255, 255, 0.4)');
            gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['M1', 'M2', 'M3', 'M4', 'M5', 'M6'],
                    datasets: [{
                        label: 'Pengeluaran',
                        data: [15, 30, 20, 50, 25, 60], // Mockup Data for visualization
                        borderColor: '#ffffff',
                        backgroundColor: gradient,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: false } },
                    scales: { x: { display: false }, y: { display: false } },
                    elements: { point: { radius: 0 } },
                    layout: { padding: 0 }
                }
            });
        });
    </script>
</body>
</html>
