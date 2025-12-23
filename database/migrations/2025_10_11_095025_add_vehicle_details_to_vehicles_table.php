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
        // Add only missing columns to avoid duplicate column errors in sqlite
        $columns = [
            'make','model','year','license_plate','vin','color','mileage','fuel_type',
            'transmission_type','last_maintenance_date','next_maintenance_date',
            'insurance_policy_number','insurance_expiry_date','registration_expiry_date',
            'notes','rate_per_hour','type','capacity','daily_rate','status','description','features'
        ];

        foreach ($columns as $col) {
            if (!Schema::hasColumn('vehicles', $col)) {
                Schema::table('vehicles', function (Blueprint $table) use ($col) {
                    switch ($col) {
                        case 'make':
                        case 'model':
                        case 'license_plate':
                        case 'vin':
                        case 'color':
                        case 'fuel_type':
                        case 'transmission_type':
                        case 'insurance_policy_number':
                            $table->string($col)->nullable()->after('vehicle_type');
                            break;
                        case 'year':
                            $table->integer('year')->nullable()->after('model');
                            break;
                        case 'mileage':
                            $table->integer('mileage')->nullable()->after('color');
                            break;
                        case 'last_maintenance_date':
                        case 'next_maintenance_date':
                        case 'insurance_expiry_date':
                        case 'registration_expiry_date':
                            $table->date($col)->nullable()->after('transmission_type');
                            break;
                        case 'notes':
                            $table->text('notes')->nullable()->after('registration_expiry_date');
                            break;
                        case 'rate_per_hour':
                            $table->decimal('rate_per_hour', 8, 2)->nullable()->after('notes');
                            break;
                        case 'type':
                            $table->string('type')->nullable()->after('vehicle_type');
                            break;
                        case 'capacity':
                            $table->integer('capacity')->nullable()->after('type');
                            break;
                        case 'daily_rate':
                            $table->decimal('daily_rate', 8, 2)->nullable()->after('capacity');
                            break;
                        case 'status':
                            $table->string('status')->default('available')->after('daily_rate');
                            break;
                        case 'description':
                        case 'features':
                            $table->text($col)->nullable()->after('status');
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
            'make', 'model', 'year', 'license_plate', 'vin', 'color', 'mileage',
            'fuel_type', 'transmission_type', 'last_maintenance_date', 'next_maintenance_date',
            'insurance_policy_number', 'insurance_expiry_date', 'registration_expiry_date',
            'notes', 'rate_per_hour', 'type', 'capacity', 'daily_rate', 'status',
            'description', 'features'
        ];

        foreach ($columns as $col) {
            if (Schema::hasColumn('vehicles', $col)) {
                Schema::table('vehicles', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }
    }
};