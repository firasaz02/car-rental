<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add the chauffeur_id foreign key only if it doesn't exist yet.
        if (!Schema::hasColumn('vehicles', 'chauffeur_id')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->foreignId('chauffeur_id')->nullable()->constrained('users')->nullOnDelete()->after('driver_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('vehicles', 'chauffeur_id')) {
            Schema::table('vehicles', function (Blueprint $table) {
                // Dropping foreign keys in sqlite requires naming; attempt only if exists.
                try {
                    $table->dropForeign(['chauffeur_id']);
                } catch (\Exception $e) {
                    // ignore if foreign key doesn't exist in sqlite in-memory migrations
                }
                $table->dropColumn('chauffeur_id');
            });
        }
    }
};