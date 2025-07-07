<?php

namespace App\Imports;

use App\Models\User;
use App\Models\EmploymentStatus;
use App\Models\Division;
use App\Models\Section;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (User::where('employee_id', $row['employee_id'])->exists()) {
            Log::info('SKIPPED: duplicate employee_id ' . $row['employee_id']);
            return null;
        }

        $employmentStatus = EmploymentStatus::where('name', $row['employment_status'])->first();
        $division = Division::where('name', $row['division'])->first();
        $section = Section::where('name', $row['section'])->first();

        if (!$employmentStatus || !$division || !$section) {
            Log::warning('SKIPPED: missing references for ' . json_encode($row));
            return null;
        }

        return new User([
            'username' => strtolower($row['username']),
            'employee_id' => $row['employee_id'],
            'first_name' => ucwords(strtolower($row['first_name'])),
            'middle_name' => !empty($row['middle_name']) ? ucwords(strtolower($row['middle_name'])) : null,
            'last_name' => ucwords(strtolower($row['last_name'])),
            'gender' => $row['gender'],
            'extension_name' => !empty($row['extension_name']) ? ucwords(strtolower($row['extension_name'])) : null,
            'employment_status_id' => $employmentStatus->id,
            'division_id' => $division->id,
            'section_id' => $section->id,
            'email' => strtolower($row['email']),
            'password' => Hash::make('default123')
        ]);
    }
}
