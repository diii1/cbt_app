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

    function formRequest(array $validatedRequest)
    {
        // User
        $this->name = $validatedRequest['name'];
        $this->email = $validatedRequest['email'];
        $this->password = Hash::make($validatedRequest['password']);

        // Teacher
        $this->subject_id = $validatedRequest['subject_id'];
        $this->nip = $validatedRequest['nip'];
        $this->address = $validatedRequest['address'];
        $this->phone = $validatedRequest['phone'];
    }

    function updateRequest(array $validatedRequest)
    {
        // User
        $this->name = $validatedRequest['name'];
        $this->email = $validatedRequest['email'];

        // Teacher
        $this->subject_id = $validatedRequest['subject_id'];
        $this->nip = $validatedRequest['nip'];
        $this->address = $validatedRequest['address'];
        $this->phone = $validatedRequest['phone'];
    }
}
