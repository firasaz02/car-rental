<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $t->date('start_date');
            $t->date('end_date');
            $t->enum('status', ['pending','confirmed','cancelled'])->default('pending');
            $t->timestamps();
            $t->index(['vehicle_id','start_date','end_date','status']);
        });
    }
    
    public function down(): void { 
        Schema::dropIfExists('bookings'); 
    }
};
