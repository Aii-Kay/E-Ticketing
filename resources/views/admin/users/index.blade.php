<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="User Management - Admin Panel">

    <title>User Management - {{ config('app.name', 'EventTicket') }}</title>

    {{-- =======================================================================
         1. ASSETS & LIBRARIES
    ======================================================================= --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;700;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- =======================================================================
         2. CUSTOM STYLES (Celestial Glass Theme)
    ======================================================================= --}}
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; overflow-x: hidden; }
        h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* Aurora Background */
        .aurora-bg { position: fixed; inset: 0; z-index: -1; background: #ffffff; overflow: hidden; }
        .orb { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.5; mix-blend-mode: multiply; animation: float 25s infinite ease-in-out; }
        .orb-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #c7d2fe; }
        .orb-2 { bottom: -20%; right: -20%; width: 60vw; height: 60vw; background: #bae6fd; animation-delay: -5s; }
        .orb-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: #fbcfe8; opacity: 0.4; animation-delay: -10s; }
        .noise-texture { position: absolute; inset: 0; opacity: 0.4; pointer-events: none; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.1'/%3E%3C/svg%3E"); }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(30px, -30px) scale(1.1); } }

        /* Glass Sidebar */
        .glass-sidebar { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border-right: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 4px 0 24px rgba(0, 0, 0, 0.02); }

        /* Stats Card (Transparent) */
        .stat-card-glass {
            background: rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 1.5rem;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            transition: all 0.4s ease;
        }
        .stat-card-glass:hover {
            background: rgba(255, 255, 255, 0.8);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -10px rgba(99, 102, 241, 0.2);
            border-color: #fff;
        }

        /* User List Card (Floating Row) */
        .user-card-row {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 1.25rem;
            padding: 1rem 1.5rem;
            margin-bottom: 0.75rem;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            display: grid;
            grid-template-columns: 80px 2fr 1.5fr 1fr 1fr 120px;
            align-items: center;
            gap: 1rem;
        }
        .user-card-row:hover {
            transform: scale(1.01) translateY(-2px);
            background: #ffffff;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
            border-color: #a5b4fc;
            z-index: 10;
        }

        /* Role Badges with Glow */
        .badge-glow {
            padding: 0.4rem 1rem; border-radius: 99px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .badge-admin { background: linear-gradient(135deg, #f3e8ff, #d8b4fe); color: #7e22ce; }
        .badge-organizer { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #4338ca; }
        .badge-user { background: linear-gradient(135deg, #f1f5f9, #e2e8f0); color: #475569; }

        /* Action Buttons */
        .action-btn {
            width: 38px; height: 38px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            background: #fff; border: 1px solid #e2e8f0; color: #64748b;
            transition: all 0.2s;
        }
        .action-btn:hover { transform: scale(1.15); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .btn-approve:hover { color: #10b981; border-color: #10b981; }
        .btn-reject:hover { color: #ef4444; border-color: #ef4444; }
        .btn-delete:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

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
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">AD</div>
                <div class="flex flex-col"><span class="font-display font-bold text-xl tracking-tight text-slate-900">Event<span class="text-indigo-600">Ticket</span></span><span class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Administrator</span></div>
            </div>
        </div>

        <div class="flex-1 px-4 py-8 space-y-1.5 overflow-y-auto">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Main Control</p>
            <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="ph-duotone ph-squares-four text-xl"></i> Dashboard</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link active"><i class="ph-duotone ph-users text-xl"></i> User Management</a>
            <a href="{{ route('admin.events.index') }}" class="nav-link"><i class="ph-duotone ph-calendar-plus text-xl"></i> Kelola Event</a>
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
                <span class="font-bold text-lg text-slate-900">User Manager</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="p-2 rounded-lg text-rose-500 hover:bg-rose-50"><i class="ph-bold ph-sign-out text-2xl"></i></button></form>
        </header>

        <div class="p-6 lg:p-12 space-y-12">

            {{-- A. Header & Stats --}}
            <div class="space-y-8" data-aos="fade-down">
                <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900 mb-2">
                            User <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Database</span>
                        </h1>
                        <p class="text-slate-500 text-lg">Kelola ekosistem pengguna Anda dengan kendali penuh.</p>
                    </div>

                    {{-- Search Input (Floating) --}}
                    <div class="relative w-full md:w-80 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <i class="ph-bold ph-magnifying-glass text-xl"></i>
                        </div>
                        <input type="text" placeholder="Cari nama atau email..." class="w-full bg-white/80 border border-white rounded-2xl pl-12 pr-4 py-3.5 text-sm font-medium focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all shadow-lg shadow-indigo-500/5 placeholder:text-slate-400 backdrop-blur-sm">
                    </div>
                </div>

                {{-- Stats Grid (Glass Cards) --}}
                @php
                    $totalUsers = $users->count();
                    $pendingUsers = $users->where('status', 'pending')->count();
                    $organizers = $users->where('role', 'organizer')->count();
                    $admins = $users->where('role', 'admin')->count();
                @endphp
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="stat-card-glass group" data-tilt data-tilt-max="5">
                        <div class="flex justify-between items-start mb-2">
                            <div class="p-2 bg-blue-100 rounded-lg text-blue-600"><i class="ph-duotone ph-users-three text-xl"></i></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total</span>
                        </div>
                        <p class="text-3xl font-mono-num font-bold text-slate-900">{{ $totalUsers }}</p>
                        <p class="text-xs text-slate-500">Pengguna Terdaftar</p>
                    </div>
                    <div class="stat-card-glass group border-amber-200 bg-amber-50/30" data-tilt data-tilt-max="5">
                        <div class="flex justify-between items-start mb-2">
                            <div class="p-2 bg-amber-100 rounded-lg text-amber-600"><i class="ph-duotone ph-clock-countdown text-xl"></i></div>
                            <span class="text-[10px] font-bold text-amber-600 uppercase tracking-widest">Action</span>
                        </div>
                        <p class="text-3xl font-mono-num font-bold text-slate-900">{{ $pendingUsers }}</p>
                        <p class="text-xs text-slate-500">Menunggu Approval</p>
                    </div>
                    <div class="stat-card-glass group" data-tilt data-tilt-max="5">
                        <div class="flex justify-between items-start mb-2">
                            <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600"><i class="ph-duotone ph-briefcase text-xl"></i></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Partners</span>
                        </div>
                        <p class="text-3xl font-mono-num font-bold text-slate-900">{{ $organizers }}</p>
                        <p class="text-xs text-slate-500">Organizer Aktif</p>
                    </div>
                    <div class="stat-card-glass group" data-tilt data-tilt-max="5">
                        <div class="flex justify-between items-start mb-2">
                            <div class="p-2 bg-purple-100 rounded-lg text-purple-600"><i class="ph-duotone ph-shield-check text-xl"></i></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">System</span>
                        </div>
                        <p class="text-3xl font-mono-num font-bold text-slate-900">{{ $admins }}</p>
                        <p class="text-xs text-slate-500">Administrator</p>
                    </div>
                </div>
            </div>

            {{-- B. Floating User List --}}
            <div class="space-y-6" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between px-6 pb-2 text-xs font-bold text-slate-400 uppercase tracking-widest opacity-70">
                    <div class="w-20">ID System</div>
                    <div class="flex-1">Profile User</div>
                    <div class="w-1/4 hidden md:block">Role Access</div>
                    <div class="w-32 hidden sm:block">Status</div>
                    <div class="w-32 text-center">Action</div>
                </div>

                <div class="space-y-3">
                    @forelse ($users as $user)
                        @php
                            $roleClass = match($user->role) {
                                'admin'     => 'badge-admin',
                                'organizer' => 'badge-organizer',
                                default     => 'badge-user',
                            };
                            $statusColor = match($user->status) {
                                'approved' => 'text-emerald-600 bg-emerald-100/50 border-emerald-200',
                                'pending'  => 'text-amber-600 bg-amber-100/50 border-amber-200',
                                'rejected' => 'text-rose-600 bg-rose-100/50 border-rose-200',
                                default    => 'text-slate-500 bg-slate-100 border-slate-200'
                            };
                        @endphp

                        <div class="user-card-row group">
                            {{-- ID --}}
                            <div class="font-mono-num text-slate-400 text-sm font-bold">#{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</div>

                            {{-- Profile --}}
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <div class="w-12 h-12 rounded-full p-0.5 bg-gradient-to-tr from-slate-200 to-white shadow-sm">
                                        <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=f1f5f9&color=475569&bold=true" class="w-full h-full rounded-full object-cover">
                                    </div>
                                    @if($user->status === 'approved')
                                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></div>
                                    @elseif($user->status === 'pending')
                                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-amber-500 border-2 border-white rounded-full animate-pulse"></div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm group-hover:text-indigo-600 transition-colors">{{ $user->name }}</h4>
                                    <p class="text-xs text-slate-500 font-medium">{{ $user->email }}</p>
                                </div>
                            </div>

                            {{-- Role --}}
                            <div class="hidden md:block">
                                <span class="badge-glow {{ $roleClass }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>

                            {{-- Status --}}
                            <div class="hidden sm:block">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold border {{ $statusColor }}">
                                    {{ ucfirst($user->status ?? 'Active') }}
                                </span>
                            </div>

                            {{-- Actions --}}
                            <div class="flex justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                @if ($user->role === 'organizer' && $user->status === 'pending')
                                    <form action="{{ route('admin.users.approve', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="action-btn btn-approve" title="Approve Organizer">
                                            <i class="ph-bold ph-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" class="js-confirm-form" data-title="Tolak Organizer?" data-text="User tidak akan bisa membuat event.">
                                        @csrf
                                        <button type="submit" class="action-btn btn-reject" title="Reject Organizer">
                                            <i class="ph-bold ph-x"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="js-confirm-form" data-title="Hapus User?" data-text="Data user dan event terkait akan dihapus permanen.">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" title="Hapus User">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center bg-white/50 rounded-[2rem] border border-dashed border-slate-300">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                                <i class="ph-duotone ph-users-three text-3xl"></i>
                            </div>
                            <h3 class="font-bold text-slate-900">Belum Ada User</h3>
                            <p class="text-sm text-slate-500">Database pengguna masih kosong.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination (If Available) --}}
                @if(method_exists($users, 'hasPages') && $users->hasPages())
                    <div class="pt-8 flex justify-center">
                        <div class="bg-white/60 backdrop-blur px-6 py-3 rounded-2xl shadow-lg shadow-indigo-500/5 border border-white">
                            {{ $users->links() }}
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

            // 3D Tilt Effect
            VanillaTilt.init(document.querySelectorAll(".stat-card-glass"), {
                max: 15, speed: 400, glare: true, "max-glare": 0.2, scale: 1.05
            });

            // SweetAlert Logic
            const confirmForms = document.querySelectorAll('.js-confirm-form');
            confirmForms.forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    Swal.fire({
                        title: form.dataset.title,
                        text: form.dataset.text,
                        icon: 'warning',
                        background: '#ffffff',
                        color: '#1e293b',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
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
