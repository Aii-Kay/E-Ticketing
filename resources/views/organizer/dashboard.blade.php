<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Organizer Dashboard Premium">

    <title>Organizer Dashboard - {{ config('app.name', 'EventTicket') }}</title>

    {{-- =======================================================================
         1. ASSETS, FONTS & LIBRARIES
    ======================================================================= --}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>

    {{-- =======================================================================
         2. CUSTOM STYLES (Luminous Horizon Theme)
    ======================================================================= --}}
    <style>
        /* Typography */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* --- 2.1 Aurora Background Light --- */
        .aurora-bg { position: fixed; inset: 0; z-index: -1; background: #ffffff; overflow: hidden; }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.5;
            mix-blend-mode: multiply; animation: float 20s infinite ease-in-out;
        }
        .orb-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #e0e7ff; animation-delay: 0s; }
        .orb-2 { bottom: -20%; right: -20%; width: 60vw; height: 60vw; background: #fce7f3; animation-delay: -5s; }
        .orb-3 { top: 30%; left: 30%; width: 40vw; height: 40vw; background: #bae6fd; opacity: 0.3; animation-delay: -10s; }

        .noise-texture {
            position: absolute; inset: 0; opacity: 0.4; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.1'/%3E%3C/svg%3E");
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        /* --- 2.2 Glass UI --- */
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.02);
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(24px) saturate(120%);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-panel:hover {
            background: rgba(255, 255, 255, 0.85);
            transform: translateY(-5px);
            box-shadow: 0 20px 50px -10px rgba(99, 102, 241, 0.1);
            border-color: #fff;
        }

        /* --- 2.3 Premium Stats Cards --- */
        .stat-card {
            position: relative; overflow: hidden;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid #f1f5f9; border-radius: 2rem; padding: 1.5rem;
            transition: all 0.4s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            border-color: #a5b4fc;
            box-shadow: 0 15px 35px -5px rgba(99, 102, 241, 0.15);
        }

        /* Highlight Card (Revenue) */
        .stat-card-highlight {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white; border: none;
            box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.3);
            position: relative; overflow: hidden;
        }
        .stat-card-highlight::before {
            content: ''; position: absolute; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3Ccircle cx='13' cy='13' r='3'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        /* --- 2.4 Inputs & Forms --- */
        .glass-input {
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid #e2e8f0;
            border-radius: 1rem; color: #1e293b;
            transition: all 0.3s;
        }
        .glass-input:focus {
            background: #fff; border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            outline: none;
        }

        /* --- 2.5 Sidebar Navigation --- */
        .nav-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.85rem 1rem; border-radius: 1rem;
            color: #64748b; font-weight: 500;
            transition: all 0.2s ease;
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
        <div class="h-24 flex items-center px-8 border-b border-slate-200/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/20">
                    EO
                </div>
                <div class="flex flex-col">
                    <span class="font-display font-bold text-xl tracking-tight text-slate-900">Event<span class="text-indigo-600">Ticket</span></span>
                    <span class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Organizer Panel</span>
                </div>
            </div>
        </div>

        {{-- Menu --}}
        <div class="flex-1 px-4 py-8 space-y-1.5 overflow-y-auto">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Main Menu</p>

            <a href="{{ route('organizer.dashboard') }}" class="nav-link active">
                <i class="ph-duotone ph-squares-four text-xl"></i> Dashboard
            </a>
            <a href="{{ route('organizer.events.index') }}" class="nav-link">
                <i class="ph-duotone ph-calendar-plus text-xl"></i> Event Saya
            </a>
            <a href="{{ route('organizer.bookings.index') }}" class="nav-link">
                <i class="ph-duotone ph-users-three text-xl"></i> Booking Masuk
            </a>
            <a href="{{ route('organizer.notifications.index') }}" class="nav-link">
                <i class="ph-duotone ph-bell text-xl"></i> Notifikasi
            </a>
            <a href="{{ route('organizer.analytics.index') }}" class="nav-link">
                <i class="ph-duotone ph-chart-line-up text-xl"></i> Analytics
            </a>
        </div>

        {{-- Profile Footer (FIXED LOGOUT) --}}
        <div class="p-4 border-t border-slate-200/50 relative" x-data="{ open: false }">

            {{-- User Button --}}
            <div @click="open = !open" class="bg-white/60 border border-slate-200 p-3 rounded-2xl flex items-center gap-3 cursor-pointer hover:bg-white transition-all shadow-sm">
                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-500 to-pink-500 p-[2px]">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=f1f5f9&color=4f46e5" class="rounded-full w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">Organizer</p>
                </div>
                <i class="ph-bold ph-caret-up text-slate-400"></i>
            </div>

            {{-- Logout Menu (Popup ke Atas) --}}
            <div x-show="open" @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute bottom-20 left-4 right-4 bg-white rounded-xl shadow-2xl border border-slate-100 p-2 z-50">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-rose-500 hover:bg-rose-50 rounded-lg font-bold transition-colors">
                        <i class="ph-bold ph-sign-out text-lg"></i> Keluar
                    </button>
                </form>

            </div>
        </div>
    </aside>

    {{-- =======================================================================
         4. MAIN CONTENT AREA
    ======================================================================= --}}

    {{-- LOGIC PHP: Menghitung Data Real-Time di View --}}
    @php
        $organizerId = Auth::id();

        // 1. Total Events (Milik Organizer)
        $totalEvents = \App\Models\Event::where('created_by', $organizerId)->count();

        // 2. Ambil ID event milik organizer
        $myEventIds = \App\Models\Event::where('created_by', $organizerId)->pluck('id');

        // 3. Tiket Terjual (Hanya status approved)
        $totalTicketsSold = \App\Models\Booking::whereIn('event_id', $myEventIds)
            ->where('status', 'approved')
            ->sum('quantity');

        // 4. Total Revenue (Quantity * Ticket Price)
        $revenue = 0;
        $approvedBookings = \App\Models\Booking::with('ticketType')
            ->whereIn('event_id', $myEventIds)
            ->where('status', 'approved')
            ->get();

        foreach($approvedBookings as $b) {
            $price = $b->ticketType ? $b->ticketType->price : 0;
            $revenue += ($b->quantity * $price);
        }

        // 5. Data Grafik Bulanan
        $monthlyRevenue = array_fill(1, 12, 0);
        foreach($approvedBookings as $b) {
            if($b->created_at->format('Y') == date('Y')) {
                $month = (int)$b->created_at->format('n');
                $price = $b->ticketType ? $b->ticketType->price : 0;
                $monthlyRevenue[$month] += ($b->quantity * $price);
            }
        }
        $chartData = array_values($monthlyRevenue);

        // 6. Data Kategori (Fallback jika controller tidak mengirim)
        $categories = $categories ?? \App\Models\Category::all();
    @endphp

    <div class="lg:ml-72 flex-1 flex flex-col min-h-screen relative z-10 transition-all">

        {{-- Mobile Header --}}
        <header class="h-20 flex items-center justify-between px-6 lg:px-10 lg:hidden bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
                    <i class="ph-bold ph-list text-2xl"></i>
                </button>
                <span class="font-bold text-lg text-slate-900">Dashboard</span>
            </div>
            {{-- Mobile Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 rounded-lg text-rose-500 hover:bg-rose-50">
                    <i class="ph-bold ph-sign-out text-2xl"></i>
                </button>
            </form>
        </header>

        <div class="p-6 lg:p-12 space-y-12">

            {{-- A. Header Section --}}
            <div class="flex flex-col md:flex-row justify-between items-end gap-6" data-aos="fade-down">
                <div>
                    <h1 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900 mb-2">
                        Performance <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Overview</span>
                    </h1>
                    <p class="text-slate-500 text-lg">Pantau pertumbuhan bisnis event Anda secara real-time.</p>
                </div>

                {{-- FAB Create Button --}}
                <a href="{{ route('organizer.events.create') }}" class="group relative px-8 py-3.5 rounded-2xl bg-indigo-600 text-white font-bold shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all overflow-hidden transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-500 ease-in-out skew-x-12"></div>
                    <span class="relative flex items-center gap-2">
                        <i class="ph-bold ph-plus-circle text-xl"></i> Buat Event Baru
                    </span>
                </a>
            </div>

            {{-- B. Stats Cards (Data Real-Time) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-aos="fade-up">

                {{-- Card 1: Total Events --}}
                <div class="stat-card group" data-tilt data-tilt-max="5">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="ph-duotone ph-calendar-check"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-wider border border-slate-200">Portofolio</span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-mono-num font-bold text-slate-900 mb-1">{{ $totalEvents }}</h3>
                        <p class="text-sm font-medium text-slate-500">Event Dipublikasikan</p>
                    </div>
                </div>

                {{-- Card 2: Tickets Sold --}}
                <div class="stat-card group" data-tilt data-tilt-max="5">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="ph-duotone ph-ticket"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-[10px] font-bold text-emerald-600 uppercase tracking-wider border border-emerald-200">Terjual</span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-mono-num font-bold text-slate-900 mb-1">{{ $totalTicketsSold }}</h3>
                        <p class="text-sm font-medium text-slate-500">Total Tiket Laku</p>
                    </div>
                </div>

                {{-- Card 3: Revenue (Highlight) --}}
                <div class="stat-card-highlight rounded-[2rem] p-8 group cursor-default" data-tilt data-tilt-max="5" data-tilt-glare data-tilt-max-glare="0.2">
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl text-white shadow-inner border border-white/20">
                                <i class="ph-bold ph-currency-dollar"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-white/10 text-[10px] font-bold border border-white/20 backdrop-blur-sm">Net Income</span>
                        </div>
                        <div>
                            <p class="text-indigo-100 text-sm font-bold uppercase tracking-wider mb-1">Total Pendapatan</p>
                            <h3 class="text-4xl font-mono-num font-bold text-white tracking-tight">
                                Rp {{ number_format($revenue, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-purple-500/40 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
                </div>
            </div>

            {{-- C. Analytics Chart & Forms --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Chart Section --}}
                <div class="lg:col-span-2 glass-panel rounded-[2.5rem] p-8 md:p-10" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="font-bold text-xl text-slate-900">Analitik Penjualan {{ date('Y') }}</h3>
                            <p class="text-sm text-slate-500">Pendapatan bulanan dari event Anda.</p>
                        </div>
                        <div class="bg-slate-100 p-1 rounded-xl flex gap-1">
                            <button class="px-4 py-1.5 bg-white text-slate-900 text-xs font-bold rounded-lg shadow-sm">Revenue</button>
                            <button class="px-4 py-1.5 text-slate-500 text-xs font-bold hover:text-slate-700 transition-colors">Tiket</button>
                        </div>
                    </div>
                    <div class="h-80 w-full relative">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                {{-- Quick Forms (Ticket Type) --}}
                <div class="space-y-6" data-aos="fade-left" data-aos-delay="200">

                    <div class="glass-panel rounded-[2.5rem] p-8 border-t-4 border-indigo-500">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                                <i class="ph-fill ph-tag text-xl"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 text-lg">Tambah Tipe Tiket</h3>
                        </div>

                        <form method="POST" action="{{ route('organizer.ticket-types.store') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Event ID</label>
                                <input type="number" name="event_id" class="glass-input w-full px-4 py-3 text-sm font-medium focus:outline-none" placeholder="Masukkan ID Event" required>
                                <p class="text-[10px] text-slate-400 mt-1 italic">*Lihat ID di halaman Event Saya</p>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Nama</label>
                                    <input type="text" name="name" class="glass-input w-full px-4 py-3 text-sm font-medium focus:outline-none" placeholder="VIP" required>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Kuota</label>
                                    <input type="number" name="quota" class="glass-input w-full px-4 py-3 text-sm font-medium focus:outline-none" placeholder="100" required>
                                </div>
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Harga (IDR)</label>
                                <input type="number" name="price" class="glass-input w-full px-4 py-3 text-sm font-medium focus:outline-none" placeholder="150000" required>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-slate-900/20 flex items-center justify-center gap-2">
                                <i class="ph-bold ph-plus-circle"></i> Simpan Tiket
                            </button>
                        </form>
                    </div>

                    {{-- Mini Calendar --}}
                    <div class="glass-panel rounded-[2rem] p-6 flex items-center justify-center">
                        <div id="mini-calendar" class="text-xs"></div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- =======================================================================
         5. SCRIPTS
    ======================================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({ duration: 800, once: true, offset: 50 });

            // 1. Vanilla Tilt
            VanillaTilt.init(document.querySelectorAll("[data-tilt]"), {
                max: 10, speed: 400, glare: true, "max-glare": 0.3, scale: 1.02
            });

            // 2. Calendar
            flatpickr("#mini-calendar", {
                inline: true, dateFormat: "Y-m-d", locale: { firstDayOfWeek: 1 }, theme: "light",
            });

            // 3. Chart JS
            const ctx = document.getElementById('revenueChart').getContext('2d');
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Indigo
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Pendapatan (IDR)',
                        data: chartData,
                        borderColor: '#6366f1',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { size: 13, family: "'Plus Jakarta Sans', sans-serif" },
                            bodyFont: { size: 12, family: "'Plus Jakarta Sans', sans-serif" },
                            cornerRadius: 10,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" }, color: '#64748b' } },
                        y: { border: { display: false }, grid: { color: '#f1f5f9', borderDash: [5, 5] }, ticks: { font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" }, color: '#64748b', callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } }
                    }
                }
            });
        });
    </script>
</body>
</html>
