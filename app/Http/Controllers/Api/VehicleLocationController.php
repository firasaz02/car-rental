<?php

namespace App\Http\Controllers\Api;

use App\Events\VehicleLocationUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleLocationResource;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use App\Models\VehicleLocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleLocationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'speed' => 'nullable|integer|min:0',
            'heading' => 'nullable|integer|between:0,360',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['recorded_at'] = now();

        $location = VehicleLocation::create($data);

        // Broadcast the location update
        broadcast(new VehicleLocationUpdated($location))->toOthers();

        return response()->json([
            'success' => true,
            'data' => new VehicleLocationResource($location),
            'message' => 'Location updated successfully'
        ], 201);
    }

    public function getVehicleHistory($vehicleId): JsonResponse
    {
        $vehicle = Vehicle::find($vehicleId);

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], 404);
        }

        $locations = VehicleLocation::where('vehicle_id', $vehicleId)
            ->orderBy('recorded_at', 'desc')
            ->limit(100)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'vehicle' => new VehicleResource($vehicle),
                'locations' => VehicleLocationResource::collection($locations)
            ]
        ]);
    }

    public function getAllCurrentLocations(): JsonResponse
    {
        $vehicles = Vehicle::with('latestLocation')
            ->where('is_active', true)
            ->get()
            ->filter(fn($vehicle) => $vehicle->latestLocation !== null);

        return response()->json([
            'success' => true,
            'data' => VehicleResource::collection($vehicles)
        ]);
    }
}
