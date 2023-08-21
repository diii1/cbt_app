<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Exam;
use App\Models\Student;

class ExamResult extends Model
{
    use HasFactory;

    protected $table = "exam_results";
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'exam_id',
        'student_id',
        'score',
    ];

    /**
     * Get the exam that owns the Result
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    /**
     * Get the student that owns the Answer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }
}
