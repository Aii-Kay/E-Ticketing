<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Platform E-Ticketing Event Modern & Terpercaya">
    <title>E-Ticketing Events | Experience the Best</title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Fonts (Google Fonts: Plus Jakarta Sans & Outfit) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- External Libraries (CDN) --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

    {{-- Custom Styles & Animations --}}
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }

        /* Hide scrollbar for cleaner look but keep functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Custom Mesh Gradient Animation */
        @keyframes gradient-xy {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .animate-gradient-xy {
            background-size: 200% 200%;
            animation: gradient-xy 15s ease infinite;
        }

        /* Floating Animation */
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float {
            animation: float-slow 6s ease-in-out infinite;
        }
        .animate-float-delayed {
            animation: float-slow 7s ease-in-out infinite 2s;
        }

        /* Blob Animations */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Preloader */
        #preloader {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: #ffffff;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        .loader-dots div {
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }
        .loader-dots div:nth-child(1) { left: 8px; animation: loader-dots1 0.6s infinite; }
        .loader-dots div:nth-child(2) { left: 8px; animation: loader-dots2 0.6s infinite; }
        .loader-dots div:nth-child(3) { left: 32px; animation: loader-dots2 0.6s infinite; }
        .loader-dots div:nth-child(4) { left: 56px; animation: loader-dots3 0.6s infinite; }
        @keyframes loader-dots1 { 0% { transform: scale(0); } 100% { transform: scale(1); } }
        @keyframes loader-dots3 { 0% { transform: scale(1); } 100% { transform: scale(0); } }
        @keyframes loader-dots2 { 0% { transform: translate(0, 0); } 100% { transform: translate(24px, 0); } }

        /* Custom Swiper Pagination */
        .swiper-pagination-bullet-active {
            background-color: #0f172a !important; /* slate-900 */
            width: 20px !important;
            border-radius: 4px !important;
        }
    </style>
</head>
<body
    class="antialiased bg-slate-50 text-slate-800 selection:bg-indigo-500 selection:text-white overflow-x-hidden"
    x-data="{
        mobileMenuOpen: false,
        scrolled: false,
        searchOpen: false,
        videoModalOpen: false
    }"
    @scroll.window="scrolled = (window.pageYOffset > 20)"
