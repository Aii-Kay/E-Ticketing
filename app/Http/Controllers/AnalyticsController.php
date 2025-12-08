<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Statistik untuk ADMIN (semua event).
     * - total_events
     * - total_tickets_sold
     * - total_revenue
     * - monthly_sales (12 bulan terakhir)
     */
    public function adminStats(): JsonResponse
    {
        $totalEvents = Event::count();

        $totalTicketsSold = Booking::where('status', 'approved')
            ->sum('quantity');

        $totalRevenue = Booking::where('status', 'approved')
            ->join('ticket_types', 'bookings.ticket_type_id', '=', 'ticket_types.id')
            ->sum(DB::raw('ticket_types.price * bookings.quantity'));

        // 12 bulan terakhir mulai dari bulan ini mundur 11 bulan
        $now  = Carbon::now()->startOfMonth();
        $from = $now->copy()->subMonths(11);

        $raw = Booking::select(
                DB::raw('DATE_FORMAT(bookings.created_at, "%Y-%m") as ym'),
                DB::raw('SUM(bookings.quantity) as tickets'),
                DB::raw('SUM(bookings.quantity * ticket_types.price) as revenue')
            )
            ->join('ticket_types', 'bookings.ticket_type_id', '=', 'ticket_types.id')
            ->where('bookings.status', 'approved')
            ->whereBetween('bookings.created_at', [$from, $now->copy()->endOfMonth()])
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $labels      = [];
        $ticketsData = [];
        $revenueData = [];

        for ($i = 0; $i < 12; $i++) {
            $month = $from->copy()->addMonths($i);
            $key   = $month->format('Y-m');

            $labels[] = $month->format('M Y');

            if ($raw->has($key)) {
                $ticketsData[] = (int) $raw[$key]->tickets;
                $revenueData[] = (float) $raw[$key]->revenue;
            } else {
                $ticketsData[] = 0;
                $revenueData[] = 0.0;
            }
        }

        return response()->json([
            'total_events'       => (int) $totalEvents,
            'total_tickets_sold' => (int) $totalTicketsSold,
            'total_revenue'      => (float) $totalRevenue,
            'monthly_sales'      => [
                'labels'  => $labels,
                'tickets' => $ticketsData,
                'revenue' => $revenueData,
            ],
        ]);
    }

    /**
     * Statistik untuk ORGANIZER (hanya event miliknya).
     * - total_events (event yang dia buat)
     * - total_tickets_sold
     * - remaining_quota (sisa semua ticket_types di event dia)
     * - total_revenue
     * - per_event_sales (untuk chart per event)
     */
    public function organizerStats(): JsonResponse
    {
        $user = Auth::user();

        $totalEvents = Event::where('created_by', $user->id)->count();

        $totalTicketsSold = Booking::where('status', 'approved')
            ->whereHas('event', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })
            ->sum('quantity');

        $totalRevenue = Booking::where('status', 'approved')
            ->whereHas('event', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })
            ->join('ticket_types', 'bookings.ticket_type_id', '=', 'ticket_types.id')
            ->sum(DB::raw('ticket_types.price * bookings.quantity'));

        $remainingQuota = TicketType::whereHas('event', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })
            ->sum('quota');

        $eventSales = Booking::select(
                'events.id as event_id',
                'events.name as event_name',
                DB::raw('SUM(bookings.quantity) as tickets_sold'),
                DB::raw('SUM(bookings.quantity * ticket_types.price) as revenue')
            )
            ->join('events', 'bookings.event_id', '=', 'events.id')
            ->join('ticket_types', 'bookings.ticket_type_id', '=', 'ticket_types.id')
            ->where('bookings.status', 'approved')
            ->where('events.created_by', $user->id)
            ->groupBy('events.id', 'events.name')
            ->orderBy('events.name')
            ->get();

        $labels      = $eventSales->pluck('event_name')->values();
        $ticketsData = $eventSales->pluck('tickets_sold')->map(fn ($v) => (int) $v)->values();
        $revenueData = $eventSales->pluck('revenue')->map(fn ($v) => (float) $v)->values();

        return response()->json([
            'total_events'       => (int) $totalEvents,
            'total_tickets_sold' => (int) $totalTicketsSold,
            'remaining_quota'    => (int) $remainingQuota,
            'total_revenue'      => (float) $totalRevenue,
            'per_event_sales'    => [
                'labels'  => $labels,
                'tickets' => $ticketsData,
                'revenue' => $revenueData,
            ],
        ]);
    }
}
