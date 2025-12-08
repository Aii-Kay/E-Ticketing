{{-- resources/views/errors/403.blade.php --}}
<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-slate-100">
        <div class="max-w-lg w-full mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">

            {{-- Icon + Kode error --}}
            <div class="flex flex-col items-center space-y-2">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-red-50">
                    <svg class="w-9 h-9 text-red-500" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 9v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M12 16.5h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M3.5 19 12 4l8.5 15H3.5Z" stroke="currentColor" stroke-width="1.6"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <p class="text-xs font-mono uppercase tracking-[0.2em] text-slate-500">
                    Error 403 • Forbidden
                </p>

                <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900 text-center">
                    Akses Ditolak
                </h1>
            </div>

            {{-- Pesan utama --}}
            @php
                $message = $exception?->getMessage() ?? 'Kamu tidak punya izin untuk membuka halaman ini.';
            @endphp

            <div class="text-center space-y-2">
                <p class="text-sm text-slate-700">
                    {{ $message }}
                </p>

                <p class="text-xs text-slate-500">
                    Jika kamu baru mendaftar sebagai <span class="font-semibold">organizer</span>,
                    akunmu akan aktif setelah permintaanmu disetujui oleh admin.
                </p>
            </div>

            {{-- Tombol aksi --}}
            <div class="flex flex-col sm:flex-row justify-center gap-3 pt-2">
                {{-- Logout & ganti akun (menggunakan route /force-logout yang sudah kamu buat) --}}
                <a href="{{ url('/force-logout') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg
                          text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700
                          shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Logout & ganti akun
                </a>

                {{-- Kembali ke beranda / landing page --}}
                <a href="{{ url('/') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg
                          text-sm font-semibold border border-slate-300 text-slate-700
                          bg-white hover:bg-slate-50 focus:outline-none focus:ring-2
                          focus:ring-slate-300 focus:ring-offset-2">
                    Kembali ke beranda
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>
