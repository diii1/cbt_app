<?php

namespace App\Types\Entities;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class StudentEntity extends UserEntity
{
    public ?string $user_id;
    public ?string $class_id;
    public ?string $nis;
    public ?string $nisn;
    public ?string $address;
    public ?string $birth_date;
    public ?string $gender;
    public ?string $passwordStudent;

    function formRequest(array $validatedRequest)
    {
        // User
        $this->name = $validatedRequest['name'];
        $this->email = $validatedRequest['email'];
        $this->password = Hash::make($validatedRequest['password']);

        // Student
        $this->class_id = $validatedRequest['class_id'];
        $this->nis = $validatedRequest['nis'];
        $this->nisn = $validatedRequest['nisn'];
        $this->address = $validatedRequest['address'];
        $this->birth_date = Carbon::parse($validatedRequest['birth_date']);
        $this->gender = $validatedRequest['gender'];
        $this->passwordStudent = Crypt::encryptString($validatedRequest['password']);
    }

    function updateRequest(array $validatedRequest)
    {
        // User
        $this->name = $validatedRequest['name'];
        $this->email = $validatedRequest['email'];

        // Student
        $this->class_id = $validatedRequest['class_id'];
        $this->nis = $validatedRequest['nis'];
        $this->nisn = $validatedRequest['nisn'];
        $this->address = $validatedRequest['address'];
        $this->birth_date = Carbon::parse($validatedRequest['birth_date']);
        $this->gender = $validatedRequest['gender'];
    }
}
