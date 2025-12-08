<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Manajemen Booking Organizer - EventTicket">

    <title>Booking Management - {{ config('app.name', 'EventTicket') }}</title>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- =======================================================================
         2. CUSTOM STYLES (Ethereal Light Theme)
    ======================================================================= --}}
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC; /* Slate 50 */
            color: #1e293b; /* Slate 800 for text */
            overflow-x: hidden;
        }
        h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* --- Aurora Background Light --- */
        .aurora-bg { position: fixed; inset: 0; z-index: -2; background: #F8FAFC; overflow: hidden; }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.5;
            mix-blend-mode: multiply; animation: float 25s infinite ease-in-out;
        }
        .orb-1 { top: -20%; left: -10%; width: 50vw; height: 50vw; background: #c7d2fe; } /* Pastel Indigo */
        .orb-2 { bottom: -20%; right: -10%; width: 60vw; height: 60vw; background: #e9d5ff; animation-delay: -5s; } /* Pastel Purple */
        .orb-3 { top: 30%; left: 30%; width: 40vw; height: 40vw; background: #bae6fd; opacity: 0.3; animation-delay: -10s; } /* Pastel Sky */

        .noise-texture {
             position: absolute; inset: 0; z-index: -1; opacity: 0.4; pointer-events: none;
             background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.1'/%3E%3C/svg%3E");
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        /* --- Glass Sidebar (Light Mode) --- */
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* --- Glass Panel & Table --- */
        .glass-panel {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
        }

        /* --- 3D Status Cubes --- */
        .stat-cube {
            position: relative; overflow: hidden;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 1.5rem; padding: 1.5rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .stat-cube::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.8), transparent);
            opacity: 0; transition: opacity 0.3s;
        }
        .stat-cube:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.8);
            border-color: rgba(0, 0, 0, 0.1);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }
        .stat-cube:hover::before { opacity: 1; }

        /* --- Table Styling --- */
        .glass-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .glass-table th {
            text-align: left; padding: 1.25rem 1.5rem;
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
            color: #64748b; border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .glass-table td {
            padding: 1.25rem 1.5rem; vertical-align: middle;
            border-bottom: 1px solid rgba(0,0,0,0.03);
            transition: background 0.2s;
        }
        .glass-table tr:hover td { background: rgba(0,0,0,0.02); }
        .glass-table tr:last-child td { border-bottom: none; }

        /* --- Status Pills --- */
        .status-pill {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.35rem 0.85rem; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
        }
        .status-approved { background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.2); }
        .status-pending { background: rgba(245, 158, 11, 0.1); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.2); }
        .status-cancelled { background: rgba(239, 68, 68, 0.1); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.2); }

        /* --- Buttons & Links --- */
        .btn-action {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s; background: rgba(0,0,0,0.05); color: #64748b;
        }
        .btn-action:hover { background: rgba(0,0,0,0.1); color: #1e293b; transform: scale(1.1); }

        .nav-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.75rem 1rem; border-radius: 0.75rem;
            color: #64748b; font-weight: 500; transition: all 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            background: #eef2ff; color: #4338ca;
            box-shadow: 0 4px 6px -2px rgba(99, 102, 241, 0.05);
        }
        .nav-link.active { font-weight: 700; border: 1px solid #e0e7ff; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="antialiased text-slate-800 flex min-h-screen" x-data="{ sidebarOpen: false }">

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

        {{-- Logo --}}
        <div class="h-24 flex items-center px-8 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/20">
                    EO
                </div>
                <div class="flex flex-col">
                    <span class="font-display font-bold text-xl tracking-tight text-slate-900">Event<span class="text-indigo-600">Ticket</span></span>
                    <span class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Organizer Panel</span>
                </div>
            </div>
        </div>

        {{-- Menu --}}
        <div class="flex-1 px-4 py-8 space-y-1.5 overflow-y-auto">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Main Menu</p>

            <a href="{{ route('organizer.dashboard') }}" class="nav-link">
                <i class="ph-duotone ph-squares-four text-xl"></i> Dashboard
            </a>
            <a href="{{ route('organizer.events.index') }}" class="nav-link">
                <i class="ph-duotone ph-calendar-plus text-xl"></i> Event Saya
            </a>
            <a href="{{ route('organizer.bookings.index') }}" class="nav-link active">
                <i class="ph-duotone ph-ticket text-xl"></i> Manajemen Booking
            </a>
            <a href="{{ route('organizer.notifications.index') }}" class="nav-link">
                <i class="ph-duotone ph-bell text-xl"></i> Notifikasi
            </a>
            <a href="{{ route('organizer.analytics.index') }}" class="nav-link">
                <i class="ph-duotone ph-chart-line-up text-xl"></i> Analytics
            </a>
        </div>

        {{-- Profile Footer & LOGOUT (DITAMBAHKAN DI SINI) --}}
        <div class="p-4 border-t border-slate-200/50">
            <div class="bg-white/60 border border-slate-200 p-3 rounded-2xl flex items-center gap-3 cursor-pointer hover:bg-white transition-all shadow-sm relative"
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
                            <i class="ph-bold ph-sign-out"></i> Keluar
                        </button>
                    </form>
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

        {{-- Mobile Header (DITAMBAHKAN TOMBOL LOGOUT) --}}
        <header class="h-20 flex items-center justify-between px-6 lg:px-10 lg:hidden bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
                    <i class="ph-bold ph-list text-2xl"></i>
                </button>
                <span class="font-bold text-lg text-slate-900">Booking Manager</span>
            </div>

            {{-- Mobile Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 rounded-lg text-rose-500 hover:bg-rose-50">
                    <i class="ph-bold ph-sign-out text-2xl"></i>
                </button>
            </form>
        </header>

        <div class="p-6 lg:p-10 space-y-10">

            {{-- A. Header & 3D Status Cubes --}}
            <div class="space-y-8" data-aos="fade-down">
                <div>
                    <h1 class="text-4xl font-display font-extrabold text-slate-900 mb-2">Manajemen Booking</h1>
                    <p class="text-slate-500 text-lg">Kelola pesanan masuk dan setujui tiket peserta event Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Total Cube --}}
                    <div class="stat-cube group" data-tilt data-tilt-max="5" data-tilt-speed="400">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 rounded-lg bg-indigo-100 text-indigo-600"><i class="ph-duotone ph-stack text-xl"></i></div>
                        </div>
                        <h3 class="text-3xl font-mono-num font-bold text-slate-900 mb-1">{{ $totalBookings }}</h3>
                        <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Total Masuk</p>
                    </div>

                    {{-- Approved Cube --}}
                    <div class="stat-cube group" data-tilt data-tilt-max="5" data-tilt-speed="400">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 rounded-lg bg-emerald-100 text-emerald-600"><i class="ph-duotone ph-check-circle text-xl"></i></div>
                        </div>
                        <h3 class="text-3xl font-mono-num font-bold text-emerald-600 mb-1">{{ $approved }}</h3>
                        <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Disetujui</p>
                    </div>

                    {{-- Pending Cube --}}
                    <div class="stat-cube group" data-tilt data-tilt-max="5" data-tilt-speed="400">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 rounded-lg bg-amber-100 text-amber-600"><i class="ph-duotone ph-hourglass text-xl"></i></div>
                        </div>
                        <h3 class="text-3xl font-mono-num font-bold text-amber-600 mb-1">{{ $pending }}</h3>
                        <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Menunggu</p>
                    </div>

                    {{-- Cancelled Cube --}}
                    <div class="stat-cube group" data-tilt data-tilt-max="5" data-tilt-speed="400">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 rounded-lg bg-rose-100 text-rose-600"><i class="ph-duotone ph-x-circle text-xl"></i></div>
                        </div>
                        <h3 class="text-3xl font-mono-num font-bold text-rose-600 mb-1">{{ $cancelled }}</h3>
                        <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Dibatalkan</p>
                    </div>
                </div>
            </div>

            {{-- B. Data Table Panel --}}
            <div class="glass-panel rounded-[2rem] overflow-hidden flex flex-col" data-aos="fade-up" data-aos-delay="100">

                {{-- Toolbar --}}
                <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h3 class="font-bold text-lg text-slate-900">Daftar Pesanan</h3>

                    {{-- Search & Filter --}}
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="relative flex-1 md:w-64">
                            <input type="text" placeholder="Cari nama user atau event..." class="w-full bg-white/50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-900 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder:text-slate-400 focus:bg-white">
                            <i class="ph-bold ph-magnifying-glass absolute left-3 top-3 text-slate-400"></i>
                        </div>
                        <button class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition-colors shadow-sm">
                            <i class="ph-bold ph-funnel"></i>
                        </button>
                    </div>
                </div>

                {{-- Table Content --}}
                @if ($bookings->isEmpty())
                    <div class="p-16 text-center">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200">
                            <i class="ph-duotone ph-clipboard-text text-4xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Data</h3>
                        <p class="text-sm text-slate-500">Belum ada user yang melakukan booking untuk event Anda.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="glass-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Event</th>
                                    <th>User</th>
                                    <th>Tiket</th>
                                    <th class="text-right">Qty</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $booking)
                                    <tr class="group">
                                        <td class="text-slate-500 font-mono-num">{{ $loop->iteration }}</td>

                                        <td>
                                            <div class="text-sm font-medium text-slate-900">{{ $booking->created_at?->format('d M Y') }}</div>
                                            <div class="text-xs text-slate-500">{{ $booking->created_at?->format('H:i') }}</div>
                                        </td>

                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                                    {{ substr($booking->event->name ?? 'E', 0, 1) }}
                                                </div>
                                                <div class="max-w-[150px] truncate">
                                                    <div class="text-sm font-bold text-slate-900 truncate">{{ $booking->event->name ?? '-' }}</div>
                                                    <div class="text-xs text-slate-500 truncate">{{ $booking->event->location ?? '' }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="text-sm text-slate-700">{{ $booking->user->name ?? '-' }}</div>
                                            <div class="text-xs text-slate-500">{{ $booking->user->email ?? '' }}</div>
                                        </td>

                                        <td>
                                            <span class="px-2 py-1 rounded-md bg-slate-100 border border-slate-200 text-xs text-slate-600 font-medium">
                                                {{ $booking->ticketType->name ?? '-' }}
                                            </span>
                                        </td>

                                        <td class="text-right font-mono-num font-bold text-slate-900">
                                            {{ $booking->quantity }}
                                        </td>

                                        <td>
                                            @php
                                                $statusClass = match($booking->status) {
                                                    'approved' => 'status-approved',
                                                    'pending' => 'status-pending',
                                                    'cancelled' => 'status-cancelled',
                                                    default => 'bg-slate-100 text-slate-500 border-slate-200',
                                                };
                                            @endphp
                                            <span class="status-pill {{ $statusClass }}">
                                                @if($booking->status === 'pending') <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> @endif
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            @if ($booking->status === 'pending')
                                                <div class="flex items-center justify-center gap-2" x-data="{ loading: false }">
                                                    <form action="{{ route('organizer.bookings.approve', $booking) }}" method="POST" @submit="loading = true">
                                                        @csrf
                                                        <button type="submit" class="btn-action hover:bg-emerald-100 hover:text-emerald-600 text-slate-400" title="Approve" :disabled="loading">
                                                            <i class="ph-bold ph-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('organizer.bookings.cancel', $booking) }}" method="POST" class="js-reject-form" data-id="{{ $booking->id }}">
                                                        @csrf
                                                        <button type="submit" class="btn-action hover:bg-rose-100 hover:text-rose-600 text-slate-400" title="Reject">
                                                            <i class="ph-bold ph-x"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif ($booking->status === 'approved')
                                                <form action="{{ route('organizer.bookings.cancel', $booking) }}" method="POST" class="js-reject-form" data-id="{{ $booking->id }}">
                                                    @csrf
                                                    <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 hover:underline font-medium">Batalkan</button>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-400 font-medium">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Pagination --}}
                @if(method_exists($bookings, 'hasPages') && $bookings->hasPages())
                    <div class="p-4 border-t border-slate-100 flex justify-center">
                        {{ $bookings->links() }}
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

            // Vanilla Tilt Init
            VanillaTilt.init(document.querySelectorAll(".stat-cube"), {
                max: 15, speed: 400, glare: true, "max-glare": 0.2, scale: 1.02
            });

            // SweetAlert2 for Reject/Cancel
            const rejectForms = document.querySelectorAll('.js-reject-form');
            rejectForms.forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Pembatalan',
                        text: "Apakah Anda yakin ingin membatalkan/menolak booking ini?",
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
