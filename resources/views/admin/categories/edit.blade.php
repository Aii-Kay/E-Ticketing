<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Edit Category - Admin Panel">

    <title>Edit Category - {{ config('app.name', 'EventTicket') }}</title>

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

    {{-- =======================================================================
         2. CUSTOM STYLES (Category Forge: Etheric Edition)
    ======================================================================= --}}
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; overflow-x: hidden; }
        h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* --- Dynamic Background --- */
        .aurora-bg { position: fixed; inset: 0; z-index: -1; background: #ffffff; overflow: hidden; }
        .orb { position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.6; mix-blend-mode: multiply; animation: float 20s infinite ease-in-out; }
        .orb-1 { top: -20%; left: -20%; width: 70vw; height: 70vw; background: #a5b4fc; }
        .orb-2 { bottom: -20%; right: -20%; width: 70vw; height: 70vw; background: #67e8f9; animation-delay: -5s; }
        .orb-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: #f9a8d4; opacity: 0.4; animation-delay: -10s; }

        .grid-pattern {
            position: fixed; inset: 0; z-index: -1; opacity: 0.3;
            background-image: linear-gradient(#e2e8f0 1px, transparent 1px), linear-gradient(90deg, #e2e8f0 1px, transparent 1px);
            background-size: 50px 50px;
            mask-image: radial-gradient(circle at center, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 80%);
        }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(30px, -30px) scale(1.1); } }

        /* --- Glass Sidebar --- */
        .glass-sidebar { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border-right: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 4px 0 30px rgba(0, 0, 0, 0.03); }

        /* --- Form Panel --- */
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px) saturate(120%);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 2rem;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        /* --- Input Styles --- */
        .input-group { position: relative; margin-bottom: 1.5rem; }
        .glass-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid #cbd5e1;
            border-radius: 1.25rem;
            padding: 1.2rem 1.2rem 1.2rem 3.5rem; /* Space for icon */
            color: #1e293b;
            font-size: 1rem; font-weight: 500;
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
            position: absolute; left: 1.25rem; top: 1.35rem;
            color: #94a3b8; font-size: 1.5rem; pointer-events: none; transition: 0.3s;
        }
        .glass-input:focus + .input-icon { color: #6366f1; transform: scale(1.1); }
        label { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 0.6rem; display: block; margin-left: 0.8rem; }

        /* --- Preview Card (Holographic) --- */
        .preview-container { position: sticky; top: 2rem; perspective: 1000px; }
        .etheric-card-preview {
            position: relative; overflow: hidden;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 2.5rem;
            box-shadow: 0 30px 60px -15px rgba(99, 102, 241, 0.25);
            transform-style: preserve-3d;
            transition: all 0.5s ease;
        }
        .card-hologram {
            height: 220px; width: 100%; position: relative; overflow: hidden;
            border-radius: 2.5rem 2.5rem 50% 50% / 2.5rem 2.5rem 20% 20%;
            display: flex; align-items: center; justify-content: center;
            background-color: #0F172A; /* Fallback */
        }
        /* Mesh Gradient for Preview */
        .mesh-preview {
            background-image: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%),
                              radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%),
                              radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            background-color: #6366f1;
        }

        .float-icon {
            font-size: 6rem; color: rgba(255,255,255,0.9);
            filter: drop-shadow(0 15px 30px rgba(0,0,0,0.2));
            transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            mix-blend-mode: overlay;
        }
        .etheric-card-preview:hover .float-icon { transform: scale(1.1) rotate(10deg); }

        /* Buttons */
        .btn-submit {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white; padding: 1.2rem 3rem; border-radius: 1.5rem;
            font-weight: 700; letter-spacing: 0.025em; transition: all 0.4s;
            box-shadow: 0 15px 30px -8px rgba(99, 102, 241, 0.4);
            display: flex; align-items: center; gap: 0.8rem; width: 100%; justify-content: center;
        }
        .btn-submit:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 25px 50px -10px rgba(99, 102, 241, 0.5); }

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
                <span class="font-bold text-lg text-slate-900">Edit Kategori</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="p-2 rounded-lg text-rose-500 hover:bg-rose-50"><i class="ph-bold ph-sign-out text-2xl"></i></button></form>
        </header>

        <div class="p-6 lg:p-12 max-w-7xl mx-auto w-full">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12" data-aos="fade-down">
                <div>
                    <h1 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900 mb-3">
                        Category <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Forge</span>
                    </h1>
                    <p class="text-slate-500 text-lg">Perbarui detail kategori untuk menyempurnakan ekosistem event.</p>
                </div>

                {{-- Back Button --}}
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 rounded-2xl bg-white border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 group">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Galeri
                </a>
            </div>

            {{-- Main Layout (Form & Preview) --}}
            {{-- Gunakan x-data untuk Live Preview --}}
            <form method="POST" action="{{ route('admin.categories.update', $category->id) }}"
                  class="grid grid-cols-1 lg:grid-cols-12 gap-10"
                  x-data="{
                      catName: '{{ old('name', $category->name) }}',
                      catDesc: '{{ old('description', $category->description) }}',
                      catImg: '{{ old('image', $category->image) }}'
                  }">
                @csrf
                @method('PUT')

                {{-- Left Column: Input Form (8 cols) --}}
                <div class="lg:col-span-8 glass-panel p-8 md:p-12" data-aos="fade-up">
                    <h3 class="text-xl font-bold text-slate-900 mb-8 flex items-center gap-2">
                        <i class="ph-duotone ph-pencil-simple-line text-indigo-600"></i> Edit Informasi
                    </h3>

                    {{-- Error Handler --}}
                    @if ($errors->any())
                        <div class="p-5 mb-8 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 text-sm shadow-sm flex gap-3 animate__animated animate__headShake">
                            <i class="ph-fill ph-warning-circle text-xl shrink-0"></i>
                            <div>
                                <p class="font-bold mb-1">Mohon perbaiki:</p>
                                <ul class="list-disc list-inside opacity-90">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                            </div>
                        </div>
                    @endif

                    {{-- 1. Name --}}
                    <div class="input-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="name" x-model="catName" class="glass-input" placeholder="Contoh: Concert, Webinar..." required>
                        <i class="ph-duotone ph-tag input-icon"></i>
                    </div>

                    {{-- 2. Description --}}
                    <div class="input-group">
                        <label>Deskripsi Singkat</label>
                        <textarea name="description" rows="5" x-model="catDesc" class="glass-input leading-relaxed" placeholder="Deskripsikan kategori ini untuk pengguna..." style="padding-left: 1.25rem !important;" required></textarea>
                    </div>

                    {{-- 3. Image URL --}}
                    <div class="input-group">
                        <label>URL Gambar (Opsional)</label>
                        <input type="text" name="image" x-model="catImg" class="glass-input" placeholder="https://example.com/image.jpg">
                        <i class="ph-duotone ph-link input-icon"></i>
                        <p class="text-[10px] text-slate-400 mt-2 ml-1 italic font-medium">*Kosongkan untuk menggunakan pola abstrak default di halaman index.</p>
                    </div>

                    {{-- Action --}}
                    <div class="mt-8">
                        <button type="submit" class="btn-submit">
                            <i class="ph-bold ph-floppy-disk text-xl"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>

                {{-- Right Column: Live Holographic Preview (4 cols) --}}
                <div class="lg:col-span-4 hidden lg:block" data-aos="fade-left" data-aos-delay="200">
                    <div class="preview-container">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 text-center">Live Preview</p>

                        <div class="etheric-card-preview" data-tilt data-tilt-max="10" data-tilt-speed="400">
                            {{-- Visual Header --}}
                            <div class="card-hologram mesh-preview">
                                <template x-if="catImg">
                                    <img :src="catImg" alt="Preview" class="w-full h-full object-cover opacity-90 mix-blend-overlay">
                                </template>
                                <template x-if="!catImg">
                                    <i class="ph-duotone ph-shapes float-icon"></i>
                                </template>

                                <div class="id-badge">ID-{{ str_pad($category->id, 2, '0', STR_PAD_LEFT) }}</div>
                            </div>

                            {{-- Content Body --}}
                            <div class="p-8 flex-1 flex flex-col relative z-20">
                                <h3 class="text-3xl font-display font-extrabold text-slate-900 mb-3 leading-tight" x-text="catName ? catName : 'Judul Kategori'"></h3>
                                <p class="text-sm text-slate-500 line-clamp-4 leading-relaxed font-medium" x-text="catDesc ? catDesc : 'Deskripsi kategori akan muncul di sini saat Anda mengetik...'"></p>

                                <div class="mt-8 pt-6 border-t border-slate-200/50 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active</span>
                                    </div>
                                    <div class="flex gap-2 opacity-30">
                                        <div class="w-8 h-8 rounded-lg bg-slate-300"></div>
                                        <div class="w-8 h-8 rounded-lg bg-slate-300"></div>
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

            VanillaTilt.init(document.querySelectorAll("[data-tilt]"), {
                max: 12, speed: 400, glare: true, "max-glare": 0.2, scale: 1.02
            });
        });
    </script>
</body>
</html>
