<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Status Organizer
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        Pengajuan Organizer Sedang Diproses
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Terima kasih, {{ Auth::user()->name }}. Kamu mendaftar sebagai
                        <span class="font-semibold">Organizer</span>. Saat ini status akun organizer kamu:
                        <span class="font-semibold">{{ Auth::user()->status }}</span>.
                    </p>

                    <p class="text-sm text-gray-600 mb-4">
                        Admin akan meninjau pengajuanmu terlebih dahulu. Jika disetujui, kamu akan bisa mengakses
                        dashboard organizer dan mengelola event. Kamu juga akan mendapatkan notifikasi ketika
                        pengajuanmu di-approve atau di-reject.
                    </p>

                    <div class="flex flex-wrap gap-3 mt-4 text-sm">
                        <a href="{{ route('user.dashboard') }}"
                           class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">
                            Kembali ke User Dashboard
                        </a>

                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="px-4 py-2 rounded-md border border-red-500 text-red-600 hover:bg-red-50">
                            Logout
                        </a>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
