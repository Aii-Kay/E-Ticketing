<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Category Master - EventTicket">

    <title>Categories - {{ config('app.name', 'EventTicket') }}</title>

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
         2. CUSTOM STYLES (Quantum Horizon)
    ======================================================================= --}}
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* --- Dynamic Background --- */
        .aurora-bg { position: fixed; inset: 0; z-index: -1; background: #ffffff; overflow: hidden; }
        .orb { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.5; mix-blend-mode: multiply; animation: float 25s infinite ease-in-out; }
        .orb-1 { top: -10%; left: -10%; width: 60vw; height: 60vw; background: #a5b4fc; }
        .orb-2 { bottom: -20%; right: -20%; width: 60vw; height: 60vw; background: #67e8f9; animation-delay: -5s; }
        .orb-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: #f9a8d4; opacity: 0.4; animation-delay: -10s; }

        .grid-pattern {
            position: fixed; inset: 0; z-index: -1; opacity: 0.4;
            background-image: linear-gradient(#e2e8f0 1px, transparent 1px), linear-gradient(90deg, #e2e8f0 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%);
        }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(30px, -30px) scale(1.1); } }

        /* --- Glass Sidebar --- */
        .glass-sidebar { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border-right: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 4px 0 30px rgba(0, 0, 0, 0.03); }

        /* --- Quantum Card --- */
        .quantum-card {
            position: relative; overflow: hidden;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 2rem;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            transform-style: preserve-3d;
            height: 100%; display: flex; flex-direction: column;
        }
        .quantum-card::after {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            border-radius: 2rem;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.6);
        }
        .quantum-card:hover {
            transform: translateY(-10px) scale(1.02);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 30px 60px -15px rgba(99, 102, 241, 0.2);
            z-index: 10;
        }

        /* --- Procedural Pattern Header --- */
        .card-pattern {
            height: 160px; width: 100%; position: relative; overflow: hidden;
            background-color: #f8fafc;
            display: flex; align-items: center; justify-content: center;
        }
        .card-pattern svg { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0.6; mix-blend-mode: multiply; transition: transform 0.8s ease; }
        .quantum-card:hover .card-pattern svg { transform: scale(1.2) rotate(5deg); }

        /* Floating Icon */
        .float-icon-wrapper {
            position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
            z-index: 10;
        }
        .float-icon {
            font-size: 4.5rem; color: #1e293b;
            filter: drop-shadow(0 15px 30px rgba(0,0,0,0.15));
            transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .quantum-card:hover .float-icon { transform: scale(1.25) translateY(-10px); color: #4f46e5; }

        /* --- Stats Dashboard --- */
        .dashboard-stat {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 1.5rem; padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
            transition: transform 0.3s;
        }
        .dashboard-stat:hover { transform: translateY(-5px); background: #fff; }

        /* Buttons */
        .btn-create {
            position: relative; overflow: hidden;
            background: #0F172A; color: white; padding: 1rem 2.5rem; border-radius: 1.25rem;
            font-weight: 700; letter-spacing: 0.025em; transition: all 0.3s;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.3);
            display: flex; align-items: center; gap: 0.75rem;
        }
        .btn-create::after {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 60%);
            opacity: 0; transform: scale(0.5); transition: opacity 0.3s, transform 0.3s;
        }
        .btn-create:hover::after { opacity: 1; transform: scale(1); }
        .btn-create:hover { transform: translateY(-3px); box-shadow: 0 20px 40px -5px rgba(15, 23, 42, 0.4); }

        .btn-icon {
            width: 42px; height: 42px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s; background: rgba(255,255,255,0.8);
            border: 1px solid #e2e8f0; color: #64748b;
        }
        .btn-icon:hover { transform: scale(1.15) rotate(5deg); box-shadow: 0 8px 20px -5px rgba(0,0,0,0.1); }
        .btn-edit:hover { color: #4f46e5; border-color: #c7d2fe; background: #eef2ff; }
        .btn-delete:hover { color: #ef4444; border-color: #fecaca; background: #fef2f2; }

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
            <a href="{{ route('admin.categories.index') }}" class="nav-link active"><i class="ph-duotone ph-tag text-xl"></i> Kategori</a>
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
    <div class="lg:ml-72 flex-1 flex flex-col min-h-screen relative z-10 transition-all">

        {{-- Mobile Header --}}
        <header class="h-20 flex items-center justify-between px-6 lg:px-10 lg:hidden bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors"><i class="ph-bold ph-list text-2xl"></i></button>
                <span class="font-bold text-lg text-slate-900">Kategori</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="p-2 rounded-lg text-rose-500 hover:bg-rose-50"><i class="ph-bold ph-sign-out text-2xl"></i></button></form>
        </header>

        <div class="p-6 lg:p-12 space-y-16">

            {{-- A. Header Section --}}
            <div class="flex flex-col md:flex-row justify-between items-end gap-6" data-aos="fade-down">
                <div>
                    <h1 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900 mb-3">
                        Category <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Universe</span>
                    </h1>
                    <p class="text-slate-500 text-lg max-w-xl leading-relaxed">
                        Bangun ekosistem event yang terstruktur dengan kategori visual yang memukau.
                    </p>
                </div>

                {{-- Create Button --}}
                <a href="{{ route('admin.categories.create') }}" class="btn-create group">
                    <i class="ph-bold ph-plus-circle text-xl group-hover:rotate-90 transition-transform"></i>
                    Buat Kategori Baru
                </a>
            </div>

            {{-- B. Stats Dashboard Mini --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="100">
                <div class="dashboard-stat flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform"><i class="ph-duotone ph-tag"></i></div>
                    <div><p class="text-xs font-bold text-slate-400 uppercase">Total Categories</p><p class="text-2xl font-mono-num font-bold text-slate-900">{{ $categories->count() }}</p></div>
                </div>
                <div class="dashboard-stat flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform"><i class="ph-duotone ph-check-circle"></i></div>
                    <div><p class="text-xs font-bold text-slate-400 uppercase">Status Active</p><p class="text-2xl font-mono-num font-bold text-slate-900">{{ $categories->count() }}</p></div>
                </div>
                <div class="dashboard-stat flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform"><i class="ph-duotone ph-magic-wand"></i></div>
                    <div><p class="text-xs font-bold text-slate-400 uppercase">Visual Mode</p><p class="text-lg font-bold text-slate-900">Abstract Prism</p></div>
                </div>
            </div>

            {{-- C. Abstract Grid (Generated Visuals) --}}
            @if ($categories->isEmpty())
                <div class="p-24 text-center rounded-[3rem] border-2 border-dashed border-slate-300 bg-white/40" data-aos="zoom-in">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400 animate-pulse">
                        <i class="ph-duotone ph-shapes text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">Kanvas Kosong</h3>
                    <p class="text-slate-500 text-lg">Belum ada kategori. Mulailah berkreasi sekarang.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach ($categories as $index => $category)
                        @php
                            // Rotasi ikon untuk variasi
                            $icons = ['ph-shapes', 'ph-cube', 'ph-polygon', 'ph-star-four', 'ph-diamond', 'ph-hexagon', 'ph-triangle'];
                            $icon = $icons[$index % count($icons)];

                            // Warna gradasi pattern SVG (untuk visual background)
                            $colors = [
                                ['#6366f1', '#a855f7'], // Indigo-Purple
                                ['#10b981', '#3b82f6'], // Emerald-Blue
                                ['#f43f5e', '#f97316'], // Rose-Orange
                                ['#06b6d4', '#3b82f6'], // Cyan-Blue
                                ['#8b5cf6', '#ec4899'], // Violet-Pink
                            ];
                            $colorPair = $colors[$index % count($colors)];
                        @endphp

                        <article class="quantum-card group" data-tilt data-tilt-max="12" data-tilt-speed="400" data-tilt-perspective="1000" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                            {{-- 1. Procedural Visual Header --}}
                            <div class="card-pattern">
                                {{-- SVG Pattern --}}
                                <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <defs>
                                        <linearGradient id="grad-{{ $index }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:{{ $colorPair[0] }};stop-opacity:0.2" />
                                            <stop offset="100%" style="stop-color:{{ $colorPair[1] }};stop-opacity:0.2" />
                                        </linearGradient>
                                    </defs>
                                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="url(#grad-{{ $index }})" />
                                    <circle cx="{{ ($index * 20) % 100 }}" cy="{{ ($index * 30) % 100 }}" r="40" fill="url(#grad-{{ $index }})" />
                                </svg>

                                {{-- Floating Icon --}}
                                <div class="float-icon-wrapper">
                                    <i class="ph-duotone {{ $icon }} float-icon"></i>
                                </div>

                                {{-- ID Badge --}}
                                <div class="absolute top-4 right-4 z-20">
                                    <span class="px-3 py-1 bg-white/60 backdrop-blur-md rounded-full text-[10px] font-mono-num font-bold text-slate-800 shadow-sm border border-white">
                                        ID-{{ str_pad($category->id, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                            </div>

                            {{-- 2. Content Body --}}
                            <div class="p-8 flex-1 flex flex-col relative z-20 bg-white/10 backdrop-blur-sm">
                                <div class="flex-1">
                                    <h3 class="text-2xl font-display font-extrabold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors leading-tight">
                                        {{ $category->name }}
                                    </h3>

                                    <p class="text-sm text-slate-500 line-clamp-3 leading-relaxed mb-6 font-medium">
                                        {{ $category->description ?? 'Kategori ini belum memiliki deskripsi detail. Digunakan untuk pengelompokan event.' }}
                                    </p>
                                </div>

                                {{-- 3. Action Bar --}}
                                <div class="pt-6 border-t border-slate-100 flex items-center justify-between mt-auto">
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ready</span>
                                    </div>

                                    <div class="flex gap-3 opacity-100 transition-opacity">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-icon btn-edit" title="Edit">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </a>

                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="js-delete-form" data-name="{{ $category->name }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-delete" title="Hapus">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({ duration: 800, once: true, offset: 60 });

            VanillaTilt.init(document.querySelectorAll(".quantum-card"), {
                max: 15, speed: 400, glare: true, "max-glare": 0.4, scale: 1.05, gyro: true
            });

            // SweetAlert Delete
            const deleteForms = document.querySelectorAll('.js-delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Kategori?',
                        html: `Anda yakin ingin menghapus kategori <br><b style="color:#0f172a;font-size:1.2em;">"${form.dataset.name}"</b>?`,
                        icon: 'warning',
                        background: '#ffffff',
                        color: '#1e293b',
                        showCancelButton: true,
                        confirmButtonColor: '#0f172a',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        customClass: { popup: 'rounded-3xl shadow-2xl border border-slate-100' }
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
</body>
</html>