>

    {{-- ==========================================
         PRELOADER
    ========================================== --}}
    <div id="preloader">
        <div class="flex flex-col items-center gap-4">
            <div class="loader-dots relative w-20 h-5">
                <div class="absolute top-0 w-3 h-3 rounded-full bg-indigo-600"></div>
                <div class="absolute top-0 w-3 h-3 rounded-full bg-indigo-600"></div>
                <div class="absolute top-0 w-3 h-3 rounded-full bg-indigo-600"></div>
                <div class="absolute top-0 w-3 h-3 rounded-full bg-indigo-600"></div>
            </div>
            <div class="text-sm font-semibold text-slate-500 tracking-widest uppercase animate-pulse">
                Loading Experience...
            </div>
        </div>
    </div>

    {{-- ==========================================
         NAVBAR (GLASSMORPHISM)
    ========================================== --}}
    <header
        class="fixed w-full top-0 z-50 transition-all duration-300 border-b border-transparent"
        :class="{ 'bg-white/80 backdrop-blur-lg border-slate-200 shadow-sm py-2': scrolled, 'bg-transparent py-4': !scrolled }"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo Area --}}
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer group">
                    <div class="relative w-10 h-10 flex items-center justify-center bg-slate-900 rounded-xl shadow-lg group-hover:rotate-12 transition-transform duration-300">
                        <span class="text-white font-extrabold text-xl tracking-tighter">ET</span>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-rose-500 rounded-full border-2 border-white animate-pulse"></div>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-lg font-bold text-slate-900 leading-none">Event<span class="text-indigo-600">Ticket</span></h1>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wide uppercase">Official Platform</p>
                    </div>
                </div>

                {{-- Desktop Menu --}}
                <nav class="hidden lg:flex items-center gap-8">
                    <a href="#hero" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors relative group">
                        Beranda
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-indigo-600 transition-all group-hover:w-full"></span>
                    </a>
                    <a href="#featured" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors relative group">
                        Jelajah Event
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-indigo-600 transition-all group-hover:w-full"></span>
                    </a>
                    <a href="#cara-kerja" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors relative group">
                        Cara Kerja
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-indigo-600 transition-all group-hover:w-full"></span>
                    </a>
                     {{-- Search Trigger --}}
                     <button
                        @click="searchOpen = !searchOpen"
                        class="p-2 rounded-full text-slate-500 hover:bg-slate-100 hover:text-indigo-600 transition-all"
                        aria-label="Search"
                     >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </nav>

                {{-- Right Actions (Auth) --}}
                <div class="hidden lg:flex items-center gap-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-indigo-600 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="relative inline-flex group">
                            <div class="absolute transition-all duration-1000 opacity-70 -inset-px bg-gradient-to-r from-[#44BCFF] via-[#FF44EC] to-[#FF675E] rounded-full blur-md group-hover:opacity-100 group-hover:-inset-1 group-hover:duration-200 animate-tilt"></div>
                            <button class="relative inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-slate-900 font-pj rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900">
                                Daftar Gratis
                            </button>
                        </a>
                    @endguest

                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 focus:outline-none">
                                <div class="w-9 h-9 rounded-full bg-indigo-100 border-2 border-indigo-200 flex items-center justify-center text-indigo-700 font-bold overflow-hidden">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="text-left hidden xl:block">
                                    <p class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-slate-500 uppercase">{{ auth()->user()->role ?? 'User' }}</p>
                                </div>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            {{-- Dropdown Menu --}}
                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl ring-1 ring-black ring-opacity-5 py-1 z-50 origin-top-right">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700">Dashboard</a>
                                <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700">Profile Setting</a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-semibold">Sign out</button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <div class="lg:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-600 hover:text-slate-900 focus:outline-none p-2">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu Dropdown --}}
        <div x-show="mobileMenuOpen" x-cloak x-collapse class="lg:hidden bg-white border-t border-slate-100 shadow-xl">
            <div class="px-4 py-6 space-y-3">
                <a href="#hero" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-800 hover:bg-indigo-50 hover:text-indigo-600">Beranda</a>
                <a href="#featured" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-800 hover:bg-indigo-50 hover:text-indigo-600">Cari Event</a>
                <a href="#cara-kerja" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-800 hover:bg-indigo-50 hover:text-indigo-600">Cara Kerja</a>
                <div class="border-t border-slate-100 my-2"></div>
                @guest
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-600 hover:text-slate-900">Masuk</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-base font-bold text-center text-white bg-indigo-600 shadow-lg hover:bg-indigo-700">Daftar Sekarang</a>
                @endguest
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-bold text-center text-white bg-slate-900 shadow-lg hover:bg-slate-800">Dashboard</a>
                @endauth
            </div>
        </div>

        {{-- Search Overlay --}}
        <div
            x-show="searchOpen"
            x-transition.opacity
            x-cloak
            class="absolute top-16 left-0 w-full bg-white/95 backdrop-blur-xl border-b border-slate-200 shadow-lg p-6 z-40"
            @click.away="searchOpen = false"
        >
            <div class="max-w-4xl mx-auto">
                <form action="{{ route('events.search') }}" method="GET" class="relative">
                    <input
                        type="text"
                        name="keyword"
                        placeholder="Ketik nama event, artis, atau lokasi..."
                        class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 text-lg focus:border-indigo-500 focus:ring-0 transition-colors"
                        autofocus
                    >
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <div class="mt-4 flex flex-wrap gap-2 text-sm text-slate-500">
                        <span>Populer:</span>
                        <button type="button" class="px-3 py-1 rounded-full bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">Konser Musik</button>
                        <button type="button" class="px-3 py-1 rounded-full bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">Workshop AI</button>
                        <button type="button" class="px-3 py-1 rounded-full bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">Seminar Bisnis</button>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <main>
        {{-- ==========================================
             HERO SECTION (SPLIT + 3D ELEMENTS)
        ========================================== --}}
        <section id="hero" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
            {{-- Dynamic Background --}}
            <div class="absolute inset-0 w-full h-full bg-slate-50">
                <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
                {{-- Grid Pattern --}}
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                    {{-- Left Content --}}
                    <div class="text-center lg:text-left z-10" data-aos="fade-right" data-aos-duration="1000">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/60 backdrop-blur border border-indigo-100 shadow-sm mb-6 animate-float">
                            <span class="flex h-2 w-2 rounded-full bg-indigo-500"></span>
                            <span class="text-xs font-bold text-indigo-700 tracking-wide uppercase">Platform No. #1 di Indonesia</span>
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6">
                            Temukan Event <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-rose-500 typing-effect"></span>
                        </h1>

                        <p class="text-lg sm:text-xl text-slate-600 mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                            Akses ribuan acara seru mulai dari konser, workshop, hingga seminar. Booking tiket instan, pembayaran aman, dan pengalaman tak terlupakan.
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                            <a href="#search" class="w-full sm:w-auto px-8 py-4 rounded-full bg-slate-900 text-white font-bold text-center shadow-lg hover:shadow-indigo-500/30 hover:bg-slate-800 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Cari Event
                            </a>
                            <button @click="videoModalOpen = true" class="w-full sm:w-auto px-8 py-4 rounded-full bg-white text-slate-700 border border-slate-200 font-bold text-center shadow-sm hover:bg-slate-50 hover:border-indigo-200 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 group">
                                <span class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-600 transition-colors">
                                    <svg class="w-4 h-4 text-indigo-600 group-hover:text-white ml-0.5 transition-colors" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                </span>
                                Tonton Demo
                            </button>
                        </div>

                        {{-- Social Proof --}}
                        <div class="mt-10 flex items-center justify-center lg:justify-start gap-4 text-sm text-slate-500 font-medium">
                            <div class="flex -space-x-3">
                                <img class="w-10 h-10 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=1" alt="User">
                                <img class="w-10 h-10 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=2" alt="User">
                                <img class="w-10 h-10 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=3" alt="User">
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600">+2k</div>
                            </div>
                            <p>Bergabung dengan 2,000+ pengguna</p>
                        </div>
                    </div>

                    {{-- Right Content (3D Cards) --}}
                    <div class="relative z-10 perspective-1000" data-aos="fade-left" data-aos-duration="1200">
                        {{-- Decorative Elements --}}
                        <div class="absolute top-10 right-10 w-20 h-20 bg-gradient-to-br from-orange-400 to-rose-500 rounded-2xl rotate-12 blur-xl opacity-60 animate-pulse"></div>

                        {{-- Tilted Card Stack (Using Swiper Effect Cards or Custom CSS) --}}
                        <div class="relative w-full max-w-md mx-auto" data-tilt data-tilt-max="10" data-tilt-speed="400" data-tilt-perspective="1000">
                            <div class="relative bg-white/40 backdrop-blur-xl border border-white/50 rounded-3xl p-4 shadow-2xl transform rotate-3 hover:rotate-0 transition-all duration-500">
                                {{-- Inner Card Content --}}
                                <div class="relative rounded-2xl overflow-hidden aspect-[4/5] shadow-inner group">
                                    <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?q=80&w=1000&auto=format&fit=crop" alt="Concert" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-90"></div>

                                    {{-- Floating Date --}}
                                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur rounded-xl px-3 py-2 text-center shadow-lg">
                                        <span class="block text-xs font-bold text-slate-500 uppercase">DEC</span>
                                        <span class="block text-xl font-extrabold text-slate-900">24</span>
                                    </div>

                                    <div class="absolute bottom-6 left-6 right-6">
                                        <span class="inline-block px-2 py-1 bg-indigo-500/80 backdrop-blur text-white text-[10px] font-bold rounded-md mb-2">HOT EVENT</span>
                                        <h3 class="text-2xl font-bold text-white mb-1">Neon Music Festival</h3>
                                        <div class="flex items-center text-slate-300 text-sm gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            Jakarta International Stadium
                                        </div>
                                    </div>
                                </div>
                                {{-- Ticket Stub Effect --}}
                                <div class="absolute -right-3 top-1/2 w-6 h-6 bg-slate-50 rounded-full"></div>
                                <div class="absolute -left-3 top-1/2 w-6 h-6 bg-slate-50 rounded-full"></div>
                                <div class="mt-4 border-t-2 border-dashed border-white/30 pt-4 flex justify-between items-center px-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-xs">EO</div>
                                        <div class="text-xs text-slate-800 font-semibold">Live Nation</div>
                                    </div>
                                    <button class="px-4 py-1.5 bg-slate-900 text-white text-xs font-bold rounded-full hover:bg-slate-800 transition-colors">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-indigo-500 rounded-3xl transform -rotate-6 scale-95 -z-10 opacity-40 blur-sm"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ==========================================
             STATS BAR
        ========================================== --}}
        <section class="bg-slate-900 py-10 border-y border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-slate-800/50">
                    <div class="space-y-1">
                        <h3 class="text-3xl lg:text-4xl font-extrabold text-white count-up" data-count="{{ isset($events) ? $events->count() : 150 }}">0</h3>
                        <p class="text-xs lg:text-sm font-medium text-slate-400 uppercase tracking-widest">Total Events</p>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-3xl lg:text-4xl font-extrabold text-white count-up" data-count="8500">0</h3>
                        <p class="text-xs lg:text-sm font-medium text-slate-400 uppercase tracking-widest">Tiket Terjual</p>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-3xl lg:text-4xl font-extrabold text-white count-up" data-count="50">0</h3>
                        <p class="text-xs lg:text-sm font-medium text-slate-400 uppercase tracking-widest">Kota</p>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-3xl lg:text-4xl font-extrabold text-white count-up" data-count="98">%</h3>
                        <p class="text-xs lg:text-sm font-medium text-slate-400 uppercase tracking-widest">Kepuasan User</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ==========================================
             SEARCH & FILTER (FLOATING ISLAND STYLE)
        ========================================== --}}
        <section id="search" class="relative -mt-8 z-30">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-3xl shadow-2xl shadow-indigo-500/10 p-6 sm:p-8 border border-slate-100" data-aos="fade-up">
                    <form method="GET" action="{{ route('events.search') }}">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            {{-- Keyword Input --}}
                            <div class="md:col-span-4 space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Cari Event</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="text" name="keyword" value="{{ request('keyword') }}" class="block w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800" placeholder="Konser, Seminar, dll...">
                                </div>
                            </div>

                            {{-- Location Input --}}
                            <div class="md:col-span-3 space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Lokasi</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <input type="text" name="location" value="{{ request('location') }}" class="block w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800" placeholder="Semua Kota">
                                </div>
                            </div>

                            {{-- Date Picker (Flatpickr) --}}
                            <div class="md:col-span-3 space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Tanggal</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z"/></svg>
                                    </div>
                                    <input type="text" name="start_date" id="date-filter" value="{{ request('start_date') }}" class="flatpickr-input block w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800" placeholder="Pilih Tanggal">
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="md:col-span-2">
                                <button type="submit" class="w-full h-[50px] bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                                    Cari
                                </button>
                            </div>
                        </div>

                        {{-- Advanced Filters Collapsible (Optional Expansion) --}}
                        <div x-data="{ expanded: false }" class="mt-4">
                            <button @click="expanded = !expanded" type="button" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 focus:outline-none">
                                <span x-text="expanded ? 'Sembunyikan Filter' : 'Filter Lanjutan (Kategori)'"></span>
                                <svg class="w-3 h-3 transition-transform" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="expanded" x-collapse class="mt-4 pt-4 border-t border-slate-100">
                                <div class="flex flex-wrap gap-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="category_id" value="" class="peer sr-only" {{ !request('category_id') ? 'checked' : '' }}>
                                        <span class="px-4 py-2 rounded-lg bg-slate-50 border border-slate-200 text-sm text-slate-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 peer-checked:border-indigo-200 transition-all">Semua</span>
                                    </label>
                                    @foreach($categories as $cat)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="category_id" value="{{ $cat->id }}" class="peer sr-only" {{ request('category_id') == $cat->id ? 'checked' : '' }}>
                                            <span class="px-4 py-2 rounded-lg bg-slate-50 border border-slate-200 text-sm text-slate-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 peer-checked:border-indigo-200 transition-all">{{ $cat->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        {{-- ==========================================
             CATEGORY SWIPER
        ========================================== --}}
        <section class="py-12 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-6">
                    <h2 class="text-2xl font-bold text-slate-900">Jelajah Kategori</h2>
                    <div class="flex gap-2">
                        <button class="cat-prev w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center hover:bg-white hover:shadow-md transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                        <button class="cat-next w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center hover:bg-white hover:shadow-md transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                    </div>
                </div>

                <div class="swiper categorySwiper overflow-visible !pb-10">
                    <div class="swiper-wrapper">
                        @foreach($categories as $cat)
                            <div class="swiper-slide !w-auto">
                                <a href="{{ route('events.search', ['category_id' => $cat->id]) }}" class="group block w-40 h-40 relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="absolute inset-0 bg-indigo-600/10 group-hover:bg-indigo-600/20 transition-colors"></div>
                                    {{-- Assuming categories have icon names or images, placeholder for now --}}
                                    <div class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center">
                                        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-indigo-600 shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        </div>
                                        <span class="font-bold text-slate-800 text-sm group-hover:text-indigo-700">{{ $cat->name }}</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                         <div class="swiper-slide !w-auto"><div class="w-40 h-40 bg-slate-200 rounded-2xl animate-pulse"></div></div>
                         <div class="swiper-slide !w-auto"><div class="w-40 h-40 bg-slate-200 rounded-2xl animate-pulse"></div></div>
                         <div class="swiper-slide !w-auto"><div class="w-40 h-40 bg-slate-200 rounded-2xl animate-pulse"></div></div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ==========================================
             FEATURED EVENTS (MASONRY/GRID)
        ========================================== --}}
        <section id="featured" class="py-16 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                    <span class="text-indigo-600 font-bold tracking-wider uppercase text-sm">Don't Miss Out</span>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mt-2">Event Unggulan</h2>
                    <div class="h-1 w-20 bg-indigo-500 mx-auto mt-4 rounded-full"></div>
                </div>

                @if($events->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($events as $event)
                            <div class="group relative bg-white rounded-[2rem] shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden border border-slate-100 flex flex-col h-full" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                {{-- Image Wrapper --}}
                                <div class="relative h-64 overflow-hidden">
                                    @if($event->image)
                                        <img src="{{ asset($event->image) }}" alt="{{ $event->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif

                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>

                                    {{-- Category Badge --}}
                                    @if($event->category)
                                        <div class="absolute top-4 left-4 bg-white/20 backdrop-blur-md border border-white/30 text-white text-xs font-bold px-3 py-1 rounded-full">
                                            {{ $event->category->name }}
                                        </div>
                                    @endif

                                    {{-- Date Badge --}}
                                    <div class="absolute top-4 right-4 bg-white rounded-xl p-2 text-center min-w-[3.5rem] shadow-md">
                                        <span class="block text-xs font-bold text-rose-500 uppercase">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</span>
                                        <span class="block text-lg font-extrabold text-slate-900">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="p-6 flex-1 flex flex-col">
                                    <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600 mb-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $event->time }} WIB
                                    </div>

                                    <h3 class="text-xl font-bold text-slate-900 mb-2 line-clamp-1 group-hover:text-indigo-600 transition-colors">
                                        <a href="#" class="focus:outline-none">
                                            <span class="absolute inset-0"></span>
                                            {{ $event->name }}
                                        </a>
                                    </h3>

                                    <p class="text-slate-500 text-sm line-clamp-2 mb-4 flex-1">
                                        {{ $event->description }}
                                    </p>

                                    <div class="flex items-center gap-2 text-sm text-slate-600 mb-6">
                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="truncate">{{ $event->location }}</span>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-auto relative z-20">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                                                 <img src="https://ui-avatars.com/api/?name=EO&background=random" alt="EO">
                                            </div>
                                            <span class="text-xs font-bold text-slate-700">Organizer</span>
                                        </div>

                                        @auth
                                            @if(auth()->user()->role === 'registered_user')
                                                <a href="{{ route('user.bookings.create', ['event_id' => $event->id]) }}" class="px-5 py-2 rounded-full bg-slate-900 text-white text-sm font-bold shadow-lg hover:bg-indigo-600 transition-all transform hover:-translate-y-0.5">
                                                    Booking
                                                </a>
                                            @else
                                                 <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-full border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50">Manage</a>
                                            @endif
                                        @else
                                            <a href="{{ route('register') }}" class="px-5 py-2 rounded-full bg-slate-900 text-white text-sm font-bold shadow-lg hover:bg-indigo-600 transition-all transform hover:-translate-y-0.5">
                                                Booking
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination (Styled) --}}
                    @if(method_exists($events, 'hasPages') && $events->hasPages())
                        <div class="mt-16 flex justify-center">
                            {{ $events->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-20 bg-slate-50 rounded-3xl border border-dashed border-slate-300">
                        <div class="inline-block p-4 rounded-full bg-slate-100 mb-4">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Belum ada event ditemukan</h3>
                        <p class="text-slate-500 mt-2">Coba kata kunci lain atau reset filter pencarian Anda.</p>
                        <a href="{{ route('home') }}" class="inline-block mt-6 px-6 py-2 rounded-full bg-white border border-slate-300 text-slate-700 font-bold hover:bg-slate-50 transition-colors">Reset Filter</a>
                    </div>
                @endif
            </div>
        </section>

        {{-- ==========================================
             HOW IT WORKS (ZIG-ZAG)
        ========================================== --}}
        <section id="cara-kerja" class="py-20 bg-slate-900 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-16" data-aos="fade-up">
                    <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Cara Kerja Platform</h2>
                    <p class="text-slate-400 max-w-2xl mx-auto text-lg">Proses pemesanan tiket yang seamless, dari pencarian hingga scan QR code di lokasi.</p>
                </div>

                <div class="space-y-12 lg:space-y-24">
                    {{-- Step 1 --}}
                    <div class="flex flex-col lg:flex-row items-center gap-10">
                        <div class="lg:w-1/2" data-aos="fade-right">
                             <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-slate-700 bg-slate-800 p-2">
                                <div class="bg-slate-900 rounded-xl h-64 flex items-center justify-center">
                                    {{-- Placeholder Illustration --}}
                                    <svg class="w-32 h-32 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                             </div>
                        </div>
                        <div class="lg:w-1/2" data-aos="fade-left">
                            <div class="flex items-center gap-4 mb-4">
                                <span class="flex items-center justify-center w-12 h-12 rounded-full bg-indigo-600 text-white text-xl font-bold shadow-lg shadow-indigo-500/50">1</span>
                                <h3 class="text-2xl font-bold">Cari Event Favorit</h3>
                            </div>
                            <p class="text-slate-400 text-lg leading-relaxed">
                                Gunakan fitur pencarian canggih kami untuk menemukan event berdasarkan lokasi, kategori, atau tanggal. Filter hasil untuk menemukan yang paling relevan dengan minat Anda.
                            </p>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="flex flex-col lg:flex-row-reverse items-center gap-10">
                        <div class="lg:w-1/2" data-aos="fade-left">
                             <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-slate-700 bg-slate-800 p-2">
                                <div class="bg-slate-900 rounded-xl h-64 flex items-center justify-center">
                                    {{-- Placeholder Illustration --}}
                                    <svg class="w-32 h-32 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                </div>
                             </div>
                        </div>
                        <div class="lg:w-1/2" data-aos="fade-right">
                            <div class="flex items-center gap-4 mb-4">
                                <span class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-600 text-white text-xl font-bold shadow-lg shadow-purple-500/50">2</span>
                                <h3 class="text-2xl font-bold">Booking & Bayar</h3>
                            </div>
                            <p class="text-slate-400 text-lg leading-relaxed">
                                Pilih kategori tiket, jumlah, dan lakukan pemesanan. Proses ini menunggu persetujuan admin/organizer untuk memastikan ketersediaan slot eksklusif.
                            </p>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="flex flex-col lg:flex-row items-center gap-10">
                        <div class="lg:w-1/2" data-aos="fade-right">
                             <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-slate-700 bg-slate-800 p-2">
                                <div class="bg-slate-900 rounded-xl h-64 flex items-center justify-center">
                                    {{-- Placeholder Illustration --}}
                                    <svg class="w-32 h-32 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                </div>
                             </div>
                        </div>
                        <div class="lg:w-1/2" data-aos="fade-left">
                            <div class="flex items-center gap-4 mb-4">
                                <span class="flex items-center justify-center w-12 h-12 rounded-full bg-emerald-600 text-white text-xl font-bold shadow-lg shadow-emerald-500/50">3</span>
                                <h3 class="text-2xl font-bold">Terima E-Ticket</h3>
                            </div>
                            <p class="text-slate-400 text-lg leading-relaxed">
                                Setelah disetujui, E-Ticket dengan QR Code unik akan muncul di dashboard Anda. Unduh PDF dan tunjukkan saat masuk venue.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ==========================================
             WHY CHOOSE US (BENTO GRID)
        ========================================== --}}
        <section class="py-20 bg-slate-50">
             <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900">Mengapa Kami?</h2>
                    <p class="text-slate-600 mt-4 max-w-2xl mx-auto">Platform yang didesain untuk kenyamanan Organizer dan Pengunjung.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[200px]">
                    {{-- Card 1 (Large) --}}
                    <div class="md:col-span-2 md:row-span-2 bg-white rounded-3xl p-8 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all" data-aos="zoom-in">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
                        <div class="relative z-10">
                            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-indigo-500/30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-4">Sistem Validasi Aman</h3>
                            <p class="text-slate-600 leading-relaxed text-lg">
                                Kami menggunakan teknologi enkripsi QR Code dinamis yang mencegah pemalsuan tiket. Organizer dapat melakukan scanning real-time dengan aplikasi validator kami, memastikan hanya pemegang tiket sah yang dapat masuk.
                            </p>
                        </div>
                    </div>

                    {{-- Card 2 --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-lg transition-all" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Real-time Dashboard</h3>
                        <p class="text-sm text-slate-500">Pantau penjualan tiket dan revenue secara langsung dengan grafik interaktif.</p>
                    </div>

                    {{-- Card 3 --}}
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-3xl p-6 shadow-lg text-white hover:shadow-indigo-500/40 transition-all" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-xl font-bold mb-2">24/7 Support</h3>
                        <p class="text-sm text-indigo-100">Tim kami siap membantu kendala teknis kapan saja.</p>
                    </div>

                     {{-- Card 4 (Large Horizontal) --}}
                     <div class="md:col-span-2 bg-white rounded-3xl p-8 shadow-sm border border-slate-100 flex items-center gap-6 hover:shadow-lg transition-all" data-aos="fade-up" data-aos-delay="300">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-900 mb-2">Multi-Payment Gateway</h3>
                            <p class="text-slate-600">Mendukung QRIS, Virtual Account, E-Wallet, dan Kartu Kredit.</p>
                        </div>
                        <div class="flex -space-x-4">
                            <div class="w-10 h-10 rounded-full bg-white border shadow-sm flex items-center justify-center text-[8px] font-bold">VISA</div>
                            <div class="w-10 h-10 rounded-full bg-white border shadow-sm flex items-center justify-center text-[8px] font-bold">GOPAY</div>
                            <div class="w-10 h-10 rounded-full bg-white border shadow-sm flex items-center justify-center text-[8px] font-bold">DANA</div>
                        </div>
                    </div>

                    {{-- Card 5 --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-lg transition-all" data-aos="fade-up" data-aos-delay="400">
                         <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center text-rose-600 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                         </div>
                        <h3 class="text-lg font-bold text-slate-900">User Friendly</h3>
                    </div>
                </div>
             </div>
        </section>

        {{-- ==========================================
             CTA (PARALLAX)
        ========================================== --}}
        <section class="relative py-24 overflow-hidden">
            <div class="absolute inset-0 bg-slate-900">
                 <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-20 mix-blend-overlay fixed-bg" alt="Crowd">
                 <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-slate-900"></div>
            </div>

            <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight" data-aos="zoom-in">
                    Siap Membuat Event Anda <br> <span class="text-indigo-400">Meledak?</span>
                </h2>
                <p class="text-slate-300 text-lg md:text-xl mb-10 max-w-2xl mx-auto">
                    Bergabunglah dengan ribuan organizer sukses lainnya. Kelola tiket lebih mudah, jangkau audiens lebih luas.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @guest
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-indigo-600 text-white font-bold rounded-full shadow-[0_0_20px_rgba(79,70,229,0.5)] hover:shadow-[0_0_30px_rgba(79,70,229,0.7)] hover:scale-105 transition-all duration-300">
                            Mulai Sekarang - Gratis
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-transparent border border-white/30 text-white font-bold rounded-full hover:bg-white/10 transition-colors">
                            Masuk Akun
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-white text-slate-900 font-bold rounded-full shadow-lg hover:bg-slate-100 transition-colors">
                            Menuju Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </section>
    </main>

    {{-- ==========================================
         MEGA FOOTER
    ========================================== --}}
    <footer class="bg-white border-t border-slate-200 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                {{-- Brand --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white font-bold">ET</div>
                        <span class="text-xl font-bold text-slate-900">EventTicket</span>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Platform e-ticketing masa depan. Kami menghubungkan passion dengan pengalaman tak terlupakan melalui teknologi yang aman dan mudah.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="text-slate-400 hover:text-indigo-600 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                        <a href="#" class="text-slate-400 hover:text-indigo-600 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                    </div>
                </div>

                {{-- Links --}}
                <div>
                    <h3 class="font-bold text-slate-900 mb-4">Perusahaan</h3>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Karir</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Kontak</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-slate-900 mb-4">Bantuan</h3>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">FAQs</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div>
                    <h3 class="font-bold text-slate-900 mb-4">Newsletter</h3>
                    <p class="text-xs text-slate-500 mb-4">Dapatkan info event terbaru & promo eksklusif.</p>
                    <form class="flex flex-col gap-2">
                        <input type="email" placeholder="Email Anda" class="w-full px-4 py-2 rounded-lg bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm">
                        <button type="submit" class="w-full px-4 py-2 bg-slate-900 text-white text-sm font-bold rounded-lg hover:bg-slate-800 transition-colors">Langganan</button>
                    </form>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} EventTicket Inc. All rights reserved.</p>
                <div class="flex gap-4 text-xs text-slate-400">
                    <a href="#" class="hover:text-slate-600">Privacy</a>
                    <a href="#" class="hover:text-slate-600">Terms</a>
                    <a href="#" class="hover:text-slate-600">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- ==========================================
         VIDEO MODAL
    ========================================== --}}
    <div
        x-show="videoModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
        x-transition.opacity
        x-cloak
    >
        <div @click.away="videoModalOpen = false" class="relative w-full max-w-4xl aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl">
            <button @click="videoModalOpen = false" class="absolute top-4 right-4 text-white hover:text-rose-500 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="w-full h-full flex items-center justify-center text-white">
                <p class="text-lg">Video Demo Placeholder</p>
                {{-- <iframe class="w-full h-full" src="YOUR_YOUTUBE_EMBED_URL" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe> --}}
            </div>
        </div>
    </div>

    {{-- ==========================================
         SCRIPTS INITIALIZATION
    ========================================== --}}

    <script src="//unpkg.com/alpinejs" defer></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // 1. Remove Preloader
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
            }, 800);

            // 2. Initialize AOS (Animate On Scroll)
            AOS.init({
                duration: 800,
                once: true,
                offset: 100,
                easing: 'ease-out-cubic',
            });

            // 3. Initialize Swiper (Category Slider)
            const swiper = new Swiper('.categorySwiper', {
                slidesPerView: 'auto',
                spaceBetween: 20,
                grabCursor: true,
                navigation: {
                    nextEl: '.cat-next',
                    prevEl: '.cat-prev',
                },
                breakpoints: {
                    640: { spaceBetween: 30 },
                }
            });

            // 4. Initialize Typed.js (Hero Text)
            new Typed('.typing-effect', {
                strings: ['Konser Musik', 'Seminar Bisnis', 'Workshop Kreatif', 'Festival Seni'],
                typeSpeed: 60,
                backSpeed: 40,
                backDelay: 2000,
                loop: true
            });

            // 5. Initialize Flatpickr
            flatpickr("#date-filter", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F Y",
                minDate: "today",
                disableMobile: "true"
            });

            // 6. Number Counting Animation (Simple Implementation)
            const counters = document.querySelectorAll('.count-up');
            const options = { threshold: 0.5 };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const target = +counter.getAttribute('data-count');
                        const speed = 200; // The lower the slower

                        const updateCount = () => {
                            const count = +counter.innerText.replace(/,/g, '').replace(/%/g, ''); // cleanup
                            const inc = target / speed;

                            if (count < target) {
                                counter.innerText = Math.ceil(count + inc);
                                setTimeout(updateCount, 20);
                            } else {
                                counter.innerText = target.toLocaleString(); // Add commas
                                if(counter.nextElementSibling && counter.nextElementSibling.innerText.includes('Kepuasan')) {
                                    counter.innerText += '%';
                                }
                            }
                        };
                        updateCount();
                        observer.unobserve(counter);
                    }
                });
            }, options);

            counters.forEach(counter => observer.observe(counter));
        });
    </script>
</body>
</html>
