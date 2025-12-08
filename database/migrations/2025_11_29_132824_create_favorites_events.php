<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel favorites menyimpan event yang difavoritkan user (wishlist)
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();

            // Relasi ke user
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Relasi ke event
            $table->foreignId('event_id')
                ->constrained()
                ->cascadeOnDelete();

            // Satu user hanya boleh favoritkan 1x per event
            $table->unique(['user_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
