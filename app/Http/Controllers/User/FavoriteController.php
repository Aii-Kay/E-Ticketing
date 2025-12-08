<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // List semua event favorit user
    public function index(Request $request)
    {
        $user = Auth::user();

        $favorites = Favorite::with('event')
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->get();

        return view('user.favorites.index', [
            'user'      => $user,
            'favorites' => $favorites,
        ]);
    }

    // Tambah ke favorit
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
        ]);

        // Cegah duplikat
        $exists = Favorite::where('user_id', $user->id)
            ->where('event_id', $validated['event_id'])
            ->exists();

        if (! $exists) {
            Favorite::create([
                'user_id'  => $user->id,
                'event_id' => $validated['event_id'],
            ]);
        }

        return back()->with('status', 'Event ditambahkan ke favorit.');
    }

    // Hapus dari favorit
    public function destroy($id)
    {
        $user = Auth::user();

        $favorite = Favorite::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $favorite->delete();

        return back()->with('status', 'Event dihapus dari favorit.');
    }
}
