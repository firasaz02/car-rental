<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\VehicleLocation;
use Carbon\Carbon;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users to assign as chauffeurs
        $chauffeurs = User::where('role', 'chauffeur')->get();
        $admin = User::where('role', 'admin')->first();

        // If no chauffeurs exist, create some (User model handles password hashing automatically)
        if ($chauffeurs->isEmpty()) {
            $chauffeurs = collect([
                User::create([
                    'name' => 'Ahmed Ben Ali',
                    'email' => 'ahmed.chauffeur@example.com',
                    'password' => 'password',
                    'role' => 'chauffeur',
                    'phone' => '+216 12 345 678',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]),
                User::create([
                    'name' => 'Fatma Trabelsi',
                    'email' => 'fatma.chauffeur@example.com',
                    'password' => 'password',
                    'role' => 'chauffeur',
                    'phone' => '+216 98 765 432',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]),
                User::create([
                    'name' => 'Mohamed Khelil',
                    'email' => 'mohamed.chauffeur@example.com',
                    'password' => 'password',
                    'role' => 'chauffeur',
                    'phone' => '+216 55 123 456',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ])
            ]);
        }

        $vehicles = [
            [
                'vehicle_number' => 'VH-001',
                'driver_name' => 'Ahmed Ben Ali',
                'vehicle_type' => 'Luxury Sedan',
                'phone' => '+216 12 345 678',
                'make' => 'Mercedes-Benz',
                'model' => 'S-Class',
                'year' => 2023,
                'license_plate' => 'TN-2023-001',
                'color' => 'Black',
                'type' => 'Sedan',
                'fuel_type' => 'Gasoline',
                'capacity' => 5,
                'daily_rate' => 150.00,
                'status' => 'available',
                'description' => 'Luxury sedan with premium features, leather seats, and advanced safety systems.',
                'features' => 'GPS Navigation, Bluetooth, Air Conditioning, Leather Seats, Sunroof',
                'image_url' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&h=600&fit=crop&q=80',
                'chauffeur_id' => $chauffeurs->random()->id,
                'is_active' => true,
                'available_for_cart' => true,
                'cart_usage_count' => 0,
            ],
            [
                'vehicle_number' => 'VH-002',
                'driver_name' => 'Fatma Trabelsi',
                'vehicle_type' => 'Executive Sedan',
                'phone' => '+216 98 765 432',
                'make' => 'BMW',
                'model' => '7 Series',
                'year' => 2022,
                'license_plate' => 'TN-2022-002',
                'color' => 'White',
                'type' => 'Sedan',
                'fuel_type' => 'Gasoline',
                'capacity' => 5,
                'daily_rate' => 140.00,
                'status' => 'available',
                'description' => 'Executive luxury sedan with cutting-edge technology and comfort.',
                'features' => 'GPS Navigation, Bluetooth, Air Conditioning, Leather Seats, Heated Seats',
                'image_url' => 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800&h=600&fit=crop&q=80',
                'chauffeur_id' => $chauffeurs->random()->id,
                'is_active' => true,
                'available_for_cart' => true,
                'cart_usage_count' => 0,
            ],
            [
                'vehicle_number' => 'VH-003',
                'driver_name' => 'Mohamed Khelil',
                'vehicle_type' => 'Luxury SUV',
                'phone' => '+216 55 123 456',
                'make' => 'Toyota',
                'model' => 'Land Cruiser',
                'year' => 2022,
                'license_plate' => 'TN-2022-003',
                'color' => 'Black',
                'type' => 'SUV',
                'fuel_type' => 'Gasoline',
                'capacity' => 7,
                'daily_rate' => 120.00,
                'status' => 'in_use',
                'description' => 'Robust SUV perfect for family trips and off-road adventures.',
                'features' => 'GPS Navigation, Bluetooth, Air Conditioning, 4WD, Third Row Seats',
                'image_url' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800&h=600&fit=crop&q=80',
                'chauffeur_id' => $chauffeurs->random()->id,
                'is_active' => true,
                'available_for_cart' => true,
                'cart_usage_count' => 0,
            ],
            [
                'vehicle_number' => 'VH-004',
                'driver_name' => 'Ahmed Ben Ali',
                'vehicle_type' => 'Economy Sedan',
                'phone' => '+216 12 345 678',
                'make' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2023,
                'license_plate' => 'TN-2023-004',
                'color' => 'Blue',
                'type' => 'Sedan',
                'fuel_type' => 'Gasoline',
                'capacity' => 5,
                'daily_rate' => 60.00,
                'status' => 'available',
                'description' => 'Reliable and fuel-efficient sedan perfect for daily commuting.',
                'features' => 'GPS Navigation, Bluetooth, Air Conditioning, Cruise Control',
                'image_url' => 'https://images.unsplash.com/photo-1603574670816-d7f6578a0e54?w=800&h=600&fit=crop&q=80',
                'chauffeur_id' => $chauffeurs->random()->id,
                'is_active' => true,
                'available_for_cart' => true,
                'cart_usage_count' => 0,
            ],
            [
                'vehicle_number' => 'VH-005',
                'driver_name' => 'Fatma Trabelsi',
                'vehicle_type' => 'Passenger Van',
                'phone' => '+216 98 765 432',
                'make' => 'Mercedes-Benz',
                'model' => 'Sprinter',
                'year' => 2022,
                'license_plate' => 'TN-2022-005',
                'color' => 'White',
                'type' => 'Van',
                'fuel_type' => 'Diesel',
                'capacity' => 12,
                'daily_rate' => 100.00,
                'status' => 'available',
                'description' => 'Large passenger van ideal for group transportation.',
                'features' => 'GPS Navigation, Bluetooth, Air Conditioning, Multiple Seating Configurations',
                'image_url' => 'https://images.unsplash.com/photo-1617531653332-bd46c24f2068?w=800&h=600&fit=crop&q=80',
                'chauffeur_id' => $chauffeurs->random()->id,
                'is_active' => true,
                'available_for_cart' => true,
                'cart_usage_count' => 0,
            ],
            [
                'vehicle_number' => 'VH-006',
                'driver_name' => 'Mohamed Khelil',
                'vehicle_type' => 'Electric Sedan',
                'phone' => '+216 55 123 456',
                'make' => 'Tesla',
                'model' => 'Model S',
                'year' => 2023,
                'license_plate' => 'TN-2023-006',
                'color' => 'Black',
                'type' => 'Sedan',
                'fuel_type' => 'Electric',
                'capacity' => 5,
                'daily_rate' => 160.00,
                'status' => 'available',
                'description' => 'Premium electric sedan with autopilot and long-range capability.',
                'features' => 'Autopilot, GPS Navigation, Bluetooth, Air Conditioning, Supercharging',
                'image_url' => 'https://images.unsplash.com/photo-1560958089-b8a1929cea89?w=800&h=600&fit=crop&q=80',
                'chauffeur_id' => $chauffeurs->random()->id,
                'is_active' => true,
                'available_for_cart' => true,
                'cart_usage_count' => 0,
            ],
            [
                'vehicle_number' => 'VH-007',
                'driver_name' => 'Ahmed Ben Ali',
                'vehicle_type' => 'Sports Car',
                'phone' => '+216 12 345 678',
                'make' => 'Porsche',
                'model' => '911',
                'year' => 2023,
                'license_plate' => 'TN-2023-007',
                'color' => 'Red',
                'type' => 'Sports Car',
                'fuel_type' => 'Gasoline',
                'capacity' => 2,
                'daily_rate' => 200.00,
                'status' => 'maintenance',
                'description' => 'Iconic sports car with legendary performance and handling.',
                'features' => 'GPS Navigation, Bluetooth, Air Conditioning, Sport Chrono, PDK Transmission',
                'image_url' => 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=800&h=600&fit=crop&q=80',
                'chauffeur_id' => $chauffeurs->random()->id,
                'is_active' => false,
                'available_for_cart' => false,
                'cart_usage_count' => 0,
            ],
            [
                'vehicle_number' => 'VH-008',
                'driver_name' => 'Fatma Trabelsi',
                'vehicle_type' => 'Compact SUV',
                'phone' => '+216 98 765 432',
                'make' => 'Range Rover',
                'model' => 'Evoque',
                'year' => 2023,
                'license_plate' => 'TN-2023-008',
                'color' => 'Red',
                'type' => 'SUV',
                'fuel_type' => 'Gasoline',
                'capacity' => 5,
                'daily_rate' => 130.00,
                'status' => 'in_use',
                'description' => 'Compact luxury SUV with elegant design and advanced technology.',
                'features' => 'GPS Navigation, Bluetooth, Air Conditioning, Terrain Response, Panoramic Roof',
                'image_url' => 'https://images.unsplash.com/photo-1544829099-b9a0c55e42d6?w=800&h=600&fit=crop&q=80',
                'chauffeur_id' => $chauffeurs->random()->id,
                'is_active' => true,
                'available_for_cart' => true,
                'cart_usage_count' => 0,
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            $vehicle = Vehicle::create($vehicleData);

            // Create initial location for each vehicle (LIVE - very recent)
            VehicleLocation::create([
                'vehicle_id' => $vehicle->id,
                'latitude' => $this->getRandomLatitude(),
                'longitude' => $this->getRandomLongitude(),
                'speed' => rand(30, 80), // Realistic moving speed
                'heading' => rand(0, 360),
                'recorded_at' => now()->subSeconds(rand(10, 180)), // Last 3 minutes - LIVE tracking
            ]);

            // Create some historical locations for live tracking path
            $baseLat = $this->getRandomLatitude();
            $baseLng = $this->getRandomLongitude();
            for ($i = 0; $i < rand(8, 12); $i++) {
                VehicleLocation::create([
                    'vehicle_id' => $vehicle->id,
                    'latitude' => $baseLat + (rand(-5000, 5000) / 100000), // Small variations for realistic movement
                    'longitude' => $baseLng + (rand(-5000, 5000) / 100000),
                    'speed' => rand(20, 90), // Realistic speeds
                    'heading' => rand(0, 360),
                    'recorded_at' => now()->subMinutes(rand(1, 30))->subSeconds(rand(0, 59)), // Recent history - live tracking
                ]);
            }
        }

        $this->command->info('Created ' . count($vehicles) . ' vehicles with locations');
    }

    private function getRandomLatitude(): float
    {
        // Tunisia latitude range: 30.2 to 37.5
        return rand(30200000, 37500000) / 1000000;
    }

    private function getRandomLongitude(): float
    {
        // Tunisia longitude range: 7.5 to 11.6
        return rand(7500000, 11600000) / 1000000;
    }
}