<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

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

    protected $casts = [
        'date' => 'date',
    ];

    // Event belongsTo User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Event belongsTo Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Event hasMany TicketType
    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }
}
