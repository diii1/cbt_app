<?php

namespace App\Types\Entities;

class SubjectEntity
{
    public ?string $name;
    public ?string $code;
    public ?string $description;

    public function formRequest(array $validatedRequest)
    {
        $this->name = $validatedRequest['name'];
        $this->code = $validatedRequest['code'];
        $this->description = $validatedRequest['description'];
    }

    public function updateRequest(array $validatedRequest)
    {
        $this->name = $validatedRequest['name'];
        $this->description = $validatedRequest['description'];
    }
}
