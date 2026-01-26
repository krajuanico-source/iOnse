<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FamilyBackgroundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // SPOUSE
            'spouse_surname'            => 'nullable|string|max:255',
            'spouse_first_name'         => 'nullable|string|max:255',
            'spouse_middle_name'        => 'nullable|string|max:255',
            'spouse_extension_name'     => 'nullable|string|max:20',
            'spouse_occupation'         => 'nullable|string|max:255',
            'spouse_employer'           => 'nullable|string|max:255',
            'spouse_employer_address'   => 'nullable|string|max:255',
            'spouse_employer_telephone' => 'nullable|string|max:50',

            // FATHER
            'father_surname'            => 'nullable|string|max:255',
            'father_first_name'         => 'nullable|string|max:255',
            'father_middle_name'        => 'nullable|string|max:255',
            'father_extension_name'     => 'nullable|string|max:20',

            // MOTHER
            'mother_maiden_name'        => 'nullable|string|max:255',
            'mother_surname'            => 'nullable|string|max:255',
            'mother_first_name'         => 'nullable|string|max:255',
            'mother_middle_name'        => 'nullable|string|max:255',
            'mother_extension_name'     => 'nullable|string|max:20',

            // CHILDREN
            'children'                  => 'nullable|array',
            'children.*.id'             => 'nullable|integer|exists:children,id',
            'children.*.first_name'     => 'nullable|string|max:255',
            'children.*.middle_name'    => 'nullable|string|max:255',
            'children.*.last_name'      => 'nullable|string|max:255',
            'children.*.birthday'       => 'nullable|date',

            'deleted_children'          => 'nullable|string',
        ];
    }
}
