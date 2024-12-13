<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:locations,name|string|max:255',
            'type' => 'required|string',
            'description' => 'required|string',
            'address' => 'required|string',
        ]);

        $coordinates = $this->geocodeLocation($request->input('address'));

        if (!$coordinates) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $location = Location::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'address' => $request->address,
            'latitude' => $coordinates['lat'],
            'longitude' => $coordinates['lng'],
        ]);

        return response()->json($location, 201);
    }

    private function geocodeLocation($address)
    {
        $apiKey = env('OPENCAGE_API_KEY');

        $client = new Client();
        $response = $client->get('https://api.opencagedata.com/geocode/v1/json', [
            'query' => [
                'q' => $address,
                'key' => $apiKey,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['results'][0])) {
            return $data['results'][0]['geometry'];
        }

        return null;
    }
}
