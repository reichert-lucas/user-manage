<?php

namespace App\Services\Dto;

use Illuminate\Support\Facades\Hash;

class StoreUserDto extends BaseDto
{
    protected ?string $name;
    protected ?string $email;
    protected ?string $password;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return Hash::make($this->password);
    }
}
