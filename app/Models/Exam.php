<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Session;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Classes;

class Exam extends Model
{
    use HasFactory;

    protected $table = "exams";
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'session_id',
        'subject_id',
        'teacher_id',
        'class_id',
        'subjet_name',
        'teacher_name',
        'class_name',
        'title',
        'code',
        'total_question',
        'date',
        'token',
        'expired_token',
        'type',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
}
