<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\Dto\StoreUserDto;
use App\Services\UserService;

class AuthController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $user = UserService::store(
            new StoreUserDto($request->only('email', 'password', 'name'))
        );

        return response()->json(new UserResource($user), 201);   
    }

}
