<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GovernmentIdRequest extends FormRequest
{
    public function authorize()
    {
        return true; // You can add auth logic here if needed
    }

    public function rules()
    {
        return [
            'sss_id' => 'nullable|string|max:50',
            'gsis_id' => 'nullable|string|max:50',
            'pagibig_id' => 'nullable|string|max:50',
            'philhealth_id' => 'nullable|string|max:50',
            'tin' => 'nullable|string|max:50',
            'philsys' => 'nullable|string|max:50',
            'gov_issued_id' => 'nullable|string|max:100',
            'id_number' => 'nullable|string|max:100',
            'date_issuance' => 'nullable|date',
            'place_issuance' => 'nullable|string|max:150',
        ];
    }
}
