<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Service;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        if (auth()->user()->role === 'admin') {
            return Reservation::all();
        } elseif (auth()->user()->role === 'professional') {
            return Reservation::whereHas('service', function ($query) {
                $query->where('professional_id', auth()->id());
            })->get();
        } else {
            return Reservation::where('client_id', auth()->id())->get();
        }
    }

    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->client_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        return response()->json($reservation);
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'reservation_date' => 'required|date|after:now',
        ]);

        $service = Service::findOrFail($request->service_id);

        if (!$service->availability) {
            return response()->json(['message' => 'Service is not available'], 400);
        }

        $reservation = Reservation::create([
            'service_id' => $request->service_id,
            'client_id' => auth()->id(),
            'reservation_date' => $request->reservation_date,
            'status' => 'pending',
        ]);

        return response()->json($reservation, 201);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->client_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $request->validate([
            'reservation_date' => 'sometimes|date|after:now',
        ]);

        $reservation->update($request->only(['reservation_date']));

        return response()->json($reservation);
    }

    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $service = $reservation->service;

        if ($service->professional_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $request->validate([
            'status' => 'required|in:confirmed,canceled',
        ]);

        $reservation->update($request->only(['status']));

        return response()->json($reservation);
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->client_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted']);
    }
}
