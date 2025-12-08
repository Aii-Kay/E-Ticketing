@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-4">Organizer Analytics</h1>

    {{-- SUMMARY CARD --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow-sm rounded-md p-4">
            <div class="text-xs text-gray-500 mb-1">Total Event Saya</div>
            <div id="org-total-events" class="text-2xl font-semibold">-</div>
        </div>
        <div class="bg-white shadow-sm rounded-md p-4">
            <div class="text-xs text-gray-500 mb-1">Total Tiket Terjual</div>
            <div id="org-total-tickets" class="text-2xl font-semibold">-</div>
        </div>
        <div class="bg-white shadow-sm rounded-md p-4">
            <div class="text-xs text-gray-500 mb-1">Sisa Kuota Tiket</div>
            <div id="org-remaining-quota" class="text-2xl font-semibold">-</div>
        </div>
        <div class="bg-white shadow-sm rounded-md p-4">
            <div class="text-xs text-gray-500 mb-1">Total Revenue</div>
            <div id="org-total-revenue" class="text-2xl font-semibold">-</div>
        </div>
    </div>

    {{-- CHART --}}
    <div class="bg-white shadow-sm rounded-md p-4">
        <h2 class="font-semibold mb-4 text-sm">Penjualan per Event</h2>
        <canvas id="org-event-sales-chart" height="120"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('{{ route('organizer.analytics.json') }}')
        .then(response => response.json())
        .then(data => {
            // summary
            document.getElementById('org-total-events').textContent      = data.total_events;
            document.getElementById('org-total-tickets').textContent     = data.total_tickets_sold;
            document.getElementById('org-remaining-quota').textContent   = data.remaining_quota;
            document.getElementById('org-total-revenue').textContent     = 'Rp ' + data.total_revenue.toLocaleString('id-ID');

            const ctx = document.getElementById('org-event-sales-chart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.per_event_sales.labels,
                    datasets: [
                        {
                            label: 'Tiket Terjual',
                            data: data.per_event_sales.tickets,
                            borderWidth: 1,
                            backgroundColor: 'rgba(37, 99, 235, 0.6)'
                        },
                        {
                            label: 'Revenue',
                            data: data.per_event_sales.revenue,
                            borderWidth: 1,
                            backgroundColor: 'rgba(16, 185, 129, 0.6)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        })
        .catch(err => {
            console.error(err);
        });
});
</script>
@endsection
