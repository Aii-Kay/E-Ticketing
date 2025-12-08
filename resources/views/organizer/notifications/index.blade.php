<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Notifikasi Organizer - EventTicket">

    <title>Notifikasi - {{ config('app.name', 'EventTicket') }}</title>

    {{-- =======================================================================
         1. ASSETS & LIBRARIES
    ======================================================================= --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- =======================================================================
         2. CUSTOM STYLES (Light & Ethereal Theme)
    ======================================================================= --}}
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC; /* Slate 50 */
            color: #0F172A; /* Slate 900 */
            overflow-x: hidden;
        }
        h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* --- Aurora Background --- */
        .aurora-bg { position: fixed; inset: 0; z-index: -1; background: #ffffff; overflow: hidden; }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.5;
            mix-blend-mode: multiply; animation: float 25s infinite ease-in-out;
        }
        .orb-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #fef3c7; animation-delay: 0s; } /* Amber */
        .orb-2 { bottom: -20%; right: -20%; width: 60vw; height: 60vw; background: #e0e7ff; animation-delay: -5s; } /* Indigo */
        .orb-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: #dbeafe; opacity: 0.4; animation-delay: -10s; } /* Blue */

        .noise-texture {
            position: absolute; inset: 0; opacity: 0.3; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.1'/%3E%3C/svg%3E");
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        /* --- Glass Sidebar --- */
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(226, 232, 240, 0.6);
        }

        /* --- Notification Card --- */
        .notif-card {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; overflow: hidden;
        }
        .notif-card:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        /* Unread State Highlight */
        .notif-unread {
            background: #f0f9ff; /* Sky 50 */
            border-left: 4px solid #3b82f6; /* Blue 500 */
        }
        .notif-read {
            border-left: 4px solid transparent;
            opacity: 0.85;
        }

        /* Sidebar Link */
        .nav-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.75rem 1rem; border-radius: 0.75rem;
            color: #64748b; font-weight: 500; transition: all 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            background: #eef2ff; color: #4f46e5;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.05);
        }
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
        <div class="noise-texture"></div>
    </div>

    {{-- =======================================================================
         3. SIDEBAR NAVIGATION
    ======================================================================= --}}
    <aside class="fixed inset-y-0 left-0 z-50 w-72 glass-sidebar hidden lg:flex flex-col transition-transform duration-300 transform lg:translate-x-0">
        <div class="h-24 flex items-center px-8 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/20">EO</div>
                <div class="flex flex-col">
                    <span class="font-display font-bold text-xl tracking-tight text-slate-900">Event<span class="text-indigo-600">Ticket</span></span>
                    <span class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Organizer Panel</span>
                </div>
            </div>
        </div>
        <div class="flex-1 px-4 py-8 space-y-1 overflow-y-auto">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Main Menu</p>
            <a href="{{ route('organizer.dashboard') }}" class="nav-link">
                <i class="ph-bold ph-squares-four text-lg"></i> Dashboard
            </a>
            <a href="{{ route('organizer.events.index') }}" class="nav-link">
                <i class="ph-bold ph-calendar-plus text-lg"></i> Event Saya
            </a>
            <a href="{{ route('organizer.bookings.index') }}" class="nav-link">
                <i class="ph-bold ph-ticket text-lg"></i> Manajemen Booking
            </a>
            <a href="{{ route('organizer.notifications.index') }}" class="nav-link active">
                <i class="ph-bold ph-bell text-lg"></i> Notifikasi
                @if($notifications->where('status', 'unread')->count() > 0)
                    <span class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $notifications->where('status', 'unread')->count() }}</span>
                @endif
            </a>
            <a href="{{ route('organizer.analytics.index') }}" class="nav-link">
                <i class="ph-bold ph-chart-line-up text-lg"></i> Analytics
            </a>
        </div>

        {{-- Profile Footer & LOGOUT --}}
        <div class="p-4 border-t border-slate-100">
            <div class="bg-white/50 border border-slate-200 p-3 rounded-2xl flex items-center gap-3 cursor-pointer hover:bg-white transition-colors relative"
                 x-data="{ open: false }">

                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-500 to-pink-500 p-[2px]">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=f1f5f9&color=4f46e5" class="rounded-full w-full h-full object-cover">
                </div>

                <div class="flex-1 min-w-0" @click="open = !open">
                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">Organizer</p>
                </div>

                {{-- Toggle Icon --}}
                <div @click="open = !open">
                    <i class="ph-bold ph-caret-up text-slate-400"></i>
                </div>

                {{-- Logout Popup --}}
                <div x-show="open" @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute bottom-full left-0 w-full mb-2 bg-white rounded-xl shadow-xl border border-slate-100 p-1 z-50"
                     style="display: none;">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-rose-500 hover:bg-rose-50 rounded-lg font-bold transition-colors">
                            <i class="ph-bold ph-sign-out text-lg"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- =======================================================================
         4. MAIN CONTENT
    ======================================================================= --}}
    <div class="lg:ml-72 flex-1 flex flex-col min-h-screen relative z-10 transition-all">

        {{-- Mobile Header --}}
        <header class="h-20 flex items-center justify-between px-6 lg:px-10 lg:hidden bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors"><i class="ph-bold ph-list text-2xl"></i></button>
                <span class="font-bold text-lg text-slate-900">Notifikasi</span>
            </div>

            {{-- Mobile Logout Button --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 rounded-lg text-rose-500 hover:bg-rose-50 transition-colors">
                    <i class="ph-bold ph-sign-out text-2xl"></i>
                </button>
            </form>
        </header>

        <div class="p-6 lg:p-12 max-w-5xl mx-auto w-full">

            {{-- Header Section --}}
            <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10" data-aos="fade-down">
                <div>
                    <h1 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900 mb-2">
                        Pusat <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Notifikasi</span>
                    </h1>
                    <p class="text-slate-500 text-lg">Pantau aktivitas terbaru terkait event dan penjualan Anda.</p>
                </div>

                {{-- Filter Tabs --}}
                <div class="flex p-1 bg-white border border-slate-200 rounded-xl shadow-sm">
                    <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-slate-50'" class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all">Semua</button>
                    <button @click="filter = 'unread'" :class="filter === 'unread' ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:bg-slate-50'" class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                        Belum Dibaca
                    </button>
                </div>
            </div>

            {{-- Notification List --}}
            <div class="space-y-4 min-h-[400px]">
                @if ($notifications->isEmpty())
                    {{-- Empty State --}}
                    <div class="p-20 text-center rounded-[2.5rem] border-2 border-dashed border-slate-200 bg-white/40" data-aos="zoom-in">
                        <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-300">
                            <i class="ph-duotone ph-bell-slash text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Semuanya Bersih</h3>
                        <p class="text-slate-500 text-lg">Tidak ada notifikasi baru untuk Anda saat ini.</p>
                    </div>
                @else
                    @foreach ($notifications as $notification)
                        <div class="notif-wrapper"
                             x-show="filter === 'all' || (filter === 'unread' && '{{ $notification->status }}' === 'unread')"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">

                            <div class="notif-card p-6 flex flex-col md:flex-row gap-6 items-start md:items-center {{ $notification->status === 'unread' ? 'notif-unread' : 'notif-read' }}">

                                {{-- Icon Logic --}}
                                @php
                                    $title = strtolower($notification->title);
                                    $iconColor = 'bg-slate-100 text-slate-500';
                                    $iconClass = 'ph-info';

                                    if (str_contains($title, 'booking') || str_contains($title, 'tiket')) {
                                        $iconColor = 'bg-indigo-100 text-indigo-600';
                                        $iconClass = 'ph-ticket';
                                    } elseif (str_contains($title, 'event')) {
                                        $iconColor = 'bg-purple-100 text-purple-600';
                                        $iconClass = 'ph-calendar-star';
                                    } elseif (str_contains($title, 'pembayaran') || str_contains($title, 'lunas')) {
                                        $iconColor = 'bg-emerald-100 text-emerald-600';
                                        $iconClass = 'ph-check-circle';
                                    } elseif (str_contains($title, 'batal')) {
                                        $iconColor = 'bg-rose-100 text-rose-600';
                                        $iconClass = 'ph-x-circle';
                                    }
                                @endphp

                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-sm {{ $iconColor }}">
                                        <i class="ph-bold {{ $iconClass }}"></i>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 w-full">
                                    <div class="flex justify-between items-start mb-1.5">
                                        <h4 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                            {{ $notification->title }}
                                            @if($notification->status === 'unread')
                                                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 text-[10px] font-extrabold uppercase tracking-wider">Baru</span>
                                            @endif
                                        </h4>
                                        <span class="text-xs text-slate-400 font-mono-num flex items-center gap-1 whitespace-nowrap bg-white/50 px-2 py-1 rounded-lg">
                                            <i class="ph-fill ph-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-600 leading-relaxed {{ $notification->status === 'unread' ? 'font-medium' : '' }}">
                                        {{ $notification->message }}
                                    </p>
                                </div>

                                {{-- Actions --}}
                                @if($notification->status === 'unread')
                                    <div class="shrink-0 self-end md:self-center mt-2 md:mt-0">
                                        <form action="{{ route('organizer.notifications.read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-2 text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:border-indigo-300 hover:text-indigo-600 px-4 py-2.5 rounded-xl transition-all shadow-sm group">
                                                <i class="ph-bold ph-check-circle text-lg text-slate-400 group-hover:text-indigo-500"></i>
                                                Tandai Dibaca
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="shrink-0 self-end md:self-center">
                                        <span class="text-slate-400"><i class="ph-bold ph-check-circle text-2xl"></i></span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({ duration: 800, once: true, offset: 50 });
        });
    </script>
</body>
</html>
