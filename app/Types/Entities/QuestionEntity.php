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
    public ?string $option_a;
    public ?string $option_b;
    public ?string $option_c;
    public ?string $option_d;
    public ?string $option_e;
    public ?string $answer;
    public ?int $created_by;

    function formRequest(array $validatedRequest)
    {
        $this->exam_id = $validatedRequest['exam_id'];
        $this->subject_id = $validatedRequest['subject_id'];
        $this->exam_title = $validatedRequest['exam_title'];
        $this->subject_name = $validatedRequest['subject_name'];
        $this->question = $validatedRequest['question'];
        $this->option_a = $validatedRequest['option_a'];
        $this->option_b = $validatedRequest['option_b'];
        $this->option_c = $validatedRequest['option_c'];
        $this->option_d = $validatedRequest['option_d'];
        $this->option_e = $validatedRequest['option_e'];
        $this->answer = $validatedRequest['answer'];
        $this->created_by = $validatedRequest['created_by'];
    }
}
