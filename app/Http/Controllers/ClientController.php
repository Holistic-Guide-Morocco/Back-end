<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:user');
    }

    public function show()
    {
        return User::where('role', 'user')->get();
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
