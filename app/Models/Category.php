<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model kategori event, sekaligus relasi ke Event
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    // Category hasMany Events
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
