<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Ambil semua nilai filter dari query string
        $filters = [
            'keyword'     => $request->query('keyword'),
            'location'    => $request->query('location'),
            'start_date'  => $request->query('start_date'),
            'category_id' => $request->query('category_id'),
        ];

        // Query event dasar: hanya event yang tanggalnya >= hari ini
        $eventsQuery = Event::with('category')
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date');

        if ($filters['keyword']) {
            $eventsQuery->where('name', 'like', '%' . $filters['keyword'] . '%');
        }

        if ($filters['location']) {
            $eventsQuery->where('location', 'like', '%' . $filters['location'] . '%');
        }

        if ($filters['start_date']) {
            $eventsQuery->whereDate('date', '>=', $filters['start_date']);
        }

        if ($filters['category_id']) {
            $eventsQuery->where('category_id', $filters['category_id']);
        }

        $events = $eventsQuery->get();

        // Ambil daftar kategori untuk dropdown (hanya kategori yang sudah ada di DB)
        $categories = Category::orderBy('name')->get();

        return view('user.dashboard', [
            'user'       => $user,
            'events'     => $events,
            'filters'    => $filters,
            'categories' => $categories,
        ]);
    }
}
