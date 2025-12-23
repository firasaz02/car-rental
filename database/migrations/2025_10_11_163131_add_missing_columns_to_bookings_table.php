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
            // Add missing columns that the Booking model expects
            $table->decimal('total_amount', 10, 2)->nullable()->after('status');
            $table->text('notes')->nullable()->after('total_amount');
            $table->string('pickup_location')->nullable()->after('notes');
            $table->string('dropoff_location')->nullable()->after('pickup_location');
            
            // Update status enum to include all statuses
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])
                  ->default('pending')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['total_amount', 'notes', 'pickup_location', 'dropoff_location']);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])
                  ->default('pending')
                  ->change();
        });
    }
};