<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketTypeController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi tanpa mewajibkan description
        $validated = $request->validate([
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'name'     => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'], // sekarang boleh kosong / tidak dikirim
            'price'    => ['required', 'numeric', 'min:0'],
            'quota'    => ['required', 'integer', 'min:0'],
        ]);

        // Pastikan event milik organizer yang sedang login (kecuali admin, kalau kamu izinkan)
        $event = Event::findOrFail($validated['event_id']);

        if ($user->role === 'organizer' && $event->created_by !== $user->id) {
            abort(403, 'Anda hanya boleh membuat ticket type untuk event milik Anda sendiri.');
        }

        // Jika description tidak ada, isi dengan string kosong
        $validated['description'] = $validated['description'] ?? '';

        // Buat TicketType baru
        TicketType::create($validated);

        // Redirect balik (bisa ke halaman sebelumnya)
        return back()->with('success', 'Ticket type berhasil dibuat.');
    }
}
