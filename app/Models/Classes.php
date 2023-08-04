<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Student;
use App\Models\Exam;

class Classes extends Model
{
    use HasFactory;

    protected $table = "classes";
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function student(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function exam(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}
