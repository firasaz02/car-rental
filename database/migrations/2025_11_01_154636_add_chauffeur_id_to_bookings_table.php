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
        Schema::table('bookings', function (Blueprint $table) {
            // Add chauffeur_id column if it doesn't exist
            if (!Schema::hasColumn('bookings', 'chauffeur_id')) {
                $table->foreignId('chauffeur_id')
                      ->nullable()
                      ->after('vehicle_id')
                      ->constrained('users')
                      ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop chauffeur_id column if it exists
            if (Schema::hasColumn('bookings', 'chauffeur_id')) {
                $table->dropForeign(['chauffeur_id']);
                $table->dropColumn('chauffeur_id');
            }
        });
    }
};
