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
        $columns = [
            'phone','date_of_birth','address','city','country','license_number','license_expiry',
            'emergency_contact','emergency_phone','notes','profile_image','is_active','last_login_at'
        ];

        foreach ($columns as $col) {
            if (!Schema::hasColumn('users', $col)) {
                Schema::table('users', function (Blueprint $table) use ($col) {
                    switch ($col) {
                        case 'phone':
                        case 'address':
                        case 'city':
                        case 'country':
                        case 'license_number':
                        case 'emergency_contact':
                        case 'emergency_phone':
                        case 'profile_image':
                            $table->string($col)->nullable()->after('email');
                            break;
                        case 'date_of_birth':
                            $table->date('date_of_birth')->nullable()->after('phone');
                            break;
                        case 'license_expiry':
                            $table->date('license_expiry')->nullable()->after('license_number');
                            break;
                        case 'notes':
                            $table->text('notes')->nullable()->after('emergency_phone');
                            break;
                        case 'is_active':
                            $table->boolean('is_active')->default(true)->after('profile_image');
                            break;
                        case 'last_login_at':
                            $table->timestamp('last_login_at')->nullable()->after('is_active');
                            break;
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = [
            'phone','date_of_birth','address','city','country','license_number','license_expiry',
            'emergency_contact','emergency_phone','notes','profile_image','is_active','last_login_at'
        ];

        foreach ($columns as $col) {
            if (Schema::hasColumn('users', $col)) {
                Schema::table('users', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }
    }
};