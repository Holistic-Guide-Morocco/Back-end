<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

        $request->validate([
            'username' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'sometimes|string|min:8|different:old_password',
        ]);

        $data = $request->only(['username', 'email', 'password']);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);


        return $user;
    }

    public function destroy()
    {
        $user = auth()->user();
        $user->delete();

        return response()->json(['message' => 'Professional deleted']);
    }
}
