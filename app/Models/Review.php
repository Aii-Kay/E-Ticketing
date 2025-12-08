<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Model Review menyimpan rating & komentar user untuk suatu event
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'rating',
        'comment',
    ];

    // Review belongsTo User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Review belongsTo Event
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
