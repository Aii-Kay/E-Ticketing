<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // perbesar kolom image jadi 2048 karakter
            $table->string('image', 2048)->nullable()->change();
            // kalau mau lebih lega lagi bisa pakai:
            // $table->text('image')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // balikin lagi ke 255 kalau di-rollback
            $table->string('image', 255)->nullable()->change();
        });
    }
};
