<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;




    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required|digits:10|unique:users',
            'password' => 'required|string|min:6',
            'name' => 'required|string',
            'location' => 'string',
            'warehouse_id' => 'nullable',
            'role' => 'required|in:Pharmacist,Admin',
        ]);

        $user = new User();
        $user->phone = $validatedData['phone'];
        $user->password = bcrypt($validatedData['password']);
        $user->name = $validatedData['name'];
        $user->role = $validatedData['role'];

        if ($user->role === 'Pharmacist') {
            $user->location = $validatedData['location'];
        } elseif ($user->role === 'Admin') {
            $user->warehouse_id = $validatedData['warehouse_id'];
        }

        $user->save();

        $token = $user->createToken('AuthToken')->plainTextToken;
        return response()->json(['message' => 'Registration successful', 'user' => $user, 'token' => $token]);
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt(['phone' => $validatedData['phone'], 'password' => $validatedData['password']])) {
            $user = auth()->user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json(['message' => 'Login successful', 'token' => $token]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout successful']);
    }

    public function getAuthenticatedUserId()
    {
        return Auth::id();
    }


    public function test()
    {
        return 352;
    }

    public function test2()
    {
        return 3252;
    }
}
