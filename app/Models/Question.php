<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Answer;

class Question extends Model
{
    use HasFactory;

    protected $table = "questions";
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'exam_id',
        'subject_id',
        'exam_title',
        'subject_name',
        'number',
        'question',
        'options',
        'answer',
        'created_by'
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function answer(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
