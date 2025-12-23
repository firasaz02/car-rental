<?php

namespace App\Events;

use App\Models\VehicleLocation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VehicleLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $vehicleLocation;

    public function __construct(VehicleLocation $vehicleLocation)
    {
        $this->vehicleLocation = $vehicleLocation->load('vehicle');
    }

    public function broadcastOn(): Channel
    {
        return new Channel('vehicle-tracking');
    }

    public function broadcastAs(): string
    {
        return 'location.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'vehicle_id' => $this->vehicleLocation->vehicle_id,
            'vehicle_number' => $this->vehicleLocation->vehicle->vehicle_number,
            'driver_name' => $this->vehicleLocation->vehicle->driver_name,
            'latitude' => $this->vehicleLocation->latitude,
            'longitude' => $this->vehicleLocation->longitude,
            'speed' => $this->vehicleLocation->speed,
            'heading' => $this->vehicleLocation->heading,
            'recorded_at' => $this->vehicleLocation->recorded_at,
        ];
    }
}
