<?php

namespace App\Types\Entities;

class SchoolProfileEntity
{
    public ?string $name;
    public ?string $contact;
    public ?string $email;
    public ?string $address;
    public ?string $district;
    public ?string $regency;
    public ?string $province;
    public ?string $acreditation;
    public ?string $logo;
    public ?string $bg;

    function formRequest(array $validatedRequest, string $logoPath = null, string $bgPath = null)
    {
        $this->name = $validatedRequest['name'];
        $this->contact = $validatedRequest['contact'];
        $this->email = $validatedRequest['email'];
        $this->address = $validatedRequest['address'];
        $this->district = $validatedRequest['district'];
        $this->regency = $validatedRequest['regency'];
        $this->province = $validatedRequest['province'];
        $this->acreditation = $validatedRequest['acreditation'];
        $this->logo = $logoPath;
        $this->bg = $bgPath;
    }
}
