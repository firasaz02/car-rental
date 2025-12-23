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
        // Only add the column if it doesn't already exist. This makes the migration
        // safe to run repeatedly (important for sqlite in-memory tests).
        if (!Schema::hasColumn('vehicles', 'image_url')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->string('image_url')->nullable()->after('phone');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('vehicles', 'image_url')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->dropColumn('image_url');
            });
        }
    }
};
