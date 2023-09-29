<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\Dto\StoreUserDto;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = new StoreUserDto([
            'name' => 'primeiro usuário',
            'email' => 'primeiro_usuario@test.com',
        ]);

        User::factory()->create($user->toArray());
    }
}
