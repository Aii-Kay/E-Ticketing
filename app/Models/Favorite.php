<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model Favorite merepresentasikan event yang difavoritkan oleh user
class Favorite extends Model
{
    use HasFactory;

    // Tabel hanya punya id, user_id, event_id (tanpa timestamps)
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'event_id',
    ];

    // Favorite belongsTo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Favorite belongsTo Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
