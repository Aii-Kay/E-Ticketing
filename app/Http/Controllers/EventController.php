<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Menampilkan hasil pencarian untuk halaman publik (Welcome Page).
     */
    public function search(Request $request): View
    {
        $query = Event::with('category');

        // 1. Filter Keyword
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // 2. Filter Lokasi
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // 3. Filter Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 4. Filter Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('date', '=', $request->start_date);
        }

        $events = $query->orderBy('date', 'asc')->get();
        $categories = Category::orderBy('name')->get();

        return view('welcome', compact('events', 'categories'));
    }

    /**
     * List event untuk Dashboard (Admin & Organizer).
     */
    public function index(): View
    {
        $user = Auth::user();

        $query = Event::with(['category', 'creator'])
            ->orderBy('date', 'desc')
            ->orderBy('time');

        if ($user->role === 'organizer') {
            $query->where('created_by', $user->id);
            $events = $query->get();
            return view('organizer.events.index', compact('user', 'events'));
        }

        // Admin melihat semua event
        $events = $query->get();
        return view('admin.events.index', compact('user', 'events'));
    }

    /**
     * Form create event.
     */
    public function create(): View
    {
        $user       = Auth::user();
        $categories = Category::orderBy('name')->get();

        // KHUSUS ADMIN: Ambil daftar organizer untuk dropdown
        $organizers = collect();
        if ($user->role === 'admin') {
            $organizers = User::where('role', 'organizer')
                              ->where('status', 'approved')
                              ->orderBy('name')
                              ->get();
        }

        $viewPrefix = $user->role === 'organizer' ? 'organizer' : 'admin';

        return view("{$viewPrefix}.events.create", compact('user', 'categories', 'organizers'));
    }

    /**
     * Simpan event baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $data = $this->validateData($request);

        // LOGIKA PEMILIK EVENT
        if ($user->role === 'admin' && $request->filled('created_by')) {
            // Jika admin memilih organizer, gunakan ID organizer tersebut
            $data['created_by'] = $request->created_by;
        } else {
            // Jika organizer login, otomatis pakai ID sendiri
            $data['created_by'] = $user->id;
        }

        // Upload gambar
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request);
        }

        $event = Event::create($data);

        // Kirim notifikasi ke Admin jika Organizer yang membuat
        if ($user->role === 'organizer') {
            $this->notifyAdmins($user, $event);
        }

        $routeName = $user->role === 'organizer' ? 'organizer.events.index' : 'admin.events.index';

        return redirect()->route($routeName)->with('status', 'Event berhasil dibuat.');
    }

    /**
     * Form edit event.
     */
    public function edit(Event $event): View
    {
        $user = Auth::user();
        $this->ensureCanManage($event, $user);

        $categories = Category::orderBy('name')->get();

        // KHUSUS ADMIN: Ambil daftar organizer untuk dropdown saat edit
        $organizers = collect();
        if ($user->role === 'admin') {
            $organizers = User::where('role', 'organizer')
                              ->where('status', 'approved')
                              ->orderBy('name')
                              ->get();
        }

        $viewPrefix = $user->role === 'organizer' ? 'organizer' : 'admin';

        return view("{$viewPrefix}.events.edit", compact('user', 'event', 'categories', 'organizers'));
    }

    /**
     * Update event.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $user = Auth::user();
        $this->ensureCanManage($event, $user);

        $data = $this->validateData($request);

        // Jika Admin mengubah organizer pemilik event
        if ($user->role === 'admin' && $request->filled('created_by')) {
            $data['created_by'] = $request->created_by;
        }

        // Handle Image Update
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($event->image && File::exists(public_path($event->image))) {
                File::delete(public_path($event->image));
            }
            $data['image'] = $this->uploadImage($request);
        }

        $event->update($data);

        $routeName = $user->role === 'organizer' ? 'organizer.events.index' : 'admin.events.index';

        return redirect()->route($routeName)->with('status', 'Event berhasil diperbarui.');
    }

    /**
     * Hapus event.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $user = Auth::user();
        $this->ensureCanManage($event, $user);

        // Hapus gambar fisik jika ada
        if ($event->image && File::exists(public_path($event->image))) {
            File::delete(public_path($event->image));
        }

        $event->delete();

        $routeName = $user->role === 'organizer' ? 'organizer.events.index' : 'admin.events.index';

        return redirect()->route($routeName)->with('status', 'Event berhasil dihapus.');
    }

    // ----------------------------------------------------------------------
    // HELPER METHODS
    // ----------------------------------------------------------------------

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date'        => ['required', 'date'],
            'time'        => ['required', 'string', 'max:20'],
            'location'    => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'created_by'  => ['nullable', 'integer', 'exists:users,id'], // Validasi organizer ID
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:4096'], // Support webp & max 4MB
        ]);
    }

    protected function uploadImage(Request $request): string
    {
        $file = $request->file('image');
        // Generate nama unik
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

        $targetDir = public_path('images/events');

        if (! File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $file->move($targetDir, $fileName);

        return 'images/events/' . $fileName;
    }

    protected function notifyAdmins($organizer, $event): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title'   => 'Event Baru',
                'message' => "Organizer {$organizer->name} telah membuat event baru: {$event->name}",
                'status'  => 'unread',
            ]);
        }
    }

    protected function ensureCanManage(Event $event, $user): void
    {
        if ($user->role === 'organizer' && $event->created_by !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengelola event ini.');
        }
    }
}
