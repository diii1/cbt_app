<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Classes;
use App\Models\ExamParticipant;
use App\Models\Answer;
use App\Models\ExamResult;

class Student extends Model
{
    use HasFactory;

    protected $table = "students";
    protected $primaryKey = "user_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'address',
        'birth_date',
        'gender',
        'password',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }

    public function exam_participant(): HasMany
    {
        return $this->hasMany(ExamParticipant::class);
    }

    public function answer(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function result(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }
}
