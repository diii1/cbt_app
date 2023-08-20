<?php

namespace App\Types\Entities;

use Carbon\Carbon;

class ExamEntity
{
    public ?int $session_id;
    public ?int $subject_id;
    public ?int $teacher_id;
    public ?int $class_id;
    public ?string $subject_name;
    public ?string $teacher_name;
    public ?string $class_name;
    public ?string $title;
    public ?string $code;
    public ?int $total_question;
    public ?string $date;
    public ?int $token;
    public ?string $expired_token;
    public ?int $total_question_step;
    public ?float $min_score;
    public ?string $type;

    function formRequest(array $validatedRequest)
    {
        $this->session_id = $validatedRequest['session_id'];
        $this->subject_id = $validatedRequest['subject_id'];
        $this->teacher_id = $validatedRequest['teacher_id'];
        $this->class_id = $validatedRequest['class_id'];
        $this->subject_name = $validatedRequest['subject_name'];
        $this->teacher_name = $validatedRequest['teacher_name'];
        $this->class_name = $validatedRequest['class_name'];
        $this->title = $validatedRequest['title'];
        $this->code = $validatedRequest['code'];
        $this->total_question = $validatedRequest['total_question'];
        $this->date = Carbon::parse($validatedRequest['date'])->format('Y-m-d');
        $this->token = $validatedRequest['token'];
        $this->expired_token = Carbon::parse($validatedRequest['expired_token']);
        $this->total_question_step = $validatedRequest['total_question_step'];
        $this->min_score = $validatedRequest['min_score'];
        $this->type = $validatedRequest['type'];
    }
}
