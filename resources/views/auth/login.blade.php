<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Masuk - EventTicket</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Smooth Transition for Inputs */
        input:focus {
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        /* Animation Keyframes */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        /* Custom Scrollbar for form side */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="antialiased bg-white text-slate-900">

    <div class="min-h-screen w-full flex overflow-hidden" x-data="{
        showPassword: false,
        isLoading: false
    }">

        {{-- =======================================================================
             BAGIAN KIRI: FORMULIR LOGIN
        ======================================================================= --}}
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-4 sm:px-6 lg:px-20 xl:px-24 bg-white relative z-10 h-screen overflow-y-auto custom-scrollbar">
            <div class="w-full max-w-sm mx-auto py-10 lg:py-0">

                {{-- Header / Logo --}}
                <div data-aos="fade-down" data-aos-duration="800">
                    <a href="/" class="flex items-center gap-2 mb-8 group w-fit">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30 group-hover:rotate-12 transition-transform duration-300">
                            ET
                        </div>
                        <span class="text-2xl font-bold text-slate-900 tracking-tight">Event<span class="text-indigo-600">Ticket</span></span>
                    </a>

                    <h2 class="mt-2 text-3xl font-extrabold text-slate-900 tracking-tight">
                        Selamat Datang Kembali!
                    </h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Silakan masuk untuk mengakses tiket dan event favorit Anda.
                    </p>
                </div>

                {{-- Session Status (Success Message) --}}
                @if (session('status'))
                    <div class="mb-4 mt-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium animate__animated animate__fadeIn">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Form Section --}}
                <div class="mt-8" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                    <form method="POST" action="{{ route('login') }}" @submit="isLoading = true">
                        @csrf

                        {{-- Input: Email --}}
                        <div class="space-y-1">
                            <label for="email" class="block text-sm font-bold text-slate-700">Alamat Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <input id="email" name="email" type="email" autocomplete="username" required value="{{ old('email') }}"
                                    class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all duration-200"
                                    placeholder="nama@email.com">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 space-y-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Input: Password --}}
                        <div class="mt-5 space-y-1">
                            <div class="flex items-center justify-between">
                                <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password"
                                    class="block w-full pl-10 pr-10 py-3 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all duration-200"
                                    placeholder="••••••••">

                                {{-- Show/Hide Toggle --}}
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="showPassword = !showPassword">
                                    <svg x-show="!showPassword" class="h-5 w-5 text-slate-400 hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="showPassword" class="h-5 w-5 text-slate-400 hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </div>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 space-y-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Remember Me & Forgot Password --}}
                        <div class="flex items-center justify-between mt-6">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900 cursor-pointer">
                                    Ingat saya
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                                        Lupa password?
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- Submit Button --}}
                        <div class="mt-8">
                            <button type="submit" :disabled="isLoading"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-70 disabled:cursor-not-allowed">

                                {{-- Loading Spinner --}}
                                <svg x-show="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>

                                <span x-text="isLoading ? 'Memproses...' : 'Masuk Sekarang'"></span>
                            </button>
                        </div>

                        {{-- Divider --}}
                        <div class="mt-8">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-slate-200"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-slate-500">Atau masuk dengan</span>
                                </div>
                            </div>

                            {{-- Social Login Buttons --}}
                            <div class="mt-6 grid grid-cols-2 gap-3">
                                <div>
                                    <a href="#" class="w-full inline-flex justify-center py-2.5 px-4 border border-slate-300 rounded-xl shadow-sm bg-white text-sm font-medium text-slate-500 hover:bg-slate-50 transition-colors">
                                        <svg class="h-5 w-5" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.0003 20.45c-4.6667 0-8.45-3.7833-8.45-8.45 0-4.6667 3.7833-8.45 8.45-8.45 4.6667 0 8.45 3.7833 8.45 8.45 0 4.6667-3.7833 8.45-8.45 8.45zm0-18.9C6.2378 1.55 1.55 6.2378 1.55 12.0003c0 5.7625 4.6878 10.4503 10.4503 10.4503 5.7625 0 10.4503-4.6878 10.4503-10.4503C22.4506 6.2378 17.7628 1.55 12.0003 1.55z" />
                                            <path d="M12.42 16.59c-.93 0-1.74-.63-1.95-1.52l-2.73-10.89c-.06-.23.11-.46.35-.46h1.56c.2 0 .38.13.44.33l1.89 7.57 1.63-7.56c.05-.21.23-.34.44-.34h1.59c.23 0 .41.22.36.45l-2.3 10.91c-.2.88-1.01 1.51-1.94 1.51h-.34z"/>
                                        </svg>
                                        <span class="ml-2">Google</span>
                                    </a>
                                </div>
                                <div>
                                    <a href="#" class="w-full inline-flex justify-center py-2.5 px-4 border border-slate-300 rounded-xl shadow-sm bg-white text-sm font-medium text-slate-500 hover:bg-slate-50 transition-colors">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-2">GitHub</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Register Link --}}
                        <div class="mt-8 text-center">
                            <p class="text-sm text-slate-600">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                                    Daftar Gratis Sekarang
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- =======================================================================
             BAGIAN KANAN: GAMBAR VISUAL (Hidden on Mobile)
        ======================================================================= --}}
        <div class="hidden lg:block relative flex-1 bg-slate-900 h-screen overflow-hidden">
            {{-- Background Image --}}
            <div class="absolute inset-0 w-full h-full">
                <img class="absolute inset-0 h-full w-full object-cover opacity-60"
                     src="https://images.unsplash.com/photo-1533174072545-e8d4aa97edf9?q=80&w=2070&auto=format&fit=crop"
                     alt="Music Festival Night">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-indigo-900/70 to-purple-900/40 mix-blend-multiply"></div>
            </div>

            {{-- Animated Decoration --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-1/2 right-12 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-24 left-24 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

            {{-- Content Overlay --}}
            <div class="relative z-10 h-full flex flex-col justify-end p-16 text-white pb-24">
                <div class="mb-10" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <h2 class="text-4xl font-extrabold leading-tight mb-4">
                        Akses Ribuan Event <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Dalam Genggamanmu</span>
                    </h2>
                    <p class="text-indigo-100 text-lg max-w-lg leading-relaxed">
                        Login sekarang untuk mengelola tiket, melihat riwayat transaksi, dan mendapatkan rekomendasi event spesial hanya untukmu.
                    </p>
                </div>

                {{-- Floating Glass Card --}}
                <div class="absolute bottom-12 right-12 w-80 p-5 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl transform -rotate-2 hover:rotate-0 transition-all duration-500 group cursor-pointer">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="relative w-14 h-14 rounded-xl overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=150&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div>
                            <div class="text-[10px] uppercase tracking-wider text-indigo-300 font-bold mb-1">Coming Soon</div>
                            <div class="font-bold text-white text-base">Electronic Dreams</div>
                            <div class="text-xs text-white/70">12 Des 2025 • Jakarta</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-white/10">
                        <div class="flex -space-x-2">
                            <img class="w-6 h-6 rounded-full border border-white/50" src="https://i.pravatar.cc/150?img=12">
                            <img class="w-6 h-6 rounded-full border border-white/50" src="https://i.pravatar.cc/150?img=23">
                            <img class="w-6 h-6 rounded-full border border-white/50" src="https://i.pravatar.cc/150?img=33">
                        </div>
                        <div class="text-xs font-bold text-white bg-indigo-600 px-3 py-1 rounded-full">Book Now</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Initialize Scripts --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof AOS !== 'undefined') {
                AOS.init();
            }
        });
    </script>
</body>
</html>
