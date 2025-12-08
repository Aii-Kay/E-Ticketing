<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Booking Command Center">

    <title>Booking Management - {{ config('app.name', 'EventTicket') }}</title>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- =======================================================================
         2. CUSTOM STYLES (Luminous Apex Theme)
    ======================================================================= --}}
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; overflow-x: hidden; }
        h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* Aurora Background */
        .aurora-bg { position: fixed; inset: 0; z-index: -1; background: #ffffff; overflow: hidden; }
        .orb { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.5; mix-blend-mode: multiply; animation: float 25s infinite ease-in-out; }
        .orb-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #c7d2fe; }
        .orb-2 { bottom: -20%; right: -20%; width: 60vw; height: 60vw; background: #fbcfe8; animation-delay: -5s; }
        .orb-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: #bbf7d0; opacity: 0.4; animation-delay: -10s; }
        .noise-texture { position: absolute; inset: 0; opacity: 0.4; pointer-events: none; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.1'/%3E%3C/svg%3E"); }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(30px, -30px) scale(1.1); } }

        /* Glass Components */
        .glass-sidebar { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border-right: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 4px 0 24px rgba(0, 0, 0, 0.02); }

        /* Stats Card (Prism) */
        .stat-card {
            position: relative; overflow: hidden;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 1.75rem;
            padding: 1.5rem;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .stat-card::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.4) 0%, transparent 100%);
            opacity: 0; transition: opacity 0.3s; pointer-events: none;
        }
        .stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            background: rgba(255, 255, 255, 0.9);
            border-color: #a5b4fc;
            box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.15);
        }

        /* Floating Table Layout */
        .floating-table-container { border-spacing: 0 1rem; border-collapse: separate; width: 100%; }
        .floating-row {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }
        .floating-row td {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.8);
        }
        .floating-row td:first-child {
            border-left: 1px solid rgba(255, 255, 255, 0.8);
            border-top-left-radius: 1.25rem;
            border-bottom-left-radius: 1.25rem;
        }
        .floating-row td:last-child {
            border-right: 1px solid rgba(255, 255, 255, 0.8);
            border-top-right-radius: 1.25rem;
            border-bottom-right-radius: 1.25rem;
        }
        .floating-row:hover {
            transform: scale(1.01);
            background: #ffffff;
            box-shadow: 0 20px 40px -12px rgba(99, 102, 241, 0.15);
            position: relative; z-index: 10;
        }
        .floating-row:hover td { border-color: #a5b4fc; }

        /* Avatar Status Ring */
        .avatar-ring { padding: 3px; border-radius: 50%; display: inline-block; }
        .ring-approved { background: linear-gradient(135deg, #34d399, #10b981); }
        .ring-pending { background: linear-gradient(135deg, #fbbf24, #f59e0b); }
        .ring-cancelled { background: linear-gradient(135deg, #f87171, #ef4444); }

        /* Neon Badges */
        .badge-neon {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.35rem 0.85rem; border-radius: 99px;
            font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
            backdrop-filter: blur(4px);
        }
        .badge-approved { background: rgba(209, 250, 229, 0.8); color: #059669; border: 1px solid #6ee7b7; box-shadow: 0 0 10px rgba(16, 185, 129, 0.1); }
        .badge-pending { background: rgba(254, 243, 199, 0.8); color: #d97706; border: 1px solid #fcd34d; box-shadow: 0 0 10px rgba(245, 158, 11, 0.1); }
        .badge-cancelled { background: rgba(254, 226, 226, 0.8); color: #dc2626; border: 1px solid #fca5a5; box-shadow: 0 0 10px rgba(239, 68, 68, 0.1); }

        /* Action Buttons */
        .btn-action {
            width: 38px; height: 38px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s; background: rgba(255,255,255,0.8);
            border: 1px solid #e2e8f0; color: #64748b;
        }
        .btn-action:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .btn-approve:hover { color: #10b981; border-color: #86efac; background: #ecfdf5; }
        .btn-reject:hover { color: #ef4444; border-color: #fca5a5; background: #fef2f2; }

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
<body class="antialiased text-slate-800 flex min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- BACKGROUND --}}
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
            <a href="{{ route('admin.bookings.index') }}" class="nav-link active"><i class="ph-duotone ph-ticket text-xl"></i> Kelola Booking</a>

            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-2">System</p>
            <a href="{{ route('admin.categories.index') }}" class="nav-link"><i class="ph-duotone ph-tag text-xl"></i> Kategori</a>
            <a href="{{ route('admin.notifications.index') }}" class="nav-link"><i class="ph-duotone ph-bell text-xl"></i> Notifikasi</a>
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
    @php
        $totalBookings = $bookings->count();
        $approved      = $bookings->where('status', 'approved')->count();
        $pending       = $bookings->where('status', 'pending')->count();
        $cancelled     = $bookings->where('status', 'cancelled')->count();
    @endphp

    <div class="lg:ml-72 flex-1 flex flex-col min-h-screen relative z-10 transition-all">

        {{-- Mobile Header --}}
        <header class="h-20 flex items-center justify-between px-6 lg:px-10 lg:hidden bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors"><i class="ph-bold ph-list text-2xl"></i></button>
                <span class="font-bold text-lg text-slate-900">Booking Manager</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="p-2 rounded-lg text-rose-500 hover:bg-rose-50"><i class="ph-bold ph-sign-out text-2xl"></i></button></form>
        </header>

        <div class="p-6 lg:p-12 space-y-12">

            {{-- A. Header & Stats --}}
            <div class="space-y-8" data-aos="fade-down">
                <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900 mb-2">
                            Booking <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Command</span>
                        </h1>
                        <p class="text-slate-500 text-lg">Pusat kendali transaksi dan persetujuan tiket.</p>
                    </div>

                    {{-- Search Box --}}
                    <div class="relative w-full md:w-80 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <i class="ph-bold ph-magnifying-glass text-xl"></i>
                        </div>
                        <input type="text" placeholder="Cari pesanan..." class="w-full bg-white/80 border border-white rounded-2xl pl-12 pr-4 py-3.5 text-sm font-medium focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all shadow-lg shadow-indigo-500/5 placeholder:text-slate-400 backdrop-blur-sm">
                    </div>
                </div>

                {{-- Holographic Stats Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="stat-card group" data-tilt data-tilt-max="5" data-tilt-speed="400">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-indigo-50 rounded-2xl text-indigo-600 shadow-inner"><i class="ph-duotone ph-stack text-2xl"></i></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total</span>
                        </div>
                        <h3 class="text-4xl font-mono-num font-bold text-slate-900 mb-1">{{ $totalBookings }}</h3>
                        <p class="text-xs text-slate-500 font-medium">Transaksi Masuk</p>
                    </div>

                    <div class="stat-card group" data-tilt data-tilt-max="5" data-tilt-speed="400">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600 shadow-inner"><i class="ph-duotone ph-check-circle text-2xl"></i></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Success</span>
                        </div>
                        <h3 class="text-4xl font-mono-num font-bold text-emerald-600 mb-1">{{ $approved }}</h3>
                        <p class="text-xs text-slate-500 font-medium">Booking Disetujui</p>
                    </div>

                    <div class="stat-card group" data-tilt data-tilt-max="5" data-tilt-speed="400">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-amber-50 rounded-2xl text-amber-600 shadow-inner"><i class="ph-duotone ph-hourglass text-2xl"></i></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pending</span>
                        </div>
                        <h3 class="text-4xl font-mono-num font-bold text-amber-600 mb-1">{{ $pending }}</h3>
                        <p class="text-xs text-slate-500 font-medium">Menunggu Aksi</p>
                    </div>

                    <div class="stat-card group" data-tilt data-tilt-max="5" data-tilt-speed="400">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-rose-50 rounded-2xl text-rose-600 shadow-inner"><i class="ph-duotone ph-x-circle text-2xl"></i></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Void</span>
                        </div>
                        <h3 class="text-4xl font-mono-num font-bold text-rose-600 mb-1">{{ $cancelled }}</h3>
                        <p class="text-xs text-slate-500 font-medium">Dibatalkan</p>
                    </div>
                </div>
            </div>

            {{-- B. Levitating Data Decks (Table Replacement) --}}
            <div class="space-y-6" data-aos="fade-up" data-aos-delay="100">

                {{-- Table Headers --}}
                <div class="hidden lg:grid grid-cols-12 gap-4 px-6 pb-2 text-xs font-bold text-slate-400 uppercase tracking-widest opacity-70">
                    <div class="col-span-1">ID</div>
                    <div class="col-span-3">User Profile</div>
                    <div class="col-span-3">Event Detail</div>
                    <div class="col-span-2 text-center">Ticket</div>
                    <div class="col-span-2 text-center">Status</div>
                    <div class="col-span-1 text-center">Action</div>
                </div>

                @if ($bookings->isEmpty())
                    <div class="p-20 text-center rounded-[2.5rem] border-2 border-dashed border-slate-300 bg-white/40">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400">
                            <i class="ph-duotone ph-clipboard-text text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Belum Ada Pesanan</h3>
                        <p class="text-slate-500">Saat ini belum ada data booking yang masuk.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($bookings as $booking)
                            @php
                                $ringColor = match($booking->status) {
                                    'approved' => 'ring-approved',
                                    'pending' => 'ring-pending',
                                    'cancelled' => 'ring-cancelled',
                                    default => 'bg-slate-200'
                                };
                                $statusBadge = match($booking->status) {
                                    'approved' => 'badge-approved',
                                    'pending' => 'badge-pending',
                                    'cancelled' => 'badge-cancelled',
                                    default => 'bg-slate-100 text-slate-500'
                                };
                            @endphp

                            <div class="floating-row lg:grid lg:grid-cols-12 gap-4 items-center p-4 rounded-[1.25rem] relative group border border-white/50">

                                {{-- Mobile Label: ID --}}
                                <div class="lg:col-span-1 flex justify-between lg:block mb-2 lg:mb-0">
                                    <span class="lg:hidden text-xs font-bold text-slate-400">ID</span>
                                    <span class="font-mono-num font-bold text-slate-400">#{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>

                                {{-- User Profile --}}
                                <div class="lg:col-span-3 flex items-center gap-4 mb-4 lg:mb-0">
                                    <div class="avatar-ring {{ $ringColor }}">
                                        <div class="w-10 h-10 rounded-full bg-white p-0.5">
                                            <img src="https://ui-avatars.com/api/?name={{ $booking->user->name ?? 'User' }}&background=f1f5f9&color=475569&bold=true" class="w-full h-full rounded-full object-cover">
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm line-clamp-1">{{ $booking->user->name ?? '-' }}</h4>
                                        <p class="text-xs text-slate-500 font-medium">{{ $booking->user->email ?? '' }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $booking->created_at?->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>

                                {{-- Event Detail --}}
                                <div class="lg:col-span-3 mb-4 lg:mb-0">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold border border-slate-200 shadow-sm shrink-0">
                                            {{ substr($booking->event->name ?? 'E', 0, 1) }}
                                        </div>
                                        <div>
                                            <h5 class="font-bold text-slate-900 text-sm line-clamp-1">{{ $booking->event->name ?? '-' }}</h5>
                                            <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                                <i class="ph-fill ph-map-pin text-indigo-400"></i> {{ $booking->event->location ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Ticket Info --}}
                                <div class="lg:col-span-2 text-left lg:text-center mb-4 lg:mb-0 bg-slate-50 lg:bg-transparent p-3 lg:p-0 rounded-xl lg:rounded-none">
                                    <div class="flex justify-between lg:block">
                                        <span class="lg:hidden text-xs font-bold text-slate-400">Ticket</span>
                                        <div>
                                            <span class="px-2 py-1 rounded-md bg-white border border-slate-200 text-xs font-bold text-slate-700 shadow-sm">
                                                {{ $booking->ticketType->name ?? '-' }}
                                            </span>
                                            <p class="text-xs text-slate-500 mt-1 font-mono-num">Qty: <span class="font-bold text-slate-900">{{ $booking->quantity }}</span></p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="lg:col-span-2 flex justify-end lg:justify-center mb-4 lg:mb-0">
                                    <span class="badge-neon {{ $statusBadge }}">
                                        @if($booking->status === 'pending') <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> @endif
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>

                                {{-- Actions --}}
                                <div class="lg:col-span-1 flex justify-end lg:justify-center gap-2 opacity-100 lg:opacity-60 group-hover:opacity-100 transition-opacity">
                                    @if ($booking->status === 'pending')
                                        <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-action btn-approve" title="Approve Booking">
                                                <i class="ph-bold ph-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="js-reject-form">
                                            @csrf
                                            <button type="submit" class="btn-action btn-reject" title="Reject Booking">
                                                <i class="ph-bold ph-x"></i>
                                            </button>
                                        </form>
                                    @elseif ($booking->status === 'approved')
                                        <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="js-reject-form">
                                            @csrf
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-rose-500 hover:bg-rose-50 transition-colors" title="Batalkan">
                                                <i class="ph-bold ph-prohibit"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-300"><i class="ph-bold ph-minus"></i></span>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Pagination --}}
                @if(method_exists($bookings, 'hasPages') && $bookings->hasPages())
                    <div class="pt-8 flex justify-center">
                        <div class="bg-white/60 backdrop-blur px-6 py-2 rounded-2xl shadow-sm border border-slate-100">
                            {{ $bookings->links() }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- =======================================================================
         5. SCRIPTS
    ======================================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({ duration: 800, once: true, offset: 50 });

            // 3D Tilt Init
            VanillaTilt.init(document.querySelectorAll(".stat-card"), {
                max: 10, speed: 400, glare: true, "max-glare": 0.2, scale: 1.02
            });

            // SweetAlert2 for Reject/Cancel
            const rejectForms = document.querySelectorAll('.js-reject-form');
            rejectForms.forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Pembatalan',
                        text: "Tindakan ini akan membatalkan booking secara permanen.",
                        icon: 'warning',
                        background: '#ffffff',
                        color: '#1e293b',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: 'Kembali',
                        customClass: { popup: 'rounded-2xl shadow-xl border border-slate-100' }
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
</body>
</html>
