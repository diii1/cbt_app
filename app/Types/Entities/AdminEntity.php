<?php

namespace App\Types\Entities;

use Illuminate\Support\Facades\Hash;

class AdminEntity extends UserEntity
{
    public ?string $user_id;
    public ?string $nip;
    public ?string $address;
    public ?string $phone;

    function formRequest(array $validatedRequest)
    {
        // User
        $this->name = $validatedRequest['name'];
        $this->email = $validatedRequest['email'];
        $this->password = Hash::make($validatedRequest['password']);

        // Admin
        $this->nip = $validatedRequest['nip'];
        $this->address = $validatedRequest['address'];
        $this->phone = $validatedRequest['phone'];
    }
}
