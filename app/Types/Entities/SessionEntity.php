<?php

namespace App\Types\Entities;

use Carbon\Carbon;

class SessionEntity
{
    public ?string $name;
    public ?string $time_start;
    public ?string $time_end;

    public function formRequest(array $validatedRequest)
    {
        $this->name = $validatedRequest['name'];
        $this->time_start = Carbon::parse($validatedRequest['time_start'])->format('H:i');
        $this->time_end = Carbon::parse($validatedRequest['time_end'])->format('H:i');
    }
}
