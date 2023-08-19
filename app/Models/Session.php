<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Exam;

class Session extends Model
{
    use HasFactory;

    protected $table = "sessions";
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'time_start',
        'time_end',
    ];

    public function exam(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}
