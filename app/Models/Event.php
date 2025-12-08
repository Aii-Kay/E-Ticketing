<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Model Event menyimpan data acara dan relasi ke user, kategori, tiket, dan review
class Event extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'name',
        'description',
        'date',
        'time',
        'location',
        'category_id',
        'image',
        'created_by',
    ];

    // Casting kolom tanggal supaya otomatis jadi instance Carbon
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Event dibuat oleh 1 user (creator).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Event milik 1 kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Event punya banyak tipe tiket.
     */
    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    /**
     * Event punya banyak review dari user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    
}
