<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Event - {{ $event->name }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Outfit:wght@500;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .font-heading { font-family: 'Outfit', sans-serif; }
        .font-mono-num { font-family: 'Space Grotesk', monospace; }

        /* Animated Gradient Background */
        .aurora-bg {
            position: fixed; inset: 0; z-index: -1; background: #fff;
        }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.3;
            animation: float 20s infinite ease-in-out;
        }
        .orb-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #818cf8; }
        .orb-2 { bottom: -20%; right: -20%; width: 60vw; height: 60vw; background: #c084fc; animation-delay: -5s; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(20px, -20px); }
        }

        /* Glass Panel */
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 32px rgba(0,0,0,0.05);
        }

        /* Ticket Radio Card */
        .ticket-radio {
            transition: all 0.2s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .ticket-radio:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }
        .ticket-radio.selected {
            background-color: #eef2ff;
            border-color: #6366f1;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.1);
        }
        .ticket-radio.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            filter: grayscale(1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col"
      x-data="bookingForm({ ticketTypes: {{ $event->ticketTypes->toJson() }} })">

    <div class="aurora-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <nav class="absolute top-0 w-full z-50 p-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                <span class="font-bold text-slate-700 group-hover:text-slate-900 transition-colors">Kembali</span>
            </a>
            <div class="flex items-center gap-3 bg-white/60 backdrop-blur-md px-4 py-2 rounded-full border border-white/50 shadow-sm">
                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 p-[2px]">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=fff&color=4f46e5" class="rounded-full w-full h-full object-cover">
                </div>
                <div class="text-xs">
                    <p class="font-bold text-slate-900">{{ Auth::user()->name }}</p>
                    <p class="text-slate-500">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center py-24 px-4 sm:px-6">
        <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- LEFT: EVENT DETAILS (3 Cols) --}}
            <div class="lg:col-span-3 space-y-6" data-aos="fade-up">

                {{-- Event Header Card --}}
                <div class="glass-panel rounded-[2rem] overflow-hidden p-2">
                    <div class="relative h-64 md:h-80 rounded-[1.5rem] overflow-hidden group">
                        <img src="{{ $event->image ? asset($event->image) : 'https://source.unsplash.com/random/800x600?concert&sig='.$event->id }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                             alt="{{ $event->name }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>

                        <div class="absolute bottom-6 left-6 text-white">
                            @if($event->category)
                                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/20 text-xs font-bold uppercase tracking-wider mb-3 inline-block">
                                    {{ $event->category->name }}
                                </span>
                            @endif
                            <h1 class="text-3xl md:text-4xl font-heading font-bold leading-tight mb-2">{{ $event->name }}</h1>
                            <div class="flex items-center gap-4 text-sm text-slate-200">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $event->location }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 00-2 2z"/></svg>
                                    {{ \Carbon\Carbon::parse($event->date)->format('d F Y') }} • {{ $event->time }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description & Organizer --}}
                <div class="glass-panel rounded-[2rem] p-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-4 font-heading">Tentang Event</h3>
                    <p class="text-slate-600 leading-relaxed text-sm md:text-base mb-8">
                        {{ $event->description }}
                    </p>

                    <div class="flex items-center gap-4 p-4 bg-white/50 rounded-2xl border border-slate-100">
                        <div class="h-12 w-12 rounded-full bg-slate-200 overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name=EO&background=random" alt="EO" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Diselenggarakan Oleh</p>
                            <p class="text-slate-900 font-bold">Event Organizer Profesional</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: CHECKOUT FORM (2 Cols) --}}
            <div class="lg:col-span-2 relative" data-aos="fade-left" data-aos-delay="100">
                <div class="glass-panel rounded-[2rem] p-6 md:p-8 sticky top-24">
                    <div class="mb-6 border-b border-slate-100 pb-4">
                        <h2 class="text-2xl font-bold text-slate-900 font-heading">Pilih Tiket</h2>
                        <p class="text-sm text-slate-500">Pilih jenis tiket yang Anda inginkan.</p>
                    </div>

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-rose-50 border border-rose-100 text-rose-600 rounded-xl p-4 text-sm animate__animated animate__headShake">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.bookings.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        {{-- Ticket Type Selection --}}
                        <div class="space-y-3">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block ml-1">Kategori Tiket</label>

                            @foreach($event->ticketTypes as $type)
                                <div @click="selectTicket({{ $type->id }}, {{ $type->price }}, {{ $type->quota }})"
                                     class="ticket-radio relative rounded-2xl p-4 border bg-white flex justify-between items-center"
                                     :class="{
                                        'selected': selectedTicketId == {{ $type->id }},
                                        'disabled': {{ $type->quota }} <= 0
                                     }">

                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full border border-slate-300 flex items-center justify-center transition-colors"
                                             :class="selectedTicketId == {{ $type->id }} ? 'bg-indigo-600 border-indigo-600' : ''">
                                            <div class="w-2 h-2 rounded-full bg-white" x-show="selectedTicketId == {{ $type->id }}"></div>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $type->name }}</p>
                                            <p class="text-xs text-slate-500">Sisa Kuota: {{ $type->quota }}</p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="font-mono-num font-bold text-slate-900">Rp {{ number_format($type->price, 0, ',', '.') }}</p>
                                        @if($type->quota <= 0)
                                            <span class="text-[10px] text-rose-500 font-bold uppercase">Habis</span>
                                        @endif
                                    </div>

                                    {{-- Hidden Radio Input for Form --}}
                                    <input type="radio" name="ticket_type_id" value="{{ $type->id }}" class="hidden"
                                           x-model="selectedTicketId" {{ $type->quota <= 0 ? 'disabled' : '' }}>
                                </div>
                            @endforeach
                        </div>

                        {{-- Quantity --}}
                        <div class="space-y-3" x-show="selectedTicketId" x-transition>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block ml-1">Jumlah Tiket</label>
                            <div class="flex items-center gap-4 bg-white p-2 rounded-xl border border-slate-200 w-fit">
                                <button type="button" @click="decrement" class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-colors disabled:opacity-50" :disabled="quantity <= 1">-</button>
                                <input type="number" name="quantity" x-model="quantity" class="w-12 text-center border-none p-0 text-slate-900 font-bold focus:ring-0" min="1" readonly>
                                <button type="button" @click="increment" class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 hover:bg-indigo-200 transition-colors" :disabled="quantity >= maxQuota">+</button>
                            </div>
                        </div>

                        {{-- Summary & Total --}}
                        <div class="bg-slate-50 rounded-2xl p-5 space-y-3 border border-slate-100">
                            <div class="flex justify-between text-sm text-slate-500">
                                <span>Harga Satuan</span>
                                <span class="font-mono-num" x-text="formatRupiah(ticketPrice)"></span>
                            </div>
                            <div class="flex justify-between text-sm text-slate-500 border-b border-slate-200 pb-3">
                                <span>Jumlah</span>
                                <span x-text="quantity + 'x'"></span>
                            </div>
                            <div class="flex justify-between items-center pt-1">
                                <span class="font-bold text-slate-900">Total Bayar</span>
                                <span class="font-mono-num text-xl font-bold text-indigo-600" x-text="formatRupiah(totalPrice)"></span>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                                :disabled="!selectedTicketId"
                                class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl shadow-lg shadow-slate-900/20 transition-all transform hover:-translate-y-1 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2 group">
                            <span>Konfirmasi & Bayar</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>

                        <p class="text-xs text-center text-slate-400 leading-tight">
                            Dengan melanjutkan, Anda menyetujui <a href="#" class="text-indigo-500 hover:underline">Syarat & Ketentuan</a> kami. <br>Booking akan berstatus <strong>Pending</strong> hingga disetujui.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </main>

    {{-- Script Logic (Alpine.js) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({ duration: 800, once: true, offset: 50 });
        });

        function bookingForm(data) {
            return {
                selectedTicketId: null,
                ticketPrice: 0,
                quantity: 1,
                maxQuota: 1,

                selectTicket(id, price, quota) {
                    if (quota <= 0) return; // Prevent selection if sold out
                    this.selectedTicketId = id;
                    this.ticketPrice = price;
                    this.maxQuota = quota;
                    this.quantity = 1; // Reset quantity on type change
                },

                increment() {
                    if (this.quantity < this.maxQuota) this.quantity++;
                },

                decrement() {
                    if (this.quantity > 1) this.quantity--;
                },

                get totalPrice() {
                    return this.ticketPrice * this.quantity;
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                }
            }
        }
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</body>
</html>
