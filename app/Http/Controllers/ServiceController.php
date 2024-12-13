<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\LocationController;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return Service::all();
    }

    public function show($id)
    {
        return Service::findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'availability' => 'required|boolean',
            'location_name' => 'required|string|max:255'
        ]);

        $location = Location::where('name', $request->location_name)->first();

        if (!$location) {
            $locationController = new LocationController();
            $locationRequest = new Request([
                'name' => $request->location_name,
                'type' => $request->location_type,
                'description' => $request->location_description,
                'address' => $request->location_address,
            ]);

            $locationResponse = $locationController->store($locationRequest);
            $location = $locationResponse->getData();
        }

        $service = Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'availability' => $request->availability,
            'location' => $location->id,
            'professional_id' => auth()->id(),
        ]);

        return response()->json($service, 201);
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        if ($service->professional_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'availability' => 'sometimes|boolean',
            'location_name' => 'sometimes|string|max:255'
        ]);

        if ($request->has('location_name')) {
            $location = Location::where('name', $request->location_name)->first();

            if (!$location) {
                $locationController = new LocationController();
                $locationRequest = new Request([
                    'name' => $request->location_name,
                    'type' => $request->location_type,
                    'description' => $request->location_description,
                    'address' => $request->location_address,
                ]);

                $locationResponse = $locationController->store($locationRequest);
                $location = $locationResponse->getData();
            }

            $request->merge(['location' => $location->id]);
        }

        $service->update($request->only(['name', 'description', 'price', 'availability', 'location']));

        return $service;
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        if ($service->professional_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $service->delete();

        return response()->json(['message' => 'Service deleted']);
    }

    public function showMyServices()
    {
        $services = Service::where('professional_id', auth()->id())->get();

        if ($services->isEmpty()) {
            return response()->json(['message' => 'You don\'t have any services'], 404);
        }

        return response()->json($services);
    }
}
