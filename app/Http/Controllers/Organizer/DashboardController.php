<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index(): View
    {
        $organizerId = Auth::id();

        // 1. Total Events (Milik Organizer)
        $totalEvents = Event::where('created_by', $organizerId)->count();

        // 2. Ambil ID event milik organizer
        $myEventIds = Event::where('created_by', $organizerId)->pluck('id');

        // 3. Tiket Terjual (Hanya status approved)
        $totalTicketsSold = Booking::whereIn('event_id', $myEventIds)
            ->where('status', 'approved')
            ->sum('quantity');

        // 4. Total Revenue (Quantity * Ticket Price)
        // Menggunakan eager loading 'ticketType' untuk efisiensi query
        $revenue = 0;
        $approvedBookings = Booking::with('ticketType')
            ->whereIn('event_id', $myEventIds)
            ->where('status', 'approved')
            ->get();

        foreach($approvedBookings as $b) {
            // Pastikan ticketType ada, jika tidak, hitung 0
            $price = $b->ticketType ? $b->ticketType->price : 0;
            $revenue += ($b->quantity * $price);
        }

        // 5. Data Grafik Bulanan (Tahun ini)
        // Inisialisasi array untuk 12 bulan dengan nilai 0
        $monthlyRevenue = array_fill(1, 12, 0);
        $currentYear = date('Y');

        foreach($approvedBookings as $b) {
            // Cek apakah booking dibuat tahun ini
            if($b->created_at->format('Y') == $currentYear) {
                $month = (int)$b->created_at->format('n'); // Ambil bulan (1-12)
                $price = $b->ticketType ? $b->ticketType->price : 0;
                $monthlyRevenue[$month] += ($b->quantity * $price);
            }
        }
        // Ubah array agar indexnya mulai dari 0 untuk Chart.js
        $chartData = array_values($monthlyRevenue);

        // Kirim data ke view
        return view('organizer.dashboard', compact(
            'totalEvents',
            'totalTicketsSold',
            'revenue',
            'chartData'
        ));
    }
}
