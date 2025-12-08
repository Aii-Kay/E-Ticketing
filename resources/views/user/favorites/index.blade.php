<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Koleksi Event Favorit - EventTicket">

    <title>Favorit Saya - {{ config('app.name', 'EventTicket') }}</title>

    {{-- =======================================================================
         1. ASSETS, FONTS & LIBRARIES
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
         2. CUSTOM STYLES (Ultra Premium)
    ======================================================================= --}}
    <style>
        /* Typography */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* --- 2.1 Aurora Background (Alive Effect) --- */
        .aurora-container {
            position: fixed; inset: 0; z-index: -1;
            background: #ffffff; overflow: hidden;
        }
        .aurora-blob {
            position: absolute; border-radius: 50%;
            filter: blur(80px); opacity: 0.5;
            mix-blend-mode: multiply;
            animation: aurora-float 20s infinite alternate ease-in-out;
        }
        .blob-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #fecdd3; animation-delay: 0s; } /* Rose */
        .blob-2 { bottom: -20%; right: -20%; width: 60vw; height: 60vw; background: #c4b5fd; animation-delay: -5s; } /* Violet */
        .blob-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: #bae6fd; opacity: 0.3; animation-delay: -10s; } /* Sky */

        .noise-overlay {
            position: absolute; inset: 0; opacity: 0.3;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.1'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        @keyframes aurora-float {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            50% { transform: translate(30px, -30px) scale(1.05) rotate(5deg); }
            100% { transform: translate(-20px, 20px) scale(0.95) rotate(-5deg); }
        }

        /* --- 2.2 Glassmorphism Components --- */
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* --- 2.3 Premium Card Design --- */
        .fav-card {
            background: #ffffff;
            border-radius: 1.5rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .fav-card::after {
            content: '';
            position: absolute; inset: 0;
            border-radius: 1.5rem;
            padding: 2px;
            background: linear-gradient(135deg, rgba(244,63,94,0.3), rgba(99,102,241,0.1));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .fav-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 20px 40px -10px rgba(244, 63, 94, 0.15);
        }
        .fav-card:hover::after { opacity: 1; }

        /* --- 2.4 Button Styles --- */
        .btn-action {
            width: 36px; height: 36px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s ease;
        }
        .btn-action:hover { transform: scale(1.1); }
        .btn-action:active { transform: scale(0.9); }

        .btn-remove { background: #fff1f2; color: #e11d48; }
        .btn-remove:hover { background: #ffe4e6; color: #be123c; }

        .btn-share { background: #f0f9ff; color: #0284c7; }
        .btn-share:hover { background: #e0f2fe; color: #0369a1; }

        .btn-book {
            background: linear-gradient(135deg, #0F172A 0%, #334155 100%);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-book:hover {
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
            box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.3);
            transform: translateY(-2px);
        }

        /* --- 2.5 Search Input --- */
        .search-input {
            background: rgba(255,255,255,0.8);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .search-input:focus {
            background: #fff;
            border-color: #f43f5e;
            box-shadow: 0 0 0 4px rgba(244, 63, 94, 0.1);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Animation */
        .animate-float-slow { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="antialiased text-slate-800" x-data="favoritesApp()">

    {{-- DYNAMIC BACKGROUND --}}
    <div class="aurora-container">
        <div class="aurora-blob blob-1"></div>
        <div class="aurora-blob blob-2"></div>
        <div class="aurora-blob blob-3"></div>
        <div class="noise-overlay"></div>
    </div>

    {{-- =======================================================================
         3. NAVBAR (Minimal & Premium)
    ======================================================================= --}}
    <nav class="fixed top-0 w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                {{-- Logo Area --}}
                <div class="flex items-center gap-6">
                    <a href="{{ route('user.dashboard') }}" class="group flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/50 transition-all">
                        <div class="w-9 h-9 bg-slate-900 rounded-lg flex items-center justify-center text-white shadow-md group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-arrow-left"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-slate-900 text-sm leading-none group-hover:text-rose-600 transition-colors">Kembali</span>
                            <span class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Dashboard</span>
                        </div>
                    </a>
                </div>

                {{-- Center Title --}}
                <div class="hidden md:flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center text-rose-500">
                        <i class="ph-fill ph-heart text-xl animate-pulse"></i>
                    </div>
                    <span class="font-heading font-bold text-lg text-slate-900 tracking-tight">Koleksi Favorit</span>
                </div>

                {{-- User Profile (Compact) --}}
                <div class="flex items-center gap-3">
                    <div class="hidden md:flex flex-col items-end mr-1">
                        <span class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-rose-400 to-orange-400 p-[2px] shadow-sm">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=fff&color=f43f5e" class="rounded-full w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- =======================================================================
         4. MAIN CONTENT
    ======================================================================= --}}
    <main class="flex-grow pt-32 pb-20 px-4 sm:px-6 max-w-7xl mx-auto w-full relative z-10">

        {{-- Flash Message --}}
        @if (session('status'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="fixed top-24 right-4 z-50 flex items-center gap-3 px-6 py-4 bg-white/90 backdrop-blur-xl border border-emerald-200 rounded-2xl shadow-xl animate__animated animate__slideInRight">
                <div class="bg-emerald-100 text-emerald-600 p-2 rounded-full">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        {{-- Header Section: Title & Search --}}
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12" data-aos="fade-down">
            <div class="w-full md:w-auto">
                <h1 class="text-4xl md:text-5xl font-heading font-extrabold text-slate-900 mb-3">
                    Wishlist <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-purple-600">Event</span>
                </h1>
                <p class="text-slate-500 max-w-lg text-sm md:text-base leading-relaxed">
                    Kumpulan event yang telah Anda tandai. Pantau ketersediaan tiket dan jangan sampai kehabisan momen spesial Anda.
                </p>
            </div>

            {{-- Real-time Search Input --}}
            <div class="w-full md:w-80 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-rose-500 transition-colors">
                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                </div>
                <input type="text" x-model="search" placeholder="Cari di koleksi..."
                       class="search-input w-full pl-11 pr-4 py-3.5 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 shadow-sm">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center" x-show="search.length > 0">
                    <button @click="search = ''" class="text-slate-400 hover:text-slate-600 p-1 rounded-full hover:bg-slate-100 transition-colors">
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Content Grid --}}
        @if ($favorites->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="rounded-[2.5rem] bg-white/60 backdrop-blur-xl border-2 border-dashed border-slate-300 p-16 md:p-24 text-center animate__animated animate__fadeIn" data-aos="zoom-in">
                <div class="relative w-32 h-32 mx-auto mb-8 animate-float-slow">
                    <div class="absolute inset-0 bg-rose-100 rounded-full blur-xl opacity-50"></div>
                    <div class="relative w-full h-full bg-white rounded-full flex items-center justify-center shadow-lg border border-rose-50">
                        <i class="ph-duotone ph-heart-break text-6xl text-rose-300"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-heading font-bold text-slate-900 mb-3">Belum Ada Favorit</h3>
                <p class="text-slate-500 max-w-md mx-auto mb-8 text-sm leading-relaxed">
                    Anda belum menandai event apapun sebagai favorit. Jelajahi ribuan event menarik dan simpan yang Anda suka di sini.
                </p>
                <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-slate-900 text-white rounded-full font-bold text-sm shadow-xl shadow-slate-900/20 hover:bg-rose-600 hover:shadow-rose-500/30 transition-all transform hover:-translate-y-1">
                    <i class="ph-bold ph-compass"></i> Mulai Jelajah
                </a>
            </div>
        @else
            {{-- GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($favorites as $favorite)
                    @php $event = $favorite->event; @endphp
                    @if ($event)
                        <div class="fav-card-wrapper"
                             x-show="matchesSearch('{{ strtolower($event->name) }}')"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             data-aos="fade-up"
                             data-aos-delay="{{ $loop->index * 100 }}">

                            <article class="fav-card group flex flex-col h-full">
                                {{-- Image Area --}}
                                <div class="relative h-56 overflow-hidden bg-slate-900">
                                    <img src="{{ $event->image ? asset($event->image) : 'https://source.unsplash.com/random/600x400?concert&sig='.$event->id }}"
                                         class="w-full h-full object-cover opacity-90 transition-transform duration-700 group-hover:scale-110 group-hover:opacity-100"
                                         alt="{{ $event->name }}">

                                    {{-- Overlay Gradient --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>

                                    {{-- Category Badge --}}
                                    <div class="absolute top-4 left-4">
                                        <span class="px-3 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-bold text-white uppercase tracking-wider border border-white/20 shadow-sm">
                                            {{ $event->category->name ?? 'Event' }}
                                        </span>
                                    </div>

                                    {{-- Added Date (FIXED LINE) --}}
                                    <div class="absolute top-4 right-4 bg-black/40 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/10">
                                        <p class="text-[10px] font-medium text-white flex items-center gap-1">
                                            <i class="ph-fill ph-clock text-rose-400"></i>
                                            {{ $favorite->created_at?->diffForHumans() ?? 'Baru saja' }}
                                        </p>
                                    </div>

                                    {{-- Date Info --}}
                                    <div class="absolute bottom-4 left-4 text-white">
                                        <p class="text-xs font-bold uppercase tracking-widest text-rose-300 mb-0.5">{{ \Carbon\Carbon::parse($event->date)->format('F Y') }}</p>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-3xl font-heading font-extrabold">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                                            <span class="text-sm font-medium opacity-80 border-l border-white/30 pl-2 ml-1">{{ \Carbon\Carbon::parse($event->date)->format('l') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Content Area --}}
                                <div class="p-6 flex-1 flex flex-col bg-white">
                                    <div class="mb-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                <i class="ph-bold ph-ticket"></i> Tersedia
                                            </span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide flex items-center gap-1">
                                                <i class="ph-fill ph-map-pin"></i> {{ \Illuminate\Support\Str::limit($event->location, 15) }}
                                            </span>
                                        </div>
                                        <h3 class="text-xl font-bold text-slate-900 leading-snug line-clamp-2 group-hover:text-rose-600 transition-colors">
                                            {{ $event->name }}
                                        </h3>
                                    </div>

                                    {{-- Action Bar --}}
                                    <div class="mt-auto pt-5 border-t border-slate-100 flex items-center justify-between gap-3">

                                        <div class="flex gap-2">
                                            {{-- Delete Button --}}
                                            <form action="{{ route('user.favorites.destroy', $favorite->id) }}" method="POST" class="inline js-delete-form" data-name="{{ $event->name }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action btn-remove group/btn relative" title="Hapus Favorit">
                                                    <i class="ph-fill ph-trash text-lg"></i>
                                                    {{-- Tooltip simple --}}
                                                    <span class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover/btn:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">Hapus</span>
                                                </button>
                                            </form>

                                            {{-- Share Button (Mockup) --}}
                                            <button class="btn-action btn-share group/btn relative" title="Bagikan">
                                                <i class="ph-fill ph-share-network text-lg"></i>
                                            </button>
                                        </div>

                                        {{-- Booking Button --}}
                                        <a href="{{ route('user.bookings.create', ['event_id' => $event->id]) }}" class="btn-book flex-1 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-center shadow-lg">
                                            Booking
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Search Empty State --}}
            <div x-show="search.length > 0 && document.querySelectorAll('.fav-card-wrapper[style*=\'display: none\']').length === {{ $favorites->count() }}"
                 class="text-center py-12 hidden"
                 :class="{ '!block': search.length > 0 && document.querySelectorAll('.fav-card-wrapper[style*=\'display: none\']').length === {{ $favorites->count() }} }">
                <div class="inline-flex p-4 rounded-full bg-slate-100 mb-3 text-slate-400">
                    <i class="ph-duotone ph-magnifying-glass text-3xl"></i>
                </div>
                <p class="text-slate-500 font-medium">Tidak ada event yang cocok dengan "<span x-text="search" class="font-bold text-slate-900"></span>"</p>
            </div>
        @endif
    </main>

    <footer class="bg-white border-t border-slate-200/80 py-8 relative z-10 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} EventTicket. All rights reserved.
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Alpine Logic for Search
        function favoritesApp() {
            return {
                search: '',
                matchesSearch(name) {
                    if (this.search === '') return true;
                    return name.includes(this.search.toLowerCase());
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Init Animations
            AOS.init({ duration: 800, once: true, offset: 50 });

            // SweetAlert2 for Delete Confirmation
            const deleteForms = document.querySelectorAll('.js-delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const eventName = form.dataset.name;

                    Swal.fire({
                        title: 'Hapus dari Favorit?',
                        text: `Anda yakin ingin menghapus "${eventName}" dari koleksi?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        background: '#fff',
                        customClass: {
                            popup: 'rounded-3xl shadow-xl',
                            confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                            cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
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
