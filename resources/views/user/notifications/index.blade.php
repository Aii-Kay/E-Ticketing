<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Notifikasi - {{ config('app.name', 'EventTicket') }}</title>

    {{-- =======================================================================
         1. ASSETS & LIBRARIES
    ======================================================================= --}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- =======================================================================
         2. CUSTOM STYLES (Ethereal Theme)
    ======================================================================= --}}
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* --- 2.1 Ethereal Background --- */
        .aurora-bg {
            position: fixed; inset: 0; z-index: -1;
            background: #ffffff;
            overflow: hidden;
        }
        .aurora-blob {
            position: absolute; border-radius: 50%;
            filter: blur(90px); opacity: 0.45;
            mix-blend-mode: multiply;
            animation: aurora-move 20s infinite alternate ease-in-out;
        }
        /* Custom colors for notification page: Calmer tones */
        .blob-1 { top: -20%; left: -10%; width: 60vw; height: 60vw; background: #e0e7ff; animation-delay: 0s; } /* Indigo 100 */
        .blob-2 { bottom: -20%; right: -20%; width: 50vw; height: 50vw; background: #f0f9ff; animation-delay: -5s; } /* Sky 50 */
        .blob-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: #fdf2f8; opacity: 0.4; animation-delay: -8s; } /* Pink 50 */

        @keyframes aurora-move {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            50% { transform: translate(20px, -20px) scale(1.05) rotate(5deg); }
            100% { transform: translate(-20px, 20px) scale(0.95) rotate(-5deg); }
        }

        /* --- 2.2 Glass Components --- */
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        /* --- 2.3 Notification Card Styling --- */
        .notif-card {
            position: relative;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        /* Unread State: More prominent */
        .notif-card.unread {
            background: rgba(255, 255, 255, 0.95);
            border-color: #e0e7ff; /* Indigo 100 */
            box-shadow: 0 8px 20px -4px rgba(99, 102, 241, 0.08);
            border-left: 4px solid #6366f1; /* Indigo 500 */
        }

        /* Read State: Dimmed */
        .notif-card.read {
            opacity: 0.85;
            background: rgba(255, 255, 255, 0.4);
            border-left: 4px solid transparent;
        }
        .notif-card.read:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.8);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* Icon Wrapper */
        .icon-box {
            width: 48px; height: 48px;
            border-radius: 1rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }
        .notif-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        /* Filter Tab */
        .filter-tab {
            position: relative;
            padding: 0.5rem 1.25rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            cursor: pointer;
            z-index: 1;
        }
        .filter-tab.active {
            color: white;
        }
        .filter-tab:not(.active) {
            color: #64748b;
        }
        .filter-tab:not(.active):hover {
            color: #334155;
            background-color: rgba(255,255,255,0.5);
        }

        /* Sliding Background for Tabs */
        .tab-bg {
            position: absolute;
            background: #1e293b;
            border-radius: 999px;
            z-index: -1;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="antialiased text-slate-800" x-data="notificationApp()">

    {{-- BACKGROUND --}}
    <div class="aurora-bg">
        <div class="aurora-blob blob-1"></div>
        <div class="aurora-blob blob-2"></div>
        <div class="aurora-blob blob-3"></div>
    </div>

    {{-- =======================================================================
         3. NAVBAR (Glass)
    ======================================================================= --}}
    <nav class="fixed top-0 w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('user.dashboard') }}" class="group flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/50 transition-all">
                    <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                        <i class="ph-bold ph-arrow-left text-white text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-slate-900 leading-none group-hover:text-indigo-600 transition-colors">Kembali</span>
                        <span class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Dashboard</span>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-3 bg-white/50 backdrop-blur-md border border-white/60 px-4 py-2 rounded-full shadow-sm">
                    <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-amber-400 to-orange-500 p-[2px]">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=fff&color=d97706" class="rounded-full w-full h-full object-cover">
                    </div>
                    <span class="text-sm font-bold text-slate-700">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </nav>

    {{-- =======================================================================
         4. MAIN CONTENT
    ======================================================================= --}}
    <main class="flex-grow pt-32 pb-20 px-4 sm:px-6 max-w-5xl mx-auto w-full relative z-10">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12" data-aos="fade-down">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                        <i class="ph-fill ph-bell-ringing text-2xl"></i>
                    </div>
                    <h1 class="text-3xl font-heading font-extrabold text-slate-900">Pusat Notifikasi</h1>
                </div>
                <p class="text-slate-500 max-w-md text-sm leading-relaxed ml-1">
                    Pantau semua pembaruan penting mengenai tiket, status pembayaran, dan info akun Anda di sini.
                </p>
            </div>

            {{-- Smart Filters --}}
            <div class="relative bg-white/60 p-1 rounded-full border border-slate-200 shadow-sm flex items-center">
                <button @click="setFilter('all')" class="filter-tab" :class="{ 'active': filter === 'all' }">Semua</button>
                <button @click="setFilter('unread')" class="filter-tab" :class="{ 'active': filter === 'unread' }">
                    Belum Dibaca
                    @if($notifications->where('status', 'unread')->count() > 0)
                        <span class="ml-1 px-1.5 py-0.5 rounded-full bg-rose-500 text-[9px] text-white">{{ $notifications->where('status', 'unread')->count() }}</span>
                    @endif
                </button>

                {{-- Sliding Background Animation Logic (Simplified via Class) --}}
                <div class="absolute inset-y-1 bg-slate-900 rounded-full transition-all duration-300 z-0"
                     :style="indicatorStyle"></div>
            </div>
        </div>

        {{-- Notifications List --}}
        <div class="space-y-4 min-h-[400px]">
            @if ($notifications->isEmpty())
                {{-- Empty State --}}
                <div class="glass-panel rounded-[2.5rem] p-16 text-center border-dashed border-2 border-slate-300/50" data-aos="zoom-in">
                    <div class="relative w-24 h-24 mx-auto mb-6">
                        <div class="absolute inset-0 bg-indigo-100 rounded-full animate-ping opacity-30"></div>
                        <div class="relative w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg border border-indigo-50">
                            <i class="ph-duotone ph-bell-slash text-4xl text-slate-300"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-heading font-bold text-slate-900 mb-2">Hening...</h3>
                    <p class="text-slate-500 text-sm">Belum ada notifikasi baru untuk Anda saat ini.</p>
                </div>
            @else
                @foreach ($notifications as $notification)
                    <div class="notif-item-wrapper"
                         x-show="filter === 'all' || (filter === 'unread' && '{{ $notification->status }}' === 'unread')"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0">

                        <div class="notif-card p-5 flex flex-col md:flex-row gap-5 items-start md:items-center {{ $notification->status === 'unread' ? 'unread' : 'read' }}" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">

                            {{-- Dynamic Icon --}}
                            @php
                                $title = strtolower($notification->title);
                                $iconColor = 'bg-slate-100 text-slate-500';
                                $iconClass = 'ph-info';

                                if (str_contains($title, 'tiket') || str_contains($title, 'booking')) {
                                    $iconColor = 'bg-violet-100 text-violet-600';
                                    $iconClass = 'ph-ticket';
                                } elseif (str_contains($title, 'bayar') || str_contains($title, 'lunas')) {
                                    $iconColor = 'bg-emerald-100 text-emerald-600';
                                    $iconClass = 'ph-check-circle';
                                } elseif (str_contains($title, 'batal') || str_contains($title, 'gagal')) {
                                    $iconColor = 'bg-rose-100 text-rose-600';
                                    $iconClass = 'ph-x-circle';
                                } elseif (str_contains($title, 'promo') || str_contains($title, 'diskon')) {
                                    $iconColor = 'bg-amber-100 text-amber-600';
                                    $iconClass = 'ph-tag';
                                }
                            @endphp

                            <div class="icon-box {{ $iconColor }} shadow-sm shrink-0">
                                <i class="ph-fill {{ $iconClass }}"></i>
                            </div>

                            {{-- Content --}}
                            <div class="flex-grow">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1">
                                    <h4 class="text-base font-bold text-slate-900 {{ $notification->status === 'unread' ? '' : 'opacity-80' }}">
                                        {{ $notification->title }}
                                        @if($notification->status === 'unread')
                                            <span class="ml-2 inline-block w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                        @endif
                                    </h4>
                                    <span class="text-xs text-slate-400 font-mono-num flex items-center gap-1">
                                        <i class="ph-fill ph-clock"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed {{ $notification->status === 'unread' ? '' : 'opacity-70' }}">
                                    {{ $notification->message }}
                                </p>
                            </div>

                            {{-- Action --}}
                            @if ($notification->status === 'unread')
                                <div class="shrink-0 self-end md:self-center">
                                    <form action="{{ route('user.notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="group flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition-all shadow-sm">
                                            <span>Tandai Dibaca</span>
                                            <i class="ph-bold ph-check text-slate-400 group-hover:text-indigo-600 transition-colors"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="shrink-0 self-end md:self-center">
                                    <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-400 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                        Dibaca
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </main>

    {{-- =======================================================================
         5. FOOTER
    ======================================================================= --}}
    <footer class="bg-white border-t border-slate-200/80 py-8 relative z-10 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} EventTicket Inc. All rights reserved.
    </footer>

    {{-- =======================================================================
         6. SCRIPTS
    ======================================================================= --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        function notificationApp() {
            return {
                filter: 'all',

                // Logic for sliding background on tabs
                get indicatorStyle() {
                    // Simple logic for 2 buttons. Adjust width/left based on button sizes.
                    // This is an estimation. For precise px usage, $refs is better.
                    if (this.filter === 'all') {
                        return 'left: 4px; width: 85px;';
                    } else {
                        return 'left: 95px; width: 130px;';
                    }
                },

                setFilter(val) {
                    this.filter = val;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({ duration: 800, once: true, offset: 50 });
        });
    </script>
</body>
</html>
