<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => true,
        ]);

        $user->assignRole('customer');

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(
            'User registered successfully',
            [
                'user' => $user,
                'token' => $token
            ]
        );
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return $this->errorResponse(
                'Invalid credentials',
                401
            );
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(
            'Login successful',
            [
                'user' => $user,
                'token' => $token
            ]
        );
    }

    public function profile(Request $request)
    {
        return $this->successResponse(
            'Profile fetched successfully',
            $request->user()
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(
            'Logout successful'
        );
    }
}
