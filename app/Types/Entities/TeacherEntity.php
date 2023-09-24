<?php

namespace App\Types\Entities;

use Illuminate\Support\Facades\Hash;

class TeacherEntity extends UserEntity
{
    public ?string $user_id;
    public ?string $subject_id;
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

        // Teacher
        $this->subject_id = $validatedRequest['subject_id'];
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

        // Teacher
        $this->subject_id = $validatedRequest['subject_id'];
        $this->nip = $validatedRequest['nip'];
        $this->address = $validatedRequest['address'];
        $this->phone = $validatedRequest['phone'];
    }
}
