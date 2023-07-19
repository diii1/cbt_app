<?php

namespace App\Types\Entities;

class ClassEntity
{
    public ?string $name;
    public ?string $description;

    public function formRequest(array $validatedRequest)
    {
        $this->name = $validatedRequest['name'];
        $this->description = $validatedRequest['description'];
    }
}
