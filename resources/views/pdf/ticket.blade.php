<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>VIP Access #{{ $booking->id }}</title>
    <style>
        @page { margin: 0; padding: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #000000; /* Hitam Pekat */
            margin: 0; padding: 0;
            color: #ffffff;
        }

        .page-container {
            padding: 40px;
            width: 100%;
        }

        /* Container Tiket Utama */
        .ticket {
            width: 100%;
            max-width: 750px;
            margin: 0 auto;
            background-color: #0f172a; /* Slate 900 - Biru Gelap Mewah */
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            /* Efek Garis Neon */
            border: 2px solid #38bdf8;
        }

        /* Layout Table (Wajib untuk PDF agar rapi) */
        .layout-table {
            width: 100%;
            border-collapse: collapse;
        }

        /* BAGIAN KIRI - INFO UTAMA */
        .col-left {
            width: 70%;
            padding: 30px;
            vertical-align: top;
            border-right: 2px dashed #334155; /* Garis Sobekan */
            position: relative;
        }

        /* BAGIAN KANAN - QR CODE */
        .col-right {
            width: 30%;
            vertical-align: middle;
            text-align: center;
            background-color: #1e293b; /* Slate 800 - Sedikit lebih terang */
            padding: 20px;
            position: relative;
        }

        /* Tipografi & Elemen */
        .label-micro {
            font-size: 9px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #94a3b8; /* Abu-abu kebiruan */
            margin-bottom: 5px;
            font-weight: bold;
        }

        .event-title {
            font-size: 26px;
            font-weight: 900;
            text-transform: uppercase;
            line-height: 1.1;
            margin: 0 0 25px 0;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        /* Grid Info di Kiri */
        .info-grid {
            width: 100%;
            margin-top: 10px;
        }
        .info-grid td {
            padding-bottom: 15px;
            vertical-align: top;
        }

        .info-value {
            font-size: 15px;
            font-weight: 700;
            color: #f8fafc;
        }

        .highlight-neon {
            color: #38bdf8; /* Warna Cyan Neon */
            text-shadow: 0 0 10px rgba(56, 189, 248, 0.4);
            font-size: 16px;
        }

        .badge-type {
            background-color: #38bdf8;
            color: #0f172a;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            display: inline-block;
        }

        /* Elemen Dekoratif (Lingkaran Sobekan) */
        .circle-cutout {
            width: 20px;
            height: 20px;
            background-color: #000000; /* Harus sama dengan background body */
            border-radius: 50%;
            position: absolute;
            z-index: 10;
            left: -10px; /* Posisi di tengah garis batas */
        }
        .cut-top { top: -10px; }
        .cut-bottom { bottom: -10px; }

        /* Style Khusus Bagian Kanan */
        .qr-container {
            background: #ffffff;
            padding: 8px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.3); /* Glow effect pada QR */
        }
        .qr-img {
            width: 120px;
            height: 120px;
            display: block;
        }
        .booking-ref {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 15px;
            border: 1px solid #334155;
            padding: 5px;
            border-radius: 4px;
            display: inline-block;
            background-color: #0f172a;
        }

        /* Footer Stripe (Garis Miring Dekoratif) */
        .footer-stripe {
            width: 100%;
            height: 6px;
            background: repeating-linear-gradient(
                45deg,
                #38bdf8,
                #38bdf8 10px,
                #0f172a 10px,
                #0f172a 20px
            );
        }

        /* Watermark Background */
        .watermark {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 60px;
            font-weight: 900;
            color: rgba(255,255,255,0.03); /* Sangat transparan */
            transform: rotate(-15deg);
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body>

    <div class="page-container">
        <div class="ticket">
            <div class="footer-stripe"></div>

            <table class="layout-table">
                <tr>
                    <td class="col-left">
                        <div class="watermark">ADMIT ONE</div>

                        <div class="label-micro">OFFICIAL EVENT ACCESS</div>
                        {{-- Pastikan variabel $event tersedia --}}
                        <h1 class="event-title">{{ $event->name ?? 'Nama Event Tidak Tersedia' }}</h1>

                        <table class="info-grid">
                            <tr>
                                <td width="50%">
                                    <div class="label-micro">DATE</div>
                                    <div class="info-value highlight-neon">
                                        {{ isset($event->date) ? \Carbon\Carbon::parse($event->date)->format('d F Y') : '-' }}
                                    </div>
                                </td>
                                <td width="50%">
                                    <div class="label-micro">TIME</div>
                                    <div class="info-value">
                                        {{ isset($event->time) ? \Carbon\Carbon::parse($event->time)->format('H:i') : '-' }} WIB
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="label-micro" style="margin-top: 5px;">LOCATION</div>
                                    <div class="info-value">{{ $event->location ?? '-' }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="label-micro" style="margin-top: 5px;">ATTENDEE</div>
                                    {{-- Pastikan variabel $user tersedia --}}
                                    <div class="info-value" style="font-size: 14px;">{{ $user->name ?? 'Guest' }}</div>
                                </td>
                                <td>
                                    <div class="label-micro" style="margin-top: 5px;">ACCESS TYPE</div>
                                    {{-- Pastikan variabel $ticketType tersedia --}}
                                    <div class="badge-type">{{ $ticketType->name ?? 'REGULAR' }}</div>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td class="col-right">
                        <div class="circle-cutout cut-top"></div>
                        <div class="circle-cutout cut-bottom"></div>

                        @if (!empty($qrImage))
                            <div class="qr-container">
                                <img src="data:image/svg+xml;base64,{{ $qrImage }}" class="qr-img" alt="QR Code">
                            </div>
                        @else
                            <div class="qr-container" style="height: 120px; width:120px; display:flex; align-items:center; justify-content:center; background:#eee; color:#000; font-size:10px;">
                                NO QR
                            </div>
                        @endif

                        <div style="margin-top: 15px;">
                            <div class="label-micro">BOOKING ID</div>
                            <div class="booking-ref">#{{ str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}</div>
                        </div>

                        <div style="margin-top: 10px; font-size: 8px; color: #64748b;">
                            SCAN FOR ENTRY
                        </div>
                    </td>
                </tr>
            </table>

             <div class="footer-stripe"></div>
        </div>

        <div style="text-align: center; margin-top: 15px; color: #475569; font-size: 10px;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved. Do not duplicate this ticket.
        </div>
    </div>

</body>
</html>
