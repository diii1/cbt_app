<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'session_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'class_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'total_question' => ['required', 'integer'],
            'date' => ['required', 'date'],
            'type' => ['required', 'string', 'max:3'],
        ];
    }
}
