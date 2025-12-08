<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus kolom image dari categories
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'image')) {
                $table->dropColumn('image');
            }
        });

        // Hapus kolom image dari ticket_types
        Schema::table('ticket_types', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_types', 'image')) {
                $table->dropColumn('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tambahkan lagi kolom image jika di-rollback
        Schema::table('categories', function (Blueprint $table) {
            if (! Schema::hasColumn('categories', 'image')) {
                $table->string('image')->nullable();
            }
        });

        Schema::table('ticket_types', function (Blueprint $table) {
            if (! Schema::hasColumn('ticket_types', 'image')) {
                $table->string('image')->nullable();
            }
        });
    }
};
