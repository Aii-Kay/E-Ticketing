<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model Booking merepresentasikan pemesanan tiket oleh user
class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_type_id',
        'quantity',
        'status',
    ];

    // Booking belongsTo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Booking belongsTo Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Booking belongsTo TicketType
    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }
}
