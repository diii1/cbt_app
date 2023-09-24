<?php

namespace App\Types\Entities;

use Illuminate\Support\Facades\Hash;

class AdminEntity extends UserEntity
{
    public ?string $user_id;
    public ?string $nip;
    public ?string $address;
    public ?string $phone;
    public ?string $profile;

    function formRequest(array $validatedRequest, string $profilePath = null)
    {
        // User
        $this->name = $validatedRequest['name'];
        $this->email = $validatedRequest['email'];
        $this->password = Hash::make($validatedRequest['password']);
        $this->profile = $profilePath;

        // Admin
        $this->nip = $validatedRequest['nip'];
        $this->address = $validatedRequest['address'];
        $this->phone = $validatedRequest['phone'];
    }

    function updateRequest(array $validatedRequest, string $profilePath = null)
    {
        // User
        $this->name = $validatedRequest['name'];
        $this->email = $validatedRequest['email'];
        $this->profile = $profilePath;

        // Admin
        $this->nip = $validatedRequest['nip'];
        $this->address = $validatedRequest['address'];
        $this->phone = $validatedRequest['phone'];
    }
}
