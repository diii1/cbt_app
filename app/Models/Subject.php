<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Teacher;
use App\Models\Exam;
use App\Models\Question;

class Subject extends Model
{
    use HasFactory;

    protected $table = "subjects";
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function teacher(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function exam(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function question(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
