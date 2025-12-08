<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar Akun - EventTicket</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Role Card Selection Style */
        .role-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .role-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.15);
            border-color: #818CF8;
        }
        .role-card.selected {
            border-color: #4F46E5;
            background-color: #EEF2FF;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.1);
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
    </style>
</head>
<body class="antialiased bg-white text-slate-900">

    <div class="min-h-screen w-full flex overflow-hidden" x-data="{
        role: '{{ old('role', 'registered_user') }}',
        showPassword: false,
        showConfirmPassword: false,
        isLoading: false
    }">

        {{-- =======================================================================
             BAGIAN KIRI: FORMULIR (Full Height)
        ======================================================================= --}}
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-4 sm:px-6 lg:px-20 xl:px-24 bg-white relative z-10 h-screen overflow-y-auto">
            <div class="w-full max-w-sm mx-auto py-10 lg:py-0">

                {{-- Header --}}
                <div data-aos="fade-down" data-aos-duration="800">
                    <a href="/" class="flex items-center gap-2 mb-8 group w-fit">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30 group-hover:rotate-12 transition-transform duration-300">
                            ET
                        </div>
                        <span class="text-2xl font-bold text-slate-900 tracking-tight">Event<span class="text-indigo-600">Ticket</span></span>
                    </a>

                    <h2 class="mt-2 text-3xl font-extrabold text-slate-900 tracking-tight">
                        Buat Akun Baru
                    </h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Bergabunglah dengan komunitas event terbesar.
                        <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                            Sudah punya akun?
                        </a>
                    </p>
                </div>

                {{-- Form Section --}}
                <div class="mt-8" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                    <form method="POST" action="{{ route('register') }}" @submit="isLoading = true">
                        @csrf

                        {{-- Input: Name --}}
                        <div class="space-y-1">
                            <label for="name" class="block text-sm font-bold text-slate-700">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}"
                                    class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all duration-200"
                                    placeholder="John Doe">
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        {{-- Input: Email --}}
                        <div class="mt-5 space-y-1">
                            <label for="email" class="block text-sm font-bold text-slate-700">Alamat Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                </div>
                                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                    class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all duration-200"
                                    placeholder="nama@email.com">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        {{-- Input: Role Selection (Interactive Cards) --}}
                        <div class="mt-6">
                            <label class="block text-sm font-bold text-slate-700 mb-3">Saya ingin mendaftar sebagai:</label>

                            <input type="hidden" name="role" x-model="role">

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                {{-- Option: User --}}
                                <div @click="role = 'registered_user'"
                                     :class="{ 'selected': role === 'registered_user', 'border-slate-200': role !== 'registered_user' }"
                                     class="role-card relative rounded-xl border-2 p-4 cursor-pointer flex flex-col items-start gap-2 bg-white">

                                    <div x-show="role === 'registered_user'" class="absolute top-3 right-3 text-indigo-600 animate__animated animate__zoomIn animate__faster">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </div>

                                    <div class="p-2 rounded-lg bg-blue-50 text-blue-600 mb-1">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900">Pengunjung</h3>
                                    <p class="text-xs text-slate-500 leading-snug">
                                        Beli tiket, simpan event, dan nikmati acara.
                                    </p>
                                </div>

                                {{-- Option: Organizer --}}
                                <div @click="role = 'organizer'"
                                     :class="{ 'selected': role === 'organizer', 'border-slate-200': role !== 'organizer' }"
                                     class="role-card relative rounded-xl border-2 p-4 cursor-pointer flex flex-col items-start gap-2 bg-white">

                                    <div x-show="role === 'organizer'" class="absolute top-3 right-3 text-indigo-600 animate__animated animate__zoomIn animate__faster">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </div>

                                    <div class="p-2 rounded-lg bg-purple-50 text-purple-600 mb-1">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900">Organizer</h3>
                                    <p class="text-xs text-slate-500 leading-snug">
                                        Buat event dan jual tiket.
                                    </p>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        {{-- Input: Password --}}
                        <div class="mt-5 space-y-1">
                            <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="new-password"
                                    class="block w-full pl-10 pr-10 py-3 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all duration-200"
                                    placeholder="••••••••">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="showPassword = !showPassword">
                                    <svg x-show="!showPassword" class="h-5 w-5 text-slate-400 hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="showPassword" class="h-5 w-5 text-slate-400 hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>

                        {{-- Input: Confirm Password --}}
                        <div class="mt-5 space-y-1">
                            <label for="password_confirmation" class="block text-sm font-bold text-slate-700">Konfirmasi Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" required autocomplete="new-password"
                                    class="block w-full pl-10 pr-10 py-3 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all duration-200"
                                    placeholder="••••••••">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="showConfirmPassword = !showConfirmPassword">
                                    <svg x-show="!showConfirmPassword" class="h-5 w-5 text-slate-400 hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="showConfirmPassword" class="h-5 w-5 text-slate-400 hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                        </div>

                        {{-- Submit Button --}}
                        <div class="mt-8">
                            <button type="submit" :disabled="isLoading"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0">
                                <svg x-show="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="isLoading ? 'Memproses...' : 'Daftar Sekarang'"></span>
                            </button>
                        </div>

                        {{-- Social Login Mockup --}}
                        <div class="mt-6">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-slate-200"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-slate-500">Atau daftar dengan</span>
                                </div>
                            </div>
                            <div class="mt-6 grid grid-cols-2 gap-3">
                                <div>
                                    <a href="#" class="w-full inline-flex justify-center py-2.5 px-4 border border-slate-300 rounded-xl shadow-sm bg-white text-sm font-medium text-slate-500 hover:bg-slate-50 transition-colors">
                                        Google
                                    </a>
                                </div>
                                <div>
                                    <a href="#" class="w-full inline-flex justify-center py-2.5 px-4 border border-slate-300 rounded-xl shadow-sm bg-white text-sm font-medium text-slate-500 hover:bg-slate-50 transition-colors">
                                        GitHub
                                    </a>
                                </div>
                            </div>
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
                     src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070&auto=format&fit=crop"
                     alt="Concert Crowd">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-indigo-900/80 to-indigo-600/40 mix-blend-multiply"></div>
            </div>

            {{-- Animated Decoration --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

            {{-- Content Overlay --}}
            <div class="relative z-10 h-full flex flex-col justify-end p-16 text-white pb-32">
                <div class="mb-10" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <blockquote class="text-3xl font-medium leading-relaxed mb-6">
                        "EventTicket mengubah cara saya menikmati akhir pekan. Pemesanan tiketnya sangat cepat dan tanpa ribet!"
                    </blockquote>
                </div>

                {{-- Glass Card Decoration --}}
                <div class="absolute bottom-12 right-12 w-72 p-5 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl transform rotate-3 hover:rotate-0 transition-all duration-500">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-rose-500 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 3-2 3-2zm0 0c0 1.105 1.343 2 3 2s3-.895 3-2-3-2-3-2z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs text-white/70">Sedang Trending</div>
                            <div class="font-bold text-sm">Jazz Festival 2024</div>
                        </div>
                    </div>
                    <div class="w-full bg-white/20 rounded-full h-1.5 mb-1">
                        <div class="bg-rose-400 h-1.5 rounded-full" style="width: 85%"></div>
                    </div>
                    <div class="text-[10px] text-right text-white/80">85% Tiket Terjual</div>
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
