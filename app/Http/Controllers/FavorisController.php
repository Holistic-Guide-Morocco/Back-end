<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Service;
use Illuminate\Http\Request;

class FavorisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:user');
    }

    public function index()
    {
        $favorites = Favorite::where('client_id', auth()->id())->get();
        return response()->json($favorites);
    }

    public function show($id)
    {
        $favorite = Favorite::where('client_id', auth()->id())->findOrFail($id);
        return response()->json($favorite);
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        $favorite = Favorite::create([
            'service_id' => $request->service_id,
            'client_id' => auth()->id(),
            'date_added' => now(),
        ]);

        return response()->json($favorite, 201);
    }

    public function destroy($id)
    {
        $favorite = Favorite::where('client_id', auth()->id())->findOrFail($id);
        $favorite->delete();

        return response()->json(['message' => 'Favorite deleted']);
    }
}
