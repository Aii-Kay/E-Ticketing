<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // role: admin, organizer, registered_user
            $table->enum('role', ['admin', 'organizer', 'registered_user'])
                  ->default('registered_user');

            // status: pending, approved, rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
