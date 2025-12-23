<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vehicles')) {
            Schema::create('vehicles', function (Blueprint $table) {
                $table->id();
                $table->string('vehicle_number')->nullable();
                $table->string('driver_name')->nullable();
                $table->string('vehicle_type')->nullable();
                $table->string('phone')->nullable();
                $table->string('make')->nullable();
                $table->string('model')->nullable();
                $table->integer('year')->nullable();
                $table->string('license_plate')->nullable();
                $table->string('vin')->nullable();
                $table->string('color')->nullable();
                $table->bigInteger('mileage')->nullable();
                $table->string('fuel_type')->nullable();
                $table->string('transmission_type')->nullable();
                $table->date('last_maintenance_date')->nullable();
                $table->date('next_maintenance_date')->nullable();
                $table->string('insurance_policy_number')->nullable();
                $table->date('insurance_expiry_date')->nullable();
                $table->date('registration_expiry_date')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreignId('chauffeur_id')->nullable()->constrained('users')->nullOnDelete();
                $table->decimal('rate_per_hour', 8, 2)->nullable();
                $table->string('image_url')->nullable();
                $table->boolean('available_for_cart')->default(false);
                $table->integer('cart_usage_count')->default(0);
                $table->string('type')->nullable();
                $table->integer('capacity')->nullable();
                $table->decimal('daily_rate', 8, 2)->nullable();
                $table->string('status')->nullable();
                $table->text('description')->nullable();
                $table->json('features')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
