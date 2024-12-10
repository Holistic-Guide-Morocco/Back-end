<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfessionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:professional');
    }

    public function show()
    {
        return auth()->user();
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $user->update($request->only(['username', 'email', 'password']));

        return $user;
    }

    public function destroy()
    {
        $user = auth()->user();
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
