<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:user');
    }

    public function show($service_id)
    {
        $feedbacks = Feedback::where('service_id', $service_id)
            ->get();

        if ($feedbacks->isEmpty()) {
            return response()->json(['message' => 'No feedback found for this service'], 404);
        }

        return response()->json($feedbacks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $feedback = Feedback::create([
            'service_id' => $request->service_id,
            'client_id' => auth()->id(),
            'comment' => $request->comment,
            'rating' => $request->rating,
            'date' => now(),
        ]);

        return response()->json($feedback, 201);
    }

    public function update(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);

        if ($feedback->client_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $request->validate([
            'comment' => 'sometimes|string',
            'rating' => 'sometimes|integer|min:1|max:5',
        ]);

        $feedback->update($request->only(['comment', 'rating']));

        return response()->json($feedback);
    }

    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);

        if ($feedback->client_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $feedback->delete();

        return response()->json(['message' => 'Feedback deleted']);
    }
}
