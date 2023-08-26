<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StudentRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->student)],
            'password' => ['required', Password::min(8), 'confirmed'],
            'nis' => ['required', 'string', 'max:255', Rule::unique('students', 'nis')->ignore($this->student)],
            'nisn' => ['required', 'string', 'max:255', Rule::unique('students', 'nisn')->ignore($this->student)],
            'address' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'string', 'max:1', Rule::in(['L', 'P'])],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
        ];
    }
}
