<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * List event.
     * Admin: semua event.
     * Organizer: hanya event miliknya (created_by).
     */
    public function index(): View
    {
        $user = Auth::user();

        $query = Event::with(['category', 'creator'])
            ->orderBy('date')
            ->orderBy('time');

        if ($user->role === 'organizer') {
            $query->where('created_by', $user->id);
        }

        $events = $query->get();

        return view('events.index', compact('events'));
    }

    /**
     * Form create event.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('events.create', compact('categories'));
    }

    /**
     * Simpan event baru.
     * Organizer & Admin boleh create.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // VALIDASI
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date',
            'time'        => 'required|date_format:H:i',
            'location'    => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'required|string|max:255',
        ]);

        $data['created_by'] = $user->id;

        Event::create($data);

        // Redirect sesuai role
        if ($user->role === 'admin') {
            return redirect()->route('admin.events.index');
        }

        return redirect()->route('organizer.events.index');
    }

    /**
     * Form edit event.
     * Organizer: hanya event miliknya.
     * Admin: boleh edit semua.
     */
    public function edit(Event $event): View
    {
        $user = Auth::user();

        if ($user->role === 'organizer' && $event->created_by !== $user->id) {
            abort(403, 'You can only edit your own events.');
        }

        $categories = Category::orderBy('name')->get();

        return view('events.edit', compact('event', 'categories'));
    }

    /**
     * Update event.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $user = Auth::user();

        if ($user->role === 'organizer' && $event->created_by !== $user->id) {
            abort(403, 'You can only update your own events.');
        }

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date',
            'time'        => 'required|date_format:H:i',
            'location'    => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'required|string|max:255',
        ]);

        $event->update($data);

        if ($user->role === 'admin') {
            return redirect()->route('admin.events.index');
        }

        return redirect()->route('organizer.events.index');
    }

    /**
     * Hapus event.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $user = Auth::user();

        if ($user->role === 'organizer' && $event->created_by !== $user->id) {
            abort(403, 'You can only delete your own events.');
        }

        $event->delete();

        if ($user->role === 'admin') {
            return redirect()->route('admin.events.index');
        }

        return redirect()->route('organizer.events.index');
    }
}
