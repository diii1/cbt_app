<?php

namespace App\Types\Entities;

class QuestionEntity
{
    public ?int $exam_id;
    public ?int $subject_id;
    public ?string $exam_title;
    public ?string $subject_name;
    public ?int $number;
    public ?string $question;
    public ?string $options;
    public ?string $answer;
    public ?int $created_by;

    function formRequest(array $validatedRequest)
    {
        $this->exam_id = $validatedRequest['exam_id'];
        $this->subject_id = $validatedRequest['subject_id'];
        $this->exam_title = $validatedRequest['exam_title'];
        $this->subject_name = $validatedRequest['subject_name'];
        $this->number = $validatedRequest['number'];
        $this->question = $validatedRequest['question'];
        $this->options = $validatedRequest['options'];
        $this->answer = $validatedRequest['answer'];
        $this->created_by = $validatedRequest['created_by'];
    }
}
