<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventSearchController extends Controller
{
    // Search event berdasarkan nama, lokasi, dan range tanggal
    public function search(Request $request): View
    {
        $validated = $request->validate([
            'keyword'    => ['nullable', 'string', 'max:255'],
            'location'   => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $keyword   = $validated['keyword']    ?? null;
        $location  = $validated['location']   ?? null;
        $startDate = $validated['start_date'] ?? null;
        $endDate   = $validated['end_date']   ?? null;

        $query = Event::query();

        $query
            ->when($keyword, function ($q, $keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            })
            ->when($location, function ($q, $location) {
                $q->where('location', 'like', '%' . $location . '%');
            })
            ->when($startDate, function ($q, $startDate) {
                $q->whereDate('date', '>=', $startDate);
            })
            ->when($endDate, function ($q, $endDate) {
                $q->whereDate('date', '<=', $endDate);
            });

        $events = $query
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        return view('welcome', [
            'events'  => $events,
            'filters' => [
                'keyword'    => $keyword,
                'location'   => $location,
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ],
        ]);
    }
}
