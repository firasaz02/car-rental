<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Http\Resources\VehicleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
	public function index(): JsonResponse
	{
		$vehicles = Vehicle::with('latestLocation')
			->where('is_active', true)
			->get();

		return response()->json([
			'success' => true,
			'data' => VehicleResource::collection($vehicles)
		]);
	}

	public function store(Request $request): JsonResponse
	{
		$validator = Validator::make($request->all(), [
			'vehicle_number' => 'required|string|unique:vehicles',
			'driver_name' => 'required|string',
			'vehicle_type' => 'required|string',
			'phone' => 'nullable|string',
			'vehicle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 422);
		}

		$data = $validator->validated();
		
		// Handle image upload
		if ($request->hasFile('vehicle_image')) {
			try {
				$image = $request->file('vehicle_image');
				
				// Additional validation
				if (!$image->isValid()) {
					throw new \Exception('Invalid image file');
				}
				
				// Generate unique filename
				$filename = 'vehicle_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
				$imagePath = $image->storeAs('vehicle-images', $filename, 'public');
				
				if (!$imagePath) {
					throw new \Exception('Failed to store image');
				}
				
				$data['image_url'] = Storage::url($imagePath);
			} catch (\Exception $e) {
				\Log::error('Image upload failed: ' . $e->getMessage());
				return response()->json([
					'success' => false,
					'message' => 'Image upload failed: ' . $e->getMessage()
				], 422);
			}
		}

		// Remove vehicle_image from data array as it's not a database field
		unset($data['vehicle_image']);

		$vehicle = Vehicle::create($data);

		return response()->json([
			'success' => true,
			'data' => new VehicleResource($vehicle),
			'message' => 'Vehicle created successfully'
		], 201);
	}

	public function show($id): JsonResponse
	{
		$vehicle = Vehicle::with(['latestLocation', 'locations' => function ($query) {
			$query->orderBy('recorded_at', 'desc')->limit(50);
		}])->find($id);

		if (!$vehicle) {
			return response()->json([
				'success' => false,
				'message' => 'Vehicle not found'
			], 404);
		}

		return response()->json([
			'success' => true,
			'data' => new VehicleResource($vehicle->load('latestLocation'))
		]);
	}

	public function update(Request $request, $id): JsonResponse
	{
		$vehicle = Vehicle::find($id);

		if (!$vehicle) {
			return response()->json([
				'success' => false,
				'message' => 'Vehicle not found'
			], 404);
		}

		$validator = Validator::make($request->all(), [
			'vehicle_number' => 'string|unique:vehicles,vehicle_number,' . $id,
			'driver_name' => 'string',
			'vehicle_type' => 'string',
			'phone' => 'nullable|string',
			'is_active' => 'boolean',
			'vehicle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 422);
		}

		$data = $validator->validated();
		
		// Handle image upload
		if ($request->hasFile('vehicle_image')) {
			try {
				$image = $request->file('vehicle_image');
				
				// Additional validation
				if (!$image->isValid()) {
					throw new \Exception('Invalid image file');
				}
				
				// Delete old image if exists
				if ($vehicle->image_url && strpos($vehicle->image_url, '/storage/') !== false) {
					$oldImagePath = str_replace('/storage/', '', $vehicle->image_url);
					Storage::disk('public')->delete($oldImagePath);
				}
				
				// Generate unique filename
				$filename = 'vehicle_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
				$imagePath = $image->storeAs('vehicle-images', $filename, 'public');
				
				if (!$imagePath) {
					throw new \Exception('Failed to store image');
				}
				
				$data['image_url'] = Storage::url($imagePath);
			} catch (\Exception $e) {
				\Log::error('Image upload failed: ' . $e->getMessage());
				return response()->json([
					'success' => false,
					'message' => 'Image upload failed: ' . $e->getMessage()
				], 422);
			}
		}

		// Remove vehicle_image from data array as it's not a database field
		unset($data['vehicle_image']);

		$vehicle->update($data);

		return response()->json([
			'success' => true,
			'data' => new VehicleResource($vehicle),
			'message' => 'Vehicle updated successfully'
		]);
	}

	public function destroy($id): JsonResponse
	{
		$vehicle = Vehicle::find($id);

		if (!$vehicle) {
			return response()->json([
				'success' => false,
				'message' => 'Vehicle not found'
			], 404);
		}

		$vehicle->delete();

		return response()->json([
			'success' => true,
			'message' => 'Vehicle deleted successfully'
		]);
	}
}

