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
        // Create cart_activities table
        Schema::create('cart_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // item_added, item_removed, item_confirmed, checkout_completed, etc.
            $table->json('data'); // Additional data about the activity
            $table->enum('status', ['active', 'pending', 'approved', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['type', 'status']);
            $table->index('created_at');
        });

        // Create admin_notifications table
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // item_confirmed, checkout_completed, cart_updated, etc.
            $table->json('data'); // Additional data about the notification
            $table->boolean('read')->default(false);
            $table->timestamps();
            
            $table->index(['read', 'created_at']);
            $table->index('action');
        });

        // Create cart_items table for persistent cart storage
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->boolean('confirmed')->default(false);
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'vehicle_id']);
            $table->index(['user_id', 'confirmed']);
        });

        // Note: Cart-related columns will be added to vehicles and users tables
        // in separate migrations after those tables are created
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('admin_notifications');
        Schema::dropIfExists('cart_activities');
        
        // Cart-related columns will be dropped in separate migrations
    }
};
