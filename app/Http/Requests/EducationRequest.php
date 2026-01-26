<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // only logged-in users
    }

    public function rules(): array
    {
        return [
            'level_of_education'   => 'required|string|max:255',
            'school_name'          => 'required|string|max:255',
            'degree_course'        => 'nullable|string|max:255',
            'from'                 => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'to'                   => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'highest_level_earned' => 'nullable|string|max:255',
            'year_graduated'       => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'scholarship_honors'   => 'nullable|string|max:255',
        ];
    }
}
