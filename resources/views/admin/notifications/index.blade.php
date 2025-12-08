<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Notification Stream - Admin Panel">

    <title>Notifications - {{ config('app.name', 'EventTicket') }}</title>

    {{-- =======================================================================
         1. ASSETS & LIBRARIES
    ======================================================================= --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>

    {{-- =======================================================================
         2. CUSTOM STYLES (Nebula Stream Theme)
    ======================================================================= --}}
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; overflow-x: hidden; }
        h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* --- Dynamic Background --- */
        .aurora-bg { position: fixed; inset: 0; z-index: -1; background: #ffffff; overflow: hidden; }
        .orb { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.5; mix-blend-mode: multiply; animation: float 25s infinite ease-in-out; }
        .orb-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #a5b4fc; }
        .orb-2 { bottom: -20%; right: -20%; width: 60vw; height: 60vw; background: #fbcfe8; animation-delay: -5s; }
        .orb-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: #99f6e4; opacity: 0.4; animation-delay: -10s; }

        .grid-pattern {
            position: fixed; inset: 0; z-index: -1; opacity: 0.3;
            background-image: linear-gradient(#e2e8f0 1px, transparent 1px), linear-gradient(90deg, #e2e8f0 1px, transparent 1px);
            background-size: 50px 50px;
            mask-image: radial-gradient(circle at center, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 80%);
        }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(30px, -30px) scale(1.1); } }

        /* --- Glass Sidebar --- */
        .glass-sidebar { background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(20px); border-right: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 4px 0 30px rgba(0, 0, 0, 0.03); }

        /* --- Stats Card (3D) --- */
        .stat-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 1.5rem; padding: 1.25rem;
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
        }
        .stat-card:hover { transform: translateY(-5px); background: #fff; box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.15); border-color: #a5b4fc; }

        /* --- Stream Card (Notification) --- */
        .stream-card {
            position: relative; overflow: hidden;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex; gap: 1.5rem; align-items: flex-start;
        }

        /* Unread State Style */
        .stream-unread {
            background: rgba(255, 255, 255, 0.95);
            border-left: 6px solid #6366f1;
            box-shadow: 0 10px 30px -5px rgba(99, 102, 241, 0.15);
        }
        .stream-unread::after {
            content: ''; position: absolute; top: 1.5rem; right: 1.5rem;
            width: 8px; height: 8px; background: #ef4444; border-radius: 50%;
            box-shadow: 0 0 10px #ef4444; animation: pulse-red 2s infinite;
        }

        /* Read State Style */
        .stream-read {
            opacity: 0.75;
            background: rgba(248, 250, 252, 0.5);
            border-left: 6px solid #cbd5e1;
        }
        .stream-read:hover { opacity: 1; transform: translateX(5px); background: #fff; }

        @keyframes pulse-red { 0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); } 70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); } 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }

        /* Icon Container */
        .icon-vessel {
            width: 52px; height: 52px; border-radius: 1.2rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; flex-shrink: 0;
            transition: transform 0.3s;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.05);
        }
        .stream-unread .icon-vessel { background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); color: #4338ca; }
        .stream-read .icon-vessel { background: #f1f5f9; color: #94a3b8; }
        .stream-card:hover .icon-vessel { transform: scale(1.1) rotate(5deg); }

        /* Actions */
        .btn-mark {
            padding: 0.5rem 1.2rem; border-radius: 99px;
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
            background: #fff; border: 1px solid #e2e8f0; color: #64748b;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer;
        }
        .btn-mark:hover { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15); transform: translateY(-2px); }

        /* Filter Tabs */
        .tab-btn {
            padding: 0.6rem 1.5rem; border-radius: 1rem; font-size: 0.85rem; font-weight: 600;
            transition: all 0.3s; border: 1px solid transparent;
        }
        .tab-btn.active { background: #0F172A; color: white; box-shadow: 0 8px 20px -5px rgba(15, 23, 42, 0.3); }
        .tab-btn.inactive { background: white; color: #64748b; border-color: #e2e8f0; }
        .tab-btn.inactive:hover { background: #f8fafc; color: #334155; }

        /* Sidebar Link */
        .nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-radius: 1rem; color: #64748b; font-weight: 500; transition: all 0.2s ease; }
        .nav-link:hover, .nav-link.active { background: #eef2ff; color: #4338ca; box-shadow: 0 4px 6px -2px rgba(99, 102, 241, 0.05); }
        .nav-link.active { font-weight: 700; border: 1px solid #e0e7ff; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="antialiased text-slate-800 flex min-h-screen" x-data="{ sidebarOpen: false, filter: 'all' }">

    {{-- DYNAMIC BACKGROUND --}}
    <div class="aurora-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="grid-pattern"></div>
    </div>

    {{-- =======================================================================
         3. SIDEBAR NAVIGATION
    ======================================================================= --}}
    <aside class="fixed inset-y-0 left-0 z-50 w-72 glass-sidebar hidden lg:flex flex-col transition-transform duration-300 transform lg:translate-x-0">
        <div class="h-24 flex items-center px-8 border-b border-slate-200/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">AD</div>
                <div class="flex flex-col"><span class="font-display font-bold text-xl tracking-tight text-slate-900">Event<span class="text-indigo-600">Ticket</span></span><span class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Administrator</span></div>
            </div>
        </div>

        <div class="flex-1 px-4 py-8 space-y-1.5 overflow-y-auto">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Main Control</p>
            <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="ph-duotone ph-squares-four text-xl"></i> Dashboard</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link"><i class="ph-duotone ph-users text-xl"></i> User Management</a>
            <a href="{{ route('admin.events.index') }}" class="nav-link"><i class="ph-duotone ph-calendar-plus text-xl"></i> Kelola Event</a>
            <a href="{{ route('admin.bookings.index') }}" class="nav-link"><i class="ph-duotone ph-ticket text-xl"></i> Kelola Booking</a>

            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-2">System</p>
            <a href="{{ route('admin.categories.index') }}" class="nav-link"><i class="ph-duotone ph-tag text-xl"></i> Kategori</a>
            <a href="{{ route('admin.notifications.index') }}" class="nav-link active"><i class="ph-duotone ph-bell text-xl"></i> Notifikasi</a>
            <a href="{{ route('admin.analytics.index') }}" class="nav-link"><i class="ph-duotone ph-chart-line-up text-xl"></i> Analytics</a>
        </div>

        <div class="p-4 border-t border-slate-200/50">
            <div class="bg-white/50 border border-slate-200 p-3 rounded-2xl flex items-center gap-3 cursor-pointer hover:bg-white transition-all shadow-sm relative" x-data="{ open: false }">
                <div class="h-10 w-10 rounded-full bg-slate-900 p-[2px]"><img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=fff&color=0f172a" class="rounded-full w-full h-full object-cover"></div>
                <div class="flex-1 min-w-0" @click="open = !open">
                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">Super Admin</p>
                </div>
                <div @click="open = !open"><i class="ph-bold ph-caret-up text-slate-400"></i></div>
                <div x-show="open" @click.outside="open = false" class="absolute bottom-full left-0 w-full mb-2 bg-white rounded-xl shadow-xl border border-slate-100 p-1 z-50 animate__animated animate__fadeInUp animate__faster" style="display: none;">
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-rose-500 hover:bg-rose-50 rounded-lg font-bold transition-colors"><i class="ph-bold ph-sign-out"></i> Keluar</button></form>
                </div>
            </div>
        </div>
    </aside>

    {{-- =======================================================================
         4. MAIN CONTENT AREA
    ======================================================================= --}}
    <div class="lg:ml-72 flex-1 flex flex-col min-h-screen relative z-10 transition-all">

        {{-- Mobile Header --}}
        <header class="h-20 flex items-center justify-between px-6 lg:px-10 lg:hidden bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors"><i class="ph-bold ph-list text-2xl"></i></button>
                <span class="font-bold text-lg text-slate-900">Notifikasi</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="p-2 rounded-lg text-rose-500 hover:bg-rose-50"><i class="ph-bold ph-sign-out text-2xl"></i></button></form>
        </header>

        <div class="p-6 lg:p-12 space-y-12">

            {{-- A. Header & Stats --}}
            <div class="space-y-8" data-aos="fade-down">
                <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900 mb-3">
                            Notification <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Stream</span>
                        </h1>
                        <p class="text-slate-500 text-lg">Pusat informasi real-time dan log aktivitas sistem.</p>
                    </div>

                    {{-- Filter Tabs --}}
                    <div class="flex gap-2 bg-white/50 p-1 rounded-2xl border border-slate-200/50 backdrop-blur-sm">
                        <button @click="filter = 'all'"
                                :class="filter === 'all' ? 'active shadow-md' : 'inactive'"
                                class="tab-btn">
                            Semua
                        </button>
                        <button @click="filter = 'unread'"
                                :class="filter === 'unread' ? 'active shadow-md' : 'inactive'"
                                class="tab-btn flex items-center gap-2">
                            Belum Dibaca
                            @if($notifications->where('status', 'unread')->count() > 0)
                                <span class="flex h-2 w-2 rounded-full bg-rose-500"></span>
                            @endif
                        </button>
                    </div>
                </div>

                {{-- Stats Grid (3D Cards) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="stat-card group" data-tilt data-tilt-max="5">
                        <div class="flex justify-between items-start mb-2">
                            <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600"><i class="ph-duotone ph-bell-ringing text-xl"></i></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total</span>
                        </div>
                        <p class="text-3xl font-mono-num font-bold text-slate-900">{{ $notifications->count() }}</p>
                        <p class="text-xs text-slate-500 mt-1">Semua Notifikasi</p>
                    </div>
                    <div class="stat-card group" data-tilt data-tilt-max="5">
                        <div class="flex justify-between items-start mb-2">
                            <div class="p-2.5 bg-rose-50 rounded-xl text-rose-600"><i class="ph-duotone ph-envelope-open text-xl"></i></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Unread</span>
                        </div>
                        <p class="text-3xl font-mono-num font-bold text-rose-600">{{ $notifications->where('status', 'unread')->count() }}</p>
                        <p class="text-xs text-slate-500 mt-1">Perlu Perhatian</p>
                    </div>
                    <div class="stat-card group" data-tilt data-tilt-max="5">
                        <div class="flex justify-between items-start mb-2">
                            <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600"><i class="ph-duotone ph-check-circle text-xl"></i></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Read</span>
                        </div>
                        <p class="text-3xl font-mono-num font-bold text-emerald-600">{{ $notifications->where('status', 'read')->count() }}</p>
                        <p class="text-xs text-slate-500 mt-1">Sudah Dibaca</p>
                    </div>
                </div>
            </div>

            {{-- B. Notification Feed --}}
            <div class="space-y-5 max-w-4xl mx-auto">
                @if ($notifications->isEmpty())
                    <div class="p-24 text-center rounded-[3rem] border-2 border-dashed border-slate-300 bg-white/40 backdrop-blur-sm" data-aos="zoom-in">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400 animate-pulse">
                            <i class="ph-duotone ph-bell-slash text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Hening...</h3>
                        <p class="text-slate-500 text-lg">Tidak ada notifikasi baru untuk saat ini.</p>
                    </div>
                @else
                    @foreach ($notifications as $index => $notif)
                        <article class="stream-card {{ $notif->status === 'unread' ? 'stream-unread' : 'stream-read' }}"
                                 x-show="filter === 'all' || (filter === 'unread' && '{{ $notif->status }}' === 'unread')"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 data-aos="fade-up"
                                 data-aos-delay="{{ $index * 50 }}">

                            {{-- Icon --}}
                            <div class="icon-vessel">
                                @if(str_contains(strtolower($notif->title), 'booking'))
                                    <i class="ph-duotone ph-ticket"></i>
                                @elseif(str_contains(strtolower($notif->title), 'event'))
                                    <i class="ph-duotone ph-calendar-star"></i>
                                @elseif(str_contains(strtolower($notif->title), 'user'))
                                    <i class="ph-duotone ph-user-circle"></i>
                                @else
                                    <i class="ph-duotone ph-bell-ringing"></i>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-2">
                                    <h4 class="text-lg font-bold text-slate-900 leading-tight">
                                        {{ $notif->title }}
                                    </h4>
                                    <span class="text-[10px] font-mono-num font-bold text-slate-400 uppercase tracking-widest bg-slate-100 px-2 py-1 rounded-md whitespace-nowrap">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <p class="text-sm text-slate-600 leading-relaxed mb-4">
                                    {{ $notif->message }}
                                </p>

                                {{-- Action --}}
                                @if($notif->status === 'unread')
                                    <form method="POST" action="{{ route('admin.notifications.read', $notif->id) }}">
                                        @csrf
                                        <button type="submit" class="btn-mark">
                                            <i class="ph-bold ph-checks text-lg"></i>
                                            <span>Tandai Dibaca</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @endforeach
                @endif
            </div>

        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({ duration: 800, once: true, offset: 60 });

            VanillaTilt.init(document.querySelectorAll(".stat-card"), {
                max: 15, speed: 400, glare: true, "max-glare": 0.2, scale: 1.05
            });
        });
    </script>
</body>
</html>
