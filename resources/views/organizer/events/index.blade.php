<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Manajemen Event Organizer - EventTicket">

    <title>Event Saya - {{ config('app.name', 'EventTicket') }}</title>

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
            color: #1E293B; /* Slate 800 */
            overflow-x: hidden;
        }
        h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* --- Aurora Background Light --- */
        .aurora-bg { position: fixed; inset: 0; z-index: -1; background: #ffffff; overflow: hidden; }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.6;
            mix-blend-mode: multiply; animation: float 20s infinite ease-in-out;
        }
        .orb-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #d8b4fe; } /* Lavender */
        .orb-2 { bottom: -20%; right: -20%; width: 60vw; height: 60vw; background: #bae6fd; animation-delay: -5s; } /* Sky Blue */
        .orb-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: #fca5a5; opacity: 0.4; animation-delay: -10s; } /* Soft Rose */

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
            100% { transform: translate(-20px, 20px) scale(0.95); }
        }

        .noise-texture {
            position: absolute; inset: 0; opacity: 0.4; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.1'/%3E%3C/svg%3E");
        }

        /* --- Glass Sidebar Light --- */
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(226, 232, 240, 0.6);
        }

        /* --- Crystal Event Card --- */
        .crypto-card {
            position: relative; overflow: hidden;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
        }
        .crypto-card::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.4) 0%, transparent 100%);
            opacity: 0; transition: opacity 0.3s; pointer-events: none; z-index: 1;
        }
        .crypto-card:hover {
            transform: translateY(-8px) scale(1.01);
            background: rgba(255, 255, 255, 0.95);
            border-color: #a5b4fc;
            box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.15);
        }
        .crypto-card:hover::before { opacity: 1; }

        /* --- Image Hover Zoom --- */
        .card-image-wrapper { overflow: hidden; position: relative; border-radius: 1rem; margin: 0.75rem; }
        .card-image { transition: transform 0.7s ease; width: 100%; height: 100%; object-fit: cover; border-radius: 0.75rem; }
        .crypto-card:hover .card-image { transform: scale(1.1); }

        /* --- Neon Badges (Light Mode) --- */
        .badge-neon {
            display: inline-flex; align-items: center; gap: 0.375rem;
            padding: 0.25rem 0.75rem; border-radius: 9999px;
            font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
            backdrop-filter: blur(4px);
        }
        .neon-blue { background: rgba(239, 246, 255, 0.9); color: #2563eb; border: 1px solid #bfdbfe; }
        .neon-purple { background: rgba(250, 245, 255, 0.9); color: #9333ea; border: 1px solid #e9d5ff; }

        /* --- Interactive Buttons --- */
        .btn-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s; background: rgba(255, 255, 255, 0.9); color: #64748b;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;
        }
        .btn-icon:hover { transform: scale(1.1); color: #4f46e5; border-color: #c7d2fe; box-shadow: 0 5px 15px rgba(99, 102, 241, 0.15); }
        .btn-delete:hover { color: #ef4444; border-color: #fecaca; box-shadow: 0 5px 15px rgba(239, 68, 68, 0.15); }

        /* Create Button (Gradient) */
        .btn-create {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white; padding: 0.75rem 1.5rem; border-radius: 1rem;
            font-weight: 600; transition: all 0.3s ease;
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.3);
        }
        .btn-create:hover { transform: translateY(-2px); box-shadow: 0 15px 30px -5px rgba(79, 70, 229, 0.4); }

        /* Sidebar Link (Light Mode) */
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
        .nav-link i { font-size: 1.25rem; }

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

        {{-- Nav --}}
        <div class="flex-1 px-4 py-8 space-y-1 overflow-y-auto">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Main Menu</p>
            <a href="{{ route('organizer.dashboard') }}" class="nav-link">
                <i class="ph-duotone ph-squares-four"></i> Dashboard
            </a>
            <a href="{{ route('organizer.events.index') }}" class="nav-link active">
                <i class="ph-duotone ph-calendar-plus"></i> Event Saya
            </a>
            <a href="{{ route('organizer.bookings.index') }}" class="nav-link">
                <i class="ph-duotone ph-ticket"></i> Manajemen Booking
            </a>
            <a href="{{ route('organizer.notifications.index') }}" class="nav-link">
                <i class="ph-duotone ph-bell"></i> Notifikasi
            </a>
            <a href="{{ route('organizer.analytics.index') }}" class="nav-link">
                <i class="ph-duotone ph-chart-line-up"></i> Analytics
            </a>
        </div>

        {{-- Profile & Logout Section (FIXED HERE) --}}
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
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-rose-500 hover:bg-rose-50 rounded-lg font-medium transition-colors">
                            <i class="ph-bold ph-sign-out text-lg"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- =======================================================================
         4. MAIN CONTENT AREA
    ======================================================================= --}}
    <div class="lg:ml-72 flex-1 flex flex-col min-h-screen relative z-10 transition-all">

        {{-- Mobile Header --}}
        <header class="h-20 flex items-center justify-between px-6 lg:px-10 lg:hidden bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
                    <i class="ph-bold ph-list text-2xl"></i>
                </button>
                <span class="font-bold text-lg text-slate-900">Event Manager</span>
            </div>

            {{-- Mobile Logout Button --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 rounded-lg text-rose-500 hover:bg-rose-50 transition-colors">
                    <i class="ph-bold ph-sign-out text-2xl"></i>
                </button>
            </form>
        </header>

        <div class="p-6 lg:p-12 space-y-12">

            {{-- A. Header Section --}}
            <div class="flex flex-col md:flex-row justify-between items-end gap-6" data-aos="fade-down">
                <div>
                    <h1 class="text-4xl font-display font-extrabold text-slate-900 mb-2">
                        Galeri <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Event</span>
                    </h1>
                    <p class="text-slate-500 text-lg">Kelola portofolio event Anda. Pantau detail, edit informasi, atau luncurkan event baru.</p>
                </div>

                {{-- FAB Create Button --}}
                <a href="{{ route('organizer.events.create') }}" class="btn-create flex items-center gap-2 group shadow-lg shadow-indigo-500/30">
                    <i class="ph-bold ph-plus group-hover:rotate-90 transition-transform"></i>
                    Buat Event Baru
                </a>
            </div>

            {{-- B. Event Grid --}}
            @if ($events->isEmpty())
                <div class="glass-panel rounded-[2.5rem] p-20 text-center border-dashed border-2 border-slate-300">
                    <div class="w-24 h-24 bg-white/50 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-300 shadow-sm border border-white">
                        <i class="ph-duotone ph-calendar-slash text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">Belum Ada Event</h3>
                    <p class="text-slate-500 mb-8 max-w-md mx-auto">Portofolio event Anda masih kosong. Mulailah perjalanan organizer Anda sekarang!</p>
                    <a href="{{ route('organizer.events.create') }}" class="text-indigo-600 font-bold hover:text-indigo-700 transition-colors border-b-2 border-indigo-200 hover:border-indigo-600">
                        + Buat Event Pertama
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach ($events as $event)
                        <article class="crypto-card group flex flex-col h-full" data-tilt data-tilt-max="5" data-tilt-speed="400" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                            {{-- Image Section --}}
                            <div class="relative h-56 card-image-wrapper bg-slate-100">
                                @if ($event->image)
                                    <img src="{{ asset($event->image) }}" alt="{{ $event->name }}" class="card-image">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-400">
                                        <i class="ph-duotone ph-image text-4xl"></i>
                                    </div>
                                @endif

                                {{-- Badges Overlay --}}
                                <div class="absolute top-3 left-3 z-20">
                                    <span class="badge-neon neon-blue bg-white/90 backdrop-blur text-indigo-600 border-white shadow-sm">
                                        {{ $event->category->name ?? 'Event' }}
                                    </span>
                                </div>

                                {{-- Date Overlay (Bottom Left) --}}
                                <div class="absolute bottom-3 left-3 z-20 flex items-center gap-2">
                                    <div class="bg-white/90 backdrop-blur-md rounded-lg px-3 py-1.5 shadow-md flex items-center gap-2 border border-white">
                                        <span class="text-xs font-bold text-indigo-500 uppercase tracking-wider">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</span>
                                        <span class="text-lg font-mono-num font-bold text-slate-900 leading-none">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                                    </div>
                                </div>

                                {{-- Controls Overlay (Hidden, Show on Hover) --}}
                                <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20 translate-y-2 group-hover:translate-y-0">
                                    <a href="{{ route('organizer.events.edit', $event) }}" class="btn-icon" title="Edit Event">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </a>
                                    <form action="{{ route('organizer.events.destroy', $event) }}" method="POST" class="js-delete-form" data-name="{{ $event->name }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete" title="Hapus Event">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Body Content --}}
                            <div class="px-6 pb-6 pt-2 flex-1 flex flex-col">
                                <h3 class="text-xl font-bold text-slate-900 mb-2 line-clamp-1 group-hover:text-indigo-600 transition-colors">
                                    {{ $event->name }}
                                </h3>

                                <div class="flex items-center gap-4 text-xs text-slate-500 mb-4">
                                    <div class="flex items-center gap-1">
                                        <i class="ph-fill ph-clock text-indigo-400"></i> {{ $event->time }}
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i class="ph-fill ph-map-pin text-indigo-400"></i>
                                        <span class="truncate max-w-[120px]">{{ $event->location }}</span>
                                    </div>
                                </div>

                                <p class="text-sm text-slate-600 line-clamp-2 mb-6 flex-1 leading-relaxed">
                                    {{ $event->description }}
                                </p>

                                {{-- Card Footer --}}
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] text-slate-500 font-bold border border-slate-200">
                                            EO
                                        </div>
                                        <span class="text-xs text-slate-400 font-medium">Milik Anda</span>
                                    </div>
                                    <span class="text-xs font-mono-num font-bold text-indigo-400 tracking-wider bg-indigo-50 px-2 py-1 rounded-md">
                                        ID: #{{ str_pad($event->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

            {{-- Summary Text --}}
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <p class="text-sm text-slate-400 bg-white/50 inline-block px-4 py-2 rounded-full border border-slate-200/50 shadow-sm backdrop-blur-sm">
                    Menampilkan {{ $events->count() }} event aktif dari portofolio Anda.
                </p>
            </div>

        </div>
    </div>

    {{-- =======================================================================
         5. SCRIPTS
    ======================================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Init Animations
            AOS.init({ duration: 800, once: true, offset: 50 });

            // Init 3D Tilt
            VanillaTilt.init(document.querySelectorAll(".crypto-card"), {
                max: 10, speed: 400, glare: true, "max-glare": 0.3, scale: 1.02
            });

            // SweetAlert2 for Delete Confirmation (Light Theme)
            const deleteForms = document.querySelectorAll('.js-delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const eventName = form.dataset.name;
                    Swal.fire({
                        title: 'Hapus Event?',
                        html: `Anda yakin ingin menghapus event <br><b class="text-slate-900">"${eventName}"</b>?`,
                        icon: 'warning',
                        background: '#ffffff',
                        color: '#1e293b',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-2xl shadow-xl border border-slate-100'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
</body>
</html>
