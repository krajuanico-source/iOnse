<?php

namespace App\Imports;

use App\Models\User;
use App\Models\EmploymentStatus;
use App\Models\Division;
use App\Models\Section;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeesImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row): void
    {
        $r = $row->toArray();

        // Skip if duplicate
        if (User::where('employee_id', $r['employee_id'])->exists()) {
            return;
        }

        $employmentStatus = EmploymentStatus::where('name', $r['employment_status'])->first();
        $division = Division::where('name', $r['division'])->first();
        $section = Section::where('name', $r['section'])->first();

        if (!$employmentStatus || !$division || !$section) {
            return;
        }

        User::create([
        'username'             => strtolower($r['username']), // lower by best practice
        'employee_id'          => strtoupper($r['employee_id']),
        'first_name'           => strtoupper($r['first_name'] ?? ''),
        'middle_name'          => !empty($r['middle_name']) ? strtoupper($r['middle_name']) : null,
        'last_name'            => strtoupper($r['last_name'] ?? ''),
        'gender'               => strtoupper($r['gender']),
        'extension_name'       => !empty($r['extension_name']) ? strtoupper($r['extension_name']) : null,
        'employment_status_id' => $employmentStatus->id,
        'division_id'          => $division->id,
        'section_id'           => $section->id,
        'email'                => strtolower($r['email'] ?? ''), // emails are case-insensitive
        'password'             => Hash::make('dswd12345'),
        ]);
    }
}
