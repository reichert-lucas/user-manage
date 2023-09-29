<?php

namespace App\Services;

use App\Models\User;
use App\Services\Dto\StoreUserDto;

class UserService
{
    public static function store(StoreUserDto $userDto): User
    {
        $user = User::create($userDto->toArray());

        return $user;   
    }
}
