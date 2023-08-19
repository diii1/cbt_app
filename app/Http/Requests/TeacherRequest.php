<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class TeacherRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->teacher)],
            'password' => ['required', Password::min(8), 'confirmed'],
            'nip' => ['required', 'string', 'max:255', Rule::unique('teachers', 'nip')->ignore($this->teacher)],
            'address' => ['string', 'max:255'],
            'phone' => ['string', 'max:13'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
        ];
    }
}
