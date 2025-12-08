<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    /**
     * Generate dan download e-ticket PDF untuk satu booking.
     * Syarat:
     * - User sudah login
     * - Booking milik user tersebut
     * - Status booking = approved
     */
    public function downloadPDF(Booking $booking): Response
    {
        $user = Auth::user();

        // Pastikan user login & booking milik user tersebut
        if (! $user || $booking->user_id !== $user->id) {
            abort(403, 'You can only download your own tickets.');
        }

        // Hanya booking yang sudah di-approve yang boleh punya e-ticket
        if ($booking->status !== 'approved') {
            abort(403, 'Ticket can only be downloaded for approved bookings.');
        }

        // Muat relasi yang dibutuhkan (event, ticketType, user)
        $booking->load(['event', 'ticketType', 'user']);

        // Teks yang dimasukkan ke dalam QR Code
        $qrText = sprintf(
            'booking:%d;email:%s;event:%s',
            $booking->id,
            $booking->user->email,
            $booking->event->name
        );

        // Generate QR code dalam bentuk SVG
        $qrSvg   = QrCode::format('svg')
            ->size(140)
            ->generate($qrText);

        // Encode SVG jadi base64 supaya bisa dipakai di <img> untuk DomPDF
        $qrImage = base64_encode($qrSvg);

        // Render Blade menjadi PDF
        $pdf = Pdf::loadView('pdf.ticket', [
            'booking'    => $booking,
            'event'      => $booking->event,
            'ticketType' => $booking->ticketType,
            'user'       => $booking->user,
            'qrText'     => $qrText,
            'qrImage'    => $qrImage, // dikirim ke view
        ])->setPaper('a4');

        $fileName = 'ticket-' . $booking->id . '.pdf';

        return $pdf->download($fileName);
    }
}
