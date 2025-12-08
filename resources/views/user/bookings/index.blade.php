<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Dompet Tiket Digital - EventTicket">

    <title>Dompet Tiket - {{ config('app.name', 'EventTicket') }}</title>

    {{-- =======================================================================
         1. FONTS & ASSETS
    ======================================================================= --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- =======================================================================
         2. CUSTOM STYLES (Ultra Premium)
    ======================================================================= --}}
    <style>
        /* Base Setup */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* --- 2.1 Aurora Background (Alive Effect) --- */
        .aurora-bg {
            position: fixed; inset: 0; z-index: -1; background: #fff; overflow: hidden;
        }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.5;
            animation: float 20s infinite ease-in-out;
            mix-blend-mode: multiply;
        }
        .orb-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #a5b4fc; animation-delay: 0s; }
        .orb-2 { bottom: -20%; right: -20%; width: 60vw; height: 60vw; background: #c4b5fd; animation-delay: -5s; }
        .orb-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: #fca5a5; opacity: 0.3; animation-delay: -10s; }

        .noise-texture {
            position: absolute; inset: 0; opacity: 0.4;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.1'/%3E%3C/svg%3E");
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            33% { transform: translate(30px, -50px) scale(1.1) rotate(10deg); }
            66% { transform: translate(-20px, 20px) scale(0.9) rotate(-5deg); }
            100% { transform: translate(0, 0) scale(1) rotate(0deg); }
        }

        /* --- 2.2 Glassmorphism Navbar --- */
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        }

        /* --- 2.3 Holographic Ticket Card --- */
        .ticket-wrapper {
            perspective: 1000px;
            transition: transform 0.3s ease;
        }
        .ticket-card {
            position: relative;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 1);
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
        }
        .ticket-card:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 20px 50px -10px rgba(79, 70, 229, 0.15);
            border-color: #818cf8;
        }

        /* Holographic Shine Effect */
        .ticket-card::before {
            content: "";
            position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.6), transparent);
            transform: skewX(-25deg);
            transition: 0.5s;
            pointer-events: none;
            z-index: 50;
        }
        .ticket-card:hover::before { left: 150%; transition: 0.7s; }

        /* Perforation & Notches */
        .notch {
            position: absolute; width: 24px; height: 24px;
            background-color: #F8FAFC; border-radius: 50%; z-index: 20;
            box-shadow: inset 1px 1px 3px rgba(0,0,0,0.05);
        }
        /* Desktop Notches */
        @media (min-width: 768px) {
            .ticket-card { flex-direction: row; min-height: 240px; }
            .notch-t { top: -12px; left: 30%; transform: translateX(-50%); }
            .notch-b { bottom: -12px; left: 30%; transform: translateX(-50%); }
            .perforation {
                position: absolute; top: 12px; bottom: 12px; left: 30%;
                border-left: 2px dashed #cbd5e1; z-index: 10;
            }
        }
        /* Mobile Notches */
        @media (max-width: 767px) {
            .notch-l { top: 35%; left: -12px; transform: translateY(-50%); }
            .notch-r { top: 35%; right: -12px; transform: translateY(-50%); }
            .perforation {
                position: absolute; left: 12px; right: 12px; top: 35%;
                border-top: 2px dashed #cbd5e1; z-index: 10;
            }
        }

        /* --- 2.4 Status Indicators --- */
        .status-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px; border-radius: 99px;
            font-size: 0.7rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase;
        }
        .status-approved { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .status-pending { background: #fef9c3; color: #a16207; border: 1px solid #fde047; }
        .status-cancelled { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

        .pulse-dot {
            width: 8px; height: 8px; border-radius: 50%;
            animation: pulse-animation 2s infinite;
        }
        @keyframes pulse-animation {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* --- 2.5 QR Scanning Animation --- */
        .qr-scanner-line {
            position: absolute; width: 100%; height: 2px;
            background: #ef4444;
            box-shadow: 0 0 4px #ef4444;
            animation: scan 2s linear infinite alternate;
        }
        @keyframes scan { 0% { top: 0; } 100% { top: 100%; } }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col relative" x-data="ticketWallet()">

    {{-- ALIVE BACKGROUND --}}
    <div class="aurora-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="noise-texture"></div>
    </div>

    {{-- =======================================================================
         3. NAVBAR (Minimal & Sticky)
    ======================================================================= --}}
    <nav class="fixed top-0 w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                {{-- Logo & Back --}}
                <div class="flex items-center gap-6">
                    <a href="{{ route('user.dashboard') }}" class="group flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/50 transition-all">
                        <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-arrow-left"></i>
                        </div>
                        <span class="font-bold text-slate-700 group-hover:text-slate-900 text-sm hidden sm:inline">Dashboard</span>
                    </a>
                </div>

                {{-- Page Title --}}
                <div class="absolute left-1/2 transform -translate-x-1/2 flex items-center gap-2">
                    <i class="ph-duotone ph-ticket text-2xl text-indigo-600"></i>
                    <h1 class="font-heading font-bold text-xl text-slate-900 tracking-tight">Dompet Tiket</h1>
                </div>

                {{-- User --}}
                <div class="flex items-center gap-3">
                    <div class="hidden md:flex flex-col items-end mr-2">
                        <span class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Member</span>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 p-[2px] shadow-md">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=fff&color=4f46e5" class="rounded-full w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- =======================================================================
         4. MAIN CONTENT
    ======================================================================= --}}
    <main class="flex-grow pt-32 pb-20 px-4 sm:px-6 max-w-6xl mx-auto w-full relative z-10">

        {{-- FLASH MESSAGE --}}
        @if (session('status'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="fixed top-24 right-4 z-50 flex items-center gap-3 px-6 py-4 bg-white/90 backdrop-blur-xl border border-emerald-200 rounded-2xl shadow-xl animate__animated animate__slideInRight">
                <div class="bg-emerald-100 text-emerald-600 p-2 rounded-full">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">Berhasil!</h4>
                    <p class="text-xs text-slate-500">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        {{-- HEADER AREA: Stats & Search --}}
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12" data-aos="fade-down">
            <div>
                <h2 class="text-4xl font-heading font-extrabold text-slate-900 mb-2">Tiket Saya</h2>
                <p class="text-slate-500 max-w-md text-sm leading-relaxed">
                    Akses semua tiket event Anda di sini. Pastikan status <span class="font-bold text-emerald-600">Approved</span> untuk mengunduh tiket.
                </p>
            </div>

            {{-- Search Input (Client Side Filtering) --}}
            <div class="w-full md:w-auto relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <i class="ph-bold ph-magnifying-glass"></i>
                </div>
                <input type="text" x-model="search" placeholder="Cari event..."
                       class="w-full md:w-64 pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm group-hover:shadow-md">
            </div>
        </div>

        {{-- TICKET LIST --}}
        <div class="space-y-8">
            @forelse ($bookings as $booking)
                {{-- Item Wrapper for Search Filtering --}}
                <div class="ticket-wrapper w-full"
                     x-show="matchesSearch('{{ strtolower($booking->event->name) }}')"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     data-aos="fade-up"
                     data-aos-delay="{{ $loop->index * 100 }}">

                    <div class="ticket-card flex flex-col md:flex-row">

                        {{-- DECORATION: Notches & Perforation --}}
                        <div class="notch notch-t hidden md:block"></div>
                        <div class="notch notch-b hidden md:block"></div>
                        <div class="notch notch-l md:hidden"></div>
                        <div class="notch notch-r md:hidden"></div>
                        <div class="perforation"></div>

                        {{-- LEFT SIDE: VISUAL (30%) --}}
                        <div class="md:w-[30%] h-40 md:h-auto relative overflow-hidden bg-slate-900 group-hover:shadow-inner transition-all">
                            <img src="{{ $booking->event->image ? asset($booking->event->image) : 'https://source.unsplash.com/random/400x600?concert&sig='.$booking->event->id }}"
                                 class="w-full h-full object-cover opacity-80 group-hover:scale-110 transition-transform duration-1000 group-hover:opacity-100"
                                 alt="Event Poster">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-indigo-900/40 to-transparent"></div>

                            {{-- Date Overlay --}}
                            <div class="absolute bottom-0 left-0 w-full p-4 text-white">
                                <p class="text-xs font-bold uppercase tracking-widest text-indigo-200 mb-1">
                                    {{ \Carbon\Carbon::parse($booking->event->date)->format('F Y') }}
                                </p>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl font-heading font-black">{{ \Carbon\Carbon::parse($booking->event->date)->format('d') }}</span>
                                    <span class="text-sm font-medium opacity-80">{{ \Carbon\Carbon::parse($booking->event->date)->format('l') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT SIDE: DETAILS (70%) --}}
                        <div class="flex-1 p-6 md:p-8 flex flex-col justify-between bg-white/50">

                            {{-- Top: Header & Status --}}
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
                                <div>
                                    @php
                                        $statusClass = match($booking->status) {
                                            'approved' => 'status-approved',
                                            'pending' => 'status-pending',
                                            'cancelled' => 'status-cancelled',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                        $statusLabel = match($booking->status) {
                                            'approved' => 'Tiket Aktif',
                                            'pending' => 'Menunggu',
                                            'cancelled' => 'Dibatalkan',
                                            default => ucfirst($booking->status),
                                        };
                                        $pulseColor = $booking->status === 'approved' ? 'bg-emerald-500' : ($booking->status === 'pending' ? 'bg-amber-500' : 'hidden');
                                    @endphp
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="status-pill {{ $statusClass }}">
                                            @if($booking->status !== 'cancelled')
                                                <span class="pulse-dot {{ $pulseColor }}"></span>
                                            @endif
                                            {{ $statusLabel }}
                                        </span>
                                        <span class="text-xs font-mono-num text-slate-400 font-medium">#INV-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                                    </div>

                                    <h3 class="text-2xl font-display font-extrabold text-slate-900 line-clamp-1 group-hover:text-indigo-600 transition-colors">
                                        {{ $booking->event->name }}
                                    </h3>
                                    <p class="text-sm text-slate-500 flex items-center gap-1.5 mt-1">
                                        <i class="ph-fill ph-map-pin text-indigo-500"></i>
                                        {{ $booking->event->location }} • {{ $booking->event->time }}
                                    </p>
                                </div>

                                {{-- Barcode Aesthetic (Visual Only) --}}
                                <div class="hidden lg:block opacity-40">
                                    <div class="h-10 w-32 bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAAABCAYAAABu7w/3AAAABmJLR0QA/wD/AP+gvaeTAAAAK0lEQVQImWNgQAaM5/+D2Qz//4PZDP//g9kM//+D2Qz//4PZDP//g9kMAwAAAP//y7g8bQAAAABJRU5ErkJggg==')] bg-repeat-x bg-cover"></div>
                                </div>
                            </div>

                            {{-- Bottom: Info & Actions --}}
                            <div class="flex flex-col md:flex-row items-end justify-between gap-6 pt-4 border-t border-slate-200/60 mt-auto">

                                {{-- Ticket Info --}}
                                <div class="flex gap-6 w-full md:w-auto">
                                    <div>
                                        <p class="text-[10px] uppercase text-slate-400 font-bold tracking-wider">Tiket</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $booking->ticketType->name ?? 'Regular' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-slate-400 font-bold tracking-wider">Jumlah</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $booking->quantity }} Pax</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-slate-400 font-bold tracking-wider">Total</p>
                                        <p class="text-sm font-bold text-indigo-600 font-mono-num">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                                    {{-- Cancel --}}
                                    @if($booking->status !== 'cancelled' && $booking->status !== 'approved')
                                        <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST" class="js-cancel-form" data-name="{{ $booking->event->name }}">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 rounded-lg text-xs font-bold text-rose-500 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all flex items-center gap-2">
                                                <i class="ph-bold ph-x"></i> Batal
                                            </button>
                                        </form>
                                    @endif

                                    {{-- QR / Download --}}
                                    @if($booking->status === 'approved')
                                        <button @click="showQr('{{ $booking->id }}', '{{ $booking->event->name }}')" class="px-4 py-2 rounded-lg text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all flex items-center gap-2 shadow-sm">
                                            <i class="ph-bold ph-qr-code"></i> Lihat QR
                                        </button>

                                        <a href="{{ route('user.bookings.ticket.download', ['booking' => $booking->id]) }}"
                                           class="px-5 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold shadow-lg shadow-slate-900/20 hover:bg-indigo-600 hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                                            <i class="ph-bold ph-download-simple"></i> Download
                                        </a>
                                    @else
                                        <span class="px-4 py-2 rounded-lg bg-slate-100 text-slate-400 text-xs font-bold border border-slate-200 cursor-not-allowed flex items-center gap-2">
                                            <i class="ph-bold ph-lock-key"></i> Terkunci
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- EMPTY STATE --}}
                <div class="rounded-[2.5rem] bg-white/80 backdrop-blur-xl border-2 border-dashed border-slate-300 p-20 text-center shadow-sm" data-aos="zoom-in">
                    <div class="relative w-24 h-24 mx-auto mb-6">
                        <div class="absolute inset-0 bg-indigo-100 rounded-full animate-ping opacity-75"></div>
                        <div class="relative w-24 h-24 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center text-4xl shadow-inner border border-indigo-100">
                            <i class="ph-duotone ph-ticket"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-heading font-extrabold text-slate-900 mb-3">Dompet Tiket Kosong</h3>
                    <p class="text-slate-500 max-w-md mx-auto mb-8 text-sm leading-relaxed">
                        Anda belum memiliki riwayat pembelian tiket. Mulailah petualangan Anda dengan menjelajahi event-event menarik di sekitar Anda!
                    </p>
                    <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-slate-900 text-white rounded-full font-bold text-sm shadow-xl hover:bg-indigo-600 hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-1">
                        <i class="ph-bold ph-magnifying-glass"></i> Jelajah Event
                    </a>
                </div>
            @endforelse
        </div>

        {{-- QR CODE MODAL (Hidden by default) --}}
        <div x-show="qrModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
             style="display: none;">

            <div @click.outside="closeQr()" class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full relative overflow-hidden text-center"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <button @click="closeQr()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-slate-100 transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>

                <h3 class="text-xl font-bold text-slate-900 mb-1" x-text="currentEventName">Event Name</h3>
                <p class="text-xs text-slate-500 mb-6 font-mono-num" x-text="'#INV-' + currentBookingId.toString().padStart(6, '0')">#INV-000000</p>

                <div class="relative w-48 h-48 mx-auto bg-slate-50 rounded-xl p-4 border border-slate-200 mb-6 group">
                    {{-- QR Image --}}
                    <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=BookingID-' + currentBookingId"
                         class="w-full h-full object-contain mix-blend-multiply" alt="QR Code">

                    {{-- Scanning Effect --}}
                    <div class="absolute inset-0 overflow-hidden rounded-xl pointer-events-none">
                         <div class="qr-scanner-line"></div>
                    </div>
                </div>

                <p class="text-xs text-slate-400">Tunjukkan QR Code ini kepada petugas saat masuk ke venue.</p>
            </div>
        </div>

    </main>

    {{-- =======================================================================
         5. FOOTER
    ======================================================================= --}}
    <footer class="bg-white border-t border-slate-200/80 py-8 mt-auto relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-slate-400 font-medium">
                &copy; {{ date('Y') }} EventTicket Inc. <span class="hidden sm:inline">|</span> All rights reserved.
            </p>
            <div class="flex gap-4 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                <i class="ph-fill ph-ticket text-2xl text-slate-300"></i>
            </div>
        </div>
    </footer>

    {{-- =======================================================================
         6. SCRIPTS
    ======================================================================= --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Alpine Logic
        function ticketWallet() {
            return {
                search: '',
                qrModalOpen: false,
                currentBookingId: '',
                currentEventName: '',

                matchesSearch(name) {
                    if (this.search === '') return true;
                    return name.includes(this.search.toLowerCase());
                },

                showQr(id, name) {
                    this.currentBookingId = id;
                    this.currentEventName = name;
                    this.qrModalOpen = true;
                },

                closeQr() {
                    this.qrModalOpen = false;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Init AOS
            AOS.init({ duration: 800, once: true, offset: 50 });

            // SweetAlert2 Cancel Confirmation
            const cancelForms = document.querySelectorAll('.js-cancel-form');
            cancelForms.forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const eventName = form.dataset.name;

                    Swal.fire({
                        title: 'Batalkan Tiket?',
                        html: `Yakin ingin membatalkan tiket <b>"${eventName}"</b>?<br><span style="font-size:12px;color:#ef4444">Tindakan ini tidak dapat dikembalikan.</span>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#cbd5e1',
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: 'Tidak',
                        background: '#ffffff',
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl border border-slate-100',
                            title: 'font-heading font-bold text-slate-900',
                            confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-lg shadow-rose-200',
                            cancelButton: 'rounded-xl px-6 py-2.5 font-bold text-slate-600 hover:bg-slate-100'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
