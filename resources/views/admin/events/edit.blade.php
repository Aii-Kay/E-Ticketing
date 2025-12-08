<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Edit Event Studio - Admin Panel">

    <title>Edit Event - {{ config('app.name', 'EventTicket') }}</title>

    {{-- =======================================================================
         1. ASSETS & LIBRARIES
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- =======================================================================
         2. CUSTOM STYLES (Ethereal Masterpiece Theme)
    ======================================================================= --}}
    <style>
        /* Base Typography */
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; overflow-x: hidden; }
        h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* --- Aurora Background (Alive) --- */
        .aurora-bg { position: fixed; inset: 0; z-index: -1; background: #ffffff; overflow: hidden; }
        .orb { position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.6; mix-blend-mode: multiply; animation: float 20s infinite ease-in-out; }
        .orb-1 { top: -10%; left: -10%; width: 60vw; height: 60vw; background: #c7d2fe; }
        .orb-2 { bottom: -20%; right: -20%; width: 60vw; height: 60vw; background: #fbcfe8; animation-delay: -5s; }
        .orb-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: #bfdbfe; opacity: 0.4; animation-delay: -10s; }
        .noise-texture { position: absolute; inset: 0; opacity: 0.4; pointer-events: none; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.1'/%3E%3C/svg%3E"); }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(30px, -30px) scale(1.1); } 100% { transform: translate(-20px, 20px) scale(0.95); } }

        /* --- Glass Sidebar --- */
        .glass-sidebar { background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(20px); border-right: 1px solid rgba(226, 232, 240, 0.6); box-shadow: 4px 0 24px rgba(0, 0, 0, 0.02); }

        /* --- Form Panel (Floating Glass) --- */
        .glass-form-panel {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(24px) saturate(120%);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 2rem;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        /* --- Premium Inputs --- */
        .input-group { position: relative; }
        .glass-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem 1rem 1rem 3.5rem; /* Space for icon */
            color: #1e293b;
            font-size: 0.95rem; font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-input:focus {
            background: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            outline: none;
            transform: translateY(-2px);
        }
        .input-icon {
            position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: 1.5rem; pointer-events: none; transition: 0.3s;
        }
        .glass-input:focus + .input-icon { color: #6366f1; transform: translateY(-50%) scale(1.1); }

        label {
            font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;
            color: #64748b; margin-bottom: 0.5rem; display: block; margin-left: 0.5rem;
        }

        /* --- Live Preview Card (3D Levitating) --- */
        .preview-card-container { position: sticky; top: 2rem; perspective: 1000px; }
        .preview-card {
            background: #fff;
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 0 30px 60px -12px rgba(0,0,0,0.15);
            border: 1px solid #f1f5f9;
            transform-style: preserve-3d;
            transition: all 0.5s ease;
        }
        .preview-image-wrapper {
            height: 320px; overflow: hidden; position: relative;
            border-bottom: 1px solid #f1f5f9;
        }
        .preview-image { width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s ease; }
        .preview-card:hover .preview-image { transform: scale(1.05); }

        /* --- Buttons --- */
        .btn-submit {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white; padding: 1rem 3rem; border-radius: 1rem;
            font-weight: 700; letter-spacing: 0.025em;
            transition: all 0.3s;
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
            display: inline-flex; align-items: center; gap: 0.75rem;
        }
        .btn-submit:hover { transform: translateY(-3px) scale(1.02); box-shadow: 0 20px 40px -5px rgba(79, 70, 229, 0.5); }

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
            <a href="{{ route('admin.events.index') }}" class="nav-link active"><i class="ph-duotone ph-calendar-plus text-xl"></i> Kelola Event</a>
            <a href="{{ route('admin.bookings.index') }}" class="nav-link"><i class="ph-duotone ph-ticket text-xl"></i> Kelola Booking</a>

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
    <div class="lg:ml-72 flex-1 flex flex-col min-h-screen relative z-10 transition-all">

        {{-- Mobile Header --}}
        <header class="h-20 flex items-center justify-between px-6 lg:px-10 lg:hidden bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors"><i class="ph-bold ph-list text-2xl"></i></button>
                <span class="font-bold text-lg text-slate-900">Edit Event</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="p-2 rounded-lg text-rose-500 hover:bg-rose-50"><i class="ph-bold ph-sign-out text-2xl"></i></button></form>
        </header>

        <div class="p-6 lg:p-12 max-w-7xl mx-auto w-full">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12" data-aos="fade-down">
                <div>
                    <h1 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900 mb-2">
                        Studio <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Event</span>
                    </h1>
                    <p class="text-slate-500 text-lg">Perbarui detail acara Anda dengan visualisasi langsung.</p>
                </div>
                <a href="{{ route('admin.events.index') }}" class="px-6 py-3 rounded-2xl bg-white border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 group">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Galeri
                </a>
            </div>

            {{-- Main Form Grid --}}
            @php
                $role = auth()->user()->role ?? null;
                $routePrefix = $role === 'admin' ? 'admin' : 'organizer';
            @endphp

{{-- Tambahkan enctype="multipart/form-data" di sini --}}
            <form method="POST" action="{{ route($routePrefix . '.events.store') }}"
                enctype="multipart/form-data"
                class="grid grid-cols-1 lg:grid-cols-3 gap-10"
                x-data="{
                    imageUrl: '',
                    eventName: '',
                    eventLoc: '',
                    eventDate: '',
                    eventTime: ''
                }">
                @csrf
                @method('PUT')

                {{-- Left Column: Form Inputs --}}
                <div class="lg:col-span-2 glass-form-panel p-8 md:p-12 space-y-8" data-aos="fade-up">

                    {{-- Error Handler --}}
                    @if ($errors->any())
                        <div class="p-5 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 text-sm shadow-sm flex gap-3">
                            <i class="ph-fill ph-warning-circle text-xl shrink-0"></i>
                            <div>
                                <p class="font-bold mb-1">Periksa Inputan Anda:</p>
                                <ul class="list-disc list-inside opacity-90">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                            </div>
                        </div>
                    @endif

                    {{-- 1. Identity --}}
                    <div>
                        <label>Judul Event</label>
                        <div class="input-group">
                            <input type="text" name="name" x-model="eventName" class="glass-input" placeholder="Masukkan nama event..." required>
                            <i class="ph-duotone ph-ticket input-icon"></i>
                        </div>
                    </div>

                    {{-- 2. Context (Grid) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label>Kategori</label>
                            <div class="input-group">
                                <select name="category_id" class="glass-input appearance-none cursor-pointer" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="ph-duotone ph-tag input-icon"></i>
                                <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>
                        <div>
                            <label>Lokasi Venue</label>
                            <div class="input-group">
                                <input type="text" name="location" x-model="eventLoc" class="glass-input" placeholder="Nama gedung/kota..." required>
                                <i class="ph-duotone ph-map-pin input-icon"></i>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Timing (Grid) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label>Tanggal Pelaksanaan</label>
                            <div class="input-group">
                                <input type="text" name="date" id="date-picker" x-model="eventDate" class="glass-input" placeholder="Pilih Tanggal" required>
                                <i class="ph-duotone ph-calendar-blank input-icon"></i>
                            </div>
                        </div>
                        <div>
                            <label>Waktu Mulai</label>
                            <div class="input-group">
                                <input type="text" name="time" id="time-picker" x-model="eventTime" class="glass-input" placeholder="Pilih Jam" required>
                                <i class="ph-duotone ph-clock input-icon"></i>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Narrative --}}
                    <div>
                        <label>Deskripsi Lengkap</label>
                        <div class="input-group">
                            <textarea name="description" rows="6" class="glass-input leading-relaxed" placeholder="Ceritakan detail menarik tentang acara ini..." required style="padding-left: 1.25rem !important;">{{ old('description', $event->description) }}</textarea>
                        </div>
                    </div>


                    <div>
                        <label>Upload Banner Event</label>
                        <div class="input-group">
                            {{-- Input File dengan logika Preview AlpineJS --}}
                            <input type="file"
                                name="image"
                                class="glass-input file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                                accept="image/*"
                                @change="imageUrl = URL.createObjectURL($event.target.files[0])">

                            <i class="ph-duotone ph-upload-simple input-icon"></i>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-2 ml-1 italic font-medium">
                            *Format: JPG, PNG, WEBP (Max 2MB). Gambar akan otomatis muncul di preview kanan.
                        </p>
                    </div>

                    {{-- Footer Action --}}
                    <div class="pt-8 border-t border-slate-200/50 flex justify-end">
                        <button type="submit" class="btn-submit">
                            <i class="ph-bold ph-floppy-disk text-xl"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>

                {{-- Right Column: Holographic Preview --}}
                <div class="lg:col-span-1 hidden lg:block" data-aos="fade-left" data-aos-delay="200">
                    <div class="preview-card-container">
                        <div class="preview-card" data-tilt data-tilt-max="5" data-tilt-speed="400">

                            {{-- Image Area --}}
                            <div class="preview-image-wrapper bg-slate-100">
                                <img :src="imageUrl ? imageUrl : 'https://placehold.co/600x400?text=Event+Banner'"
                                     alt="Preview"
                                     class="preview-image">

                                {{-- Floating Badges --}}
                                <div class="absolute top-5 left-5 flex gap-2">
                                    <div class="bg-white/90 backdrop-blur-md rounded-xl px-3 py-1.5 text-xs font-bold text-indigo-600 shadow-lg border border-white/50 flex items-center gap-1.5">
                                        <i class="ph-fill ph-eye"></i> Live Preview
                                    </div>
                                </div>
                            </div>

                            {{-- Content Area --}}
                            <div class="p-8 space-y-6">
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900 leading-tight mb-2 line-clamp-2" x-text="eventName ? eventName : 'Nama Event Anda'"></h3>

                                    <div class="flex flex-col gap-3 mt-4">
                                        <div class="flex items-center gap-3 text-sm text-slate-500 font-medium">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center border border-indigo-100"><i class="ph-fill ph-calendar-blank"></i></div>
                                            <span x-text="eventDate ? new Date(eventDate).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : 'Tanggal Event'"></span>
                                        </div>
                                        <div class="flex items-center gap-3 text-sm text-slate-500 font-medium">
                                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center border border-purple-100"><i class="ph-fill ph-clock"></i></div>
                                            <span x-text="eventTime ? eventTime + ' WIB' : '00:00 WIB'"></span>
                                        </div>
                                        <div class="flex items-center gap-3 text-sm text-slate-500 font-medium">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100"><i class="ph-fill ph-map-pin"></i></div>
                                            <span x-text="eventLoc ? eventLoc : 'Lokasi Venue'"></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Organizer Info Box --}}
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 p-[2px]">
                                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=fff" class="rounded-full w-full h-full">
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Organizer</p>
                                            <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Active
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({ duration: 800, once: true, offset: 50 });

            // 3D Tilt Effect
            VanillaTilt.init(document.querySelectorAll("[data-tilt]"), {
                max: 8, speed: 400, glare: true, "max-glare": 0.2, scale: 1.02
            });

            // Date Picker (Premium Theme)
            flatpickr("#date-picker", {
                dateFormat: "Y-m-d",
                minDate: "today",
                disableMobile: "true",
                animate: true
            });

            // Time Picker
            flatpickr("#time-picker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                disableMobile: "true"
            });
        });
    </script>
</body>
</html>
