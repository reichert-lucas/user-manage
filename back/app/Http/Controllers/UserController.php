<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Dto\StoreUserDto;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;

        return UserResource::collection(
            User::search($search)->orderBy('name')->paginate($request->per_page ?? 12)
        );
    }
    
    public function store(StoreUserRequest $request)
    {
        $user = UserService::store(
            new StoreUserDto($request->only('email', 'password', 'name'))
        );

        return response()->json(new UserResource($user), 201);   
    }

    public function show(User $user)
    {
        return response()->json(new UserResource($user));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user = UserService::store(
            new StoreUserDto($request->only('email', 'password', 'name')),
            $user
        );

        return response()->json(new UserResource($user), 200);
    }

    public function destroy(User $user)
    {        
        $user->delete();

        return response()->json(null, 204);   
    }
}
