<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Dto\StoreUserDto;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{   
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Unauthorized.',
            ], 401);
        }
        
        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken('token')->plainTextToken,
        ], 200);   
    }

    public function me(Request $request)
    { 
        return response()->json([
            'user' => new UserResource($request->user()),
        ], 200);   
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(null, 200);
    }

    public function updateProfile(UpdateUserRequest $request)
    { 
        $user = UserService::store(
            new StoreUserDto($request->only('email', 'password', 'name')),
            $request->user()
        );

        return response()->json(new UserResource($user), 200); 
    }
}
