<?php

namespace App\Types\Entities;

use Illuminate\Support\Facades\Hash;

class PasswordEntity
{
    public ?string $password;
    public ?string $password_confirmation;

    function formRequest(array $validatedRequest)
    {
        $this->password = Hash::make($validatedRequest['password']);
    }
}
