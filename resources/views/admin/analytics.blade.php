@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-4">Admin Analytics</h1>

    {{-- SUMMARY CARD --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white shadow-sm rounded-md p-4">
            <div class="text-xs text-gray-500 mb-1">Total Event</div>
            <div id="admin-total-events" class="text-2xl font-semibold">-</div>
        </div>
        <div class="bg-white shadow-sm rounded-md p-4">
            <div class="text-xs text-gray-500 mb-1">Total Tiket Terjual</div>
            <div id="admin-total-tickets" class="text-2xl font-semibold">-</div>
        </div>
        <div class="bg-white shadow-sm rounded-md p-4">
            <div class="text-xs text-gray-500 mb-1">Total Revenue</div>
            <div id="admin-total-revenue" class="text-2xl font-semibold">-</div>
        </div>
    </div>

    {{-- CHART --}}
    <div class="bg-white shadow-sm rounded-md p-4">
        <h2 class="font-semibold mb-4 text-sm">Penjualan Bulanan (12 bulan terakhir)</h2>
        <canvas id="admin-sales-chart" height="120"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('{{ route('admin.analytics.json') }}')
        .then(response => response.json())
        .then(data => {
            // isi summary
            document.getElementById('admin-total-events').textContent   = data.total_events;
            document.getElementById('admin-total-tickets').textContent  = data.total_tickets_sold;
            document.getElementById('admin-total-revenue').textContent  = 'Rp ' + data.total_revenue.toLocaleString('id-ID');

            // chart
            const ctx = document.getElementById('admin-sales-chart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.monthly_sales.labels,
                    datasets: [
                        {
                            label: 'Tiket Terjual',
                            data: data.monthly_sales.tickets,
                            borderWidth: 2,
                            borderColor: 'rgba(37, 99, 235, 1)',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            tension: 0.3,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Revenue',
                            data: data.monthly_sales.revenue,
                            borderWidth: 2,
                            borderColor: 'rgba(16, 185, 129, 1)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.3,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            ticks: { precision: 0 }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: { drawOnChartArea: false }
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
