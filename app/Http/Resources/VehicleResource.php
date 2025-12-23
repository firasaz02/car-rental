<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'vehicle_number' => $this->vehicle_number,
            'driver_name' => $this->driver_name,
            'vehicle_type' => $this->vehicle_type,
            'phone' => $this->phone,
            'is_active' => (bool) $this->is_active,
            'image_url' => $this->image_url, // Use the accessor which handles URL conversion
            'latest_location' => $this->whenLoaded('latestLocation', function () {
                return new VehicleLocationResource($this->latestLocation);
            }),
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
