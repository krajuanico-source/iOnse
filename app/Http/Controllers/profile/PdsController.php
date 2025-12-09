<?php

namespace App\Http\Controllers\Profile;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Fpdi;

class PdsController extends Controller
{
    public function fillPdf()
    {
        $employee = Auth::user();

        if (!$employee) {
            abort(403, 'Unauthorized');
        }

        $pdf = new FPDI();

        // Load template
        $templatePath = public_path('pdf/PDS.pdf');
        $pageCount = $pdf->setSourceFile($templatePath);

        // Helper: Auto-fit MultiCell centered horizontally
        $writeAutoFitMultiCell = function($pdf, $x, $y, $width, $lineHeight, $text, $maxFontSize = 7, $minFontSize = 4) {
            $pdf->SetXY($x, $y);
            $text = strtoupper($text ?? '');
            $fontSize = $maxFontSize;
            $pdf->SetFont('Helvetica', 'B', $fontSize);

            while ($pdf->GetStringWidth($text) > $width && $fontSize > $minFontSize) {
                $fontSize -= 0.1;
                $pdf->SetFont('Helvetica', 'B', $fontSize);
            }

            // Split text into lines if too long
            $words = explode(' ', $text);
            $lines = [];
            $currentLine = '';
            foreach ($words as $word) {
                $testLine = $currentLine ? $currentLine . ' ' . $word : $word;
                if ($pdf->GetStringWidth($testLine) > $width) {
                    if ($currentLine) $lines[] = $currentLine;
                    $currentLine = $word;
                } else {
                    $currentLine = $testLine;
                }
            }
            if ($currentLine) $lines[] = $currentLine;

            foreach ($lines as $line) {
                $lineWidth = $pdf->GetStringWidth($line);
                $xCentered = $x + max(0, ($width - $lineWidth)/2);
                $pdf->SetXY($xCentered, $y);
                $pdf->Write($lineHeight, $line);
                $y += $lineHeight;
            }
        };

        // Loop through pages
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $pdf->AddPage();
            $pdf->useTemplate($templateId);

            $pdf->SetFont('Helvetica', 'B', 7);
            $pdf->SetTextColor(0,0,0);

            if ($pageNo === 1) {
                $this->fillPageOne($pdf, $employee, $writeAutoFitMultiCell);
            } elseif ($pageNo === 2) {
                $this->fillPageTwo($pdf, $employee, $writeAutoFitMultiCell);
            } elseif ($pageNo === 3) {
                $this->fillPageThree($pdf, $employee, $writeAutoFitMultiCell);
            } elseif ($pageNo === 4) {
        $this->fillPageFour($pdf, $employee, $writeAutoFitMultiCell); // ← new
    }
            
        }

        $filename = 'PDS-' . strtoupper($employee->last_name ?? 'USER') . '.pdf';
        $pdf->Output('I', $filename);
    }

    private function fillPageOne($pdf, $employee, $writeAutoFitMultiCell)
    {
        // Basic Info
        $pdf->SetXY(46, 42.5); $pdf->Write(0, strtoupper($employee->last_name));
        $pdf->SetXY(46, 48.5); $pdf->Write(0, strtoupper($employee->first_name));
        $pdf->SetXY(180, 48.5); $pdf->Write(0, strtoupper($employee->extension_name));
        $pdf->SetXY(46, 54); $pdf->Write(0, strtoupper($employee->middle_name));
        $birthday = $employee->birthday ? Carbon::parse($employee->birthday)->format('d/m/Y') : '';
        $pdf->SetXY(46, 61); $pdf->Write(0, $birthday);
        $pdf->SetXY(46, 69); $pdf->Write(0, strtoupper($employee->place_of_birth));
        $writeAutoFitMultiCell($pdf, 140, 73, 40, 4, strtoupper($employee->perm_country ?? ''));


        // Gender
        $genderMap = ['male' => [44, 75], 'female' => [71.5, 75]];
        $gender = strtolower($employee->gender);
        if (isset($genderMap[$gender])) {
            $pdf->SetXY(...$genderMap[$gender]);
            $pdf->Write(0, '/');
        }

        // Civil Status
        $civilMap = [
            'single' => [44, 80.5],
            'married' => [71.5, 80.5],
            'widowed' => [44, 84],
            'separated' => [71.5, 84],
            'others' => [44, 88],
        ];
        $civilStatus = strtolower($employee->civil_status);
        if (isset($civilMap[$civilStatus])) {
            $pdf->SetXY(...$civilMap[$civilStatus]);
            $pdf->Write(0, '/');
        }


        // Citizenship
        $this->fillCitizenship($pdf, $employee);

        // Address
        $this->fillAddresses($pdf, $employee, $writeAutoFitMultiCell);

        // Physical Attributes
        $pdf->SetXY(46, 93.5); $pdf->Write(0, strtoupper($employee->height));
        $pdf->SetXY(46, 99.5); $pdf->Write(0, strtoupper($employee->weight));
        $pdf->SetXY(46, 105.5); $pdf->Write(0, strtoupper($employee->blood_type));

        // Government IDs
        $govId = $employee->governmentIds->first();
        $pdf->SetXY(46, 111.5); $pdf->Write(0, strtoupper($govId->sss_id ?? ''));
        $pdf->SetXY(46, 118.5); $pdf->Write(0, strtoupper($govId->pagibig_id ?? ''));
        $pdf->SetXY(46, 124.5); $pdf->Write(0, strtoupper($govId->philhealth_id ?? ''));
        $pdf->SetXY(46, 131); $pdf->Write(0, strtoupper($govId->philsys ?? ''));
        $pdf->SetXY(46, 137.5); $pdf->Write(0, strtoupper($govId->tin ?? ''));
        $pdf->SetXY(46, 143.5); $pdf->Write(0, strtoupper($employee->employee_id ?? ''));

        // Contact Info
        $pdf->SetXY(119, 131); $pdf->Write(0, strtoupper($employee->tel_no ?? ''));
        $pdf->SetXY(119, 137); $pdf->Write(0, strtoupper($employee->mobile_no ?? ''));
        $pdf->SetXY(119, 143.5); $pdf->Write(0, strtoupper($employee->email ?? ''));

        // Family Background
        $famBackground = $employee->familyBackgrounds->first();
        if ($famBackground) {
            $this->fillFamily($pdf, $famBackground, $writeAutoFitMultiCell);
        }

        // Educational Background
        $educations = $employee->educations()->orderBy('level_of_education')->get();
        $this->fillEducation($pdf, $educations, $writeAutoFitMultiCell);
    }

       private function fillCitizenship($pdf, $employee)
        {
            $dual = strtolower($employee->dual_citizenship);

            // If Filipino (not dual citizen)
            if ($dual === 'no') {
                // Mark Filipino checkbox
                $pdf->SetXY(133, 61);
                $pdf->Write(0, '/');
                return; // Stop here
            }

            // If Dual Citizenship YES
            if ($dual === 'yes') {
                $pdf->SetXY(50, 90);
                $pdf->Write(0, 'X');

                $type = strtolower($employee->citizenship_type);

                if ($type === 'by_birth') {
                    $pdf->SetXY(70, 90);
                    $pdf->Write(0, 'X');
                }

                if ($type === 'by_naturalization') {
                    $pdf->SetXY(90, 90);
                    $pdf->Write(0, 'X');
                }
            }
        }


    private function fillAddresses($pdf, $employee, $writeAutoFitMultiCell)
    {
        // Residential
        $writeAutoFitMultiCell($pdf, 114, 78.5, 40, 4, strtoupper($employee->res_house_no ?? ''));
        $writeAutoFitMultiCell($pdf, 157, 78.5, 40, 4, strtoupper($employee->res_street ?? ''));
        $writeAutoFitMultiCell($pdf, 114, 84.5, 40, 4, strtoupper($employee->res_village ?? ''));
        $writeAutoFitMultiCell($pdf, 157, 84.5, 40, 4, strtoupper($employee->resBarangay->name ?? ''));
        $writeAutoFitMultiCell($pdf, 114, 90.5, 40, 4, strtoupper($employee->resCity->name ?? ''));
        $writeAutoFitMultiCell($pdf, 157, 90.5, 40, 4, strtoupper($employee->resProvince->name ?? ''));
        $writeAutoFitMultiCell($pdf, 102, 98, 40, 4, strtoupper($employee->res_zipcode ?? ''));

        // Permanent
        $writeAutoFitMultiCell($pdf, 114, 102.5, 40, 4, strtoupper($employee->perm_house_no ?? ''));
        $writeAutoFitMultiCell($pdf, 157, 102.5, 40, 4, strtoupper($employee->perm_street ?? ''));
        $writeAutoFitMultiCell($pdf, 114, 109, 40, 4, strtoupper($employee->perm_village ?? ''));
        $writeAutoFitMultiCell($pdf, 157, 109, 40, 4, strtoupper($employee->permBarangay->name ?? ''));
        $writeAutoFitMultiCell($pdf, 114, 115, 40, 4, strtoupper($employee->permCity->name ?? ''));
        $writeAutoFitMultiCell($pdf, 157, 115, 40, 4, strtoupper($employee->permProvince->name ?? ''));
        $writeAutoFitMultiCell($pdf, 102, 123, 40, 4, strtoupper($employee->perm_zipcode ?? ''));
    }

    private function fillFamily($pdf, $famBackground, $writeAutoFitMultiCell)
    {
        // Spouse
        $pdf->SetXY(46, 153.5); $pdf->Write(0, strtoupper($famBackground->spouse_surname ?? ''));
        $pdf->SetXY(46, 158.5); $pdf->Write(0, strtoupper($famBackground->spouse_first_name ?? ''));
        $pdf->SetXY(46, 164); $pdf->Write(0, strtoupper($famBackground->spouse_middle_name ?? ''));
        $pdf->SetXY(110, 158.5); $pdf->Write(0, strtoupper($famBackground->spouse_extension_name ?? ''));
        $pdf->SetXY(46, 169.5); $pdf->Write(0, strtoupper($famBackground->spouse_occupation ?? ''));
        $pdf->SetXY(46, 174.5); $pdf->Write(0, strtoupper($famBackground->spouse_employer ?? ''));
        $pdf->SetXY(46, 180); $pdf->Write(0, strtoupper($famBackground->spouse_employer_address ?? ''));
        $pdf->SetXY(46, 185); $pdf->Write(0, strtoupper($famBackground->spouse_employer_telephone ?? ''));

        // Father
        $pdf->SetXY(46, 190.5); $pdf->Write(0, strtoupper($famBackground->father_surname ?? ''));
        $pdf->SetXY(46, 196); $pdf->Write(0, strtoupper($famBackground->father_first_name ?? ''));
        $pdf->SetXY(46, 201); $pdf->Write(0, strtoupper($famBackground->father_middle_name ?? ''));
        $pdf->SetXY(110, 196); $pdf->Write(0, strtoupper($famBackground->father_extension_name ?? ''));

        // Mother
        $pdf->SetXY(46, 206.5); $pdf->Write(0, strtoupper($famBackground->mother_maiden_name ?? ''));
        $pdf->SetXY(46, 211.7); $pdf->Write(0, strtoupper($famBackground->mother_surname ?? ''));
        $pdf->SetXY(46, 217); $pdf->Write(0, strtoupper($famBackground->mother_first_name ?? ''));
        $pdf->SetXY(46, 222); $pdf->Write(0, strtoupper($famBackground->mother_middle_name ?? ''));

        // Children
        $children = optional($famBackground)->children ?? collect();
        $startY = 157.5; $lineHeight = 2; $xName = 120.5; $xBirthday = 165.5;
        $cellWidthName = 45; $cellWidthBirthday = 40;

        foreach ($children as $child) {
            $childFullName = strtoupper(trim(($child->first_name ?? '') . ' ' . ($child->middle_name ?? '') . ' ' . ($child->last_name ?? '') . ' ' . ($child->ext ?? '')));
            $writeAutoFitMultiCell($pdf, $xName, $startY, $cellWidthName, $lineHeight, $childFullName);
            $birthday = $child->birthday ? Carbon::parse($child->birthday)->format('d/m/Y') : '';
            $writeAutoFitMultiCell($pdf, $xBirthday, $startY, $cellWidthBirthday, $lineHeight, strtoupper($birthday));
            $startY += $lineHeight * ceil(strlen($childFullName) / 15);
        }
    }

    private function fillEducation($pdf, $educations, $writeAutoFitMultiCell)
    {
        $positions = [
            'ELEMENTARY' => 244,
            'HIGH SCHOOL' => 251,
            'SECONDARY' => 251,
            'VOCATIONAL' => 259,
            'TRADE SCHOOL' => 259,
            'COLLEGE' => 265.5,
            'GRADUATE' => 273,
        ];
        $lineHeight = 1.3;

        foreach ($educations as $edu) {
            $level = strtoupper($edu->level_of_education ?? '');
            if (!isset($positions[$level])) continue;

            $startY = $positions[$level];
            $writeAutoFitMultiCell($pdf, 44.5, $startY, 40, $lineHeight, strtoupper($edu->school_name ?? ''));
            $writeAutoFitMultiCell($pdf, 88.5, $startY, 40, $lineHeight, strtoupper($edu->degree_course ?? ''));
            $writeAutoFitMultiCell($pdf, 115, $startY, 40, $lineHeight, strtoupper($edu->from ?? ''));
            $writeAutoFitMultiCell($pdf, 127, $startY, 40, $lineHeight, strtoupper($edu->to ?? ''));
            $writeAutoFitMultiCell($pdf, 156.5, $startY, 10, $lineHeight, strtoupper($edu->highest_level_earned ?? ''));
            $writeAutoFitMultiCell($pdf, 157, $startY, 40, $lineHeight, strtoupper($edu->year_graduated ?? ''));
            $writeAutoFitMultiCell($pdf, 187, $startY, 10, $lineHeight, strtoupper($edu->scholarship_honors ?? ''));
        }
    }

    private function fillPageTwo($pdf, $employee, $writeAutoFitMultiCell)
    {
        // Civil Service Eligibility
        $csEligibilities = $employee->csEligibilities()->get();
        $startY = 21; $lineHeight = 6;

        foreach ($csEligibilities as $cs) {
            $writeAutoFitMultiCell($pdf, 25, $startY, 50, $lineHeight, strtoupper($cs->eligibility ?? ''));
            $writeAutoFitMultiCell($pdf, 80, $startY, 20, $lineHeight, strtoupper($cs->rating ?? ''));
            $examDate = $cs->exam_date ? Carbon::parse($cs->exam_date)->format('d/m/Y') : '';
            $writeAutoFitMultiCell($pdf, 99, $startY, 25, $lineHeight, $examDate);
            $writeAutoFitMultiCell($pdf, 117, $startY, 40, $lineHeight, strtoupper($cs->exam_place ?? ''));
            $writeAutoFitMultiCell($pdf, 150.5, $startY, 20, $lineHeight, strtoupper($cs->license_number ?? ''));
            $validity = $cs->license_validity ? Carbon::parse($cs->license_validity)->format('d/m/Y') : '';
            $writeAutoFitMultiCell($pdf, 166, $startY, 25, $lineHeight, $validity);
            $startY += $lineHeight + 1;
        }

        // Work Experience
        $workExperiences = $employee->workExperiences()->orderBy('date_from')->get();
        $startY = 98;
        $lineHeight = 5.5;

        foreach ($workExperiences as $work) {
            $from = $work->date_from? Carbon::parse($work->date_from)->format('d/m/Y') : '';
            $writeAutoFitMultiCell($pdf, 15.5, $startY, 25, $lineHeight, $from);
            $to = $work->date_to ? Carbon::parse($work->date_to)->format('d/m/Y') : '';
            $writeAutoFitMultiCell($pdf, 31.5, $startY, 25, $lineHeight, $to);
            $writeAutoFitMultiCell($pdf, 57, $startY, 40, $lineHeight, strtoupper($work->position_title ?? ''));
            $writeAutoFitMultiCell($pdf, 106, $startY, 40, $lineHeight, strtoupper($work->department_agency ?? ''));
            $writeAutoFitMultiCell($pdf, 153, $startY, 15, $lineHeight, strtoupper($work->status_of_appointment ?? ''));
            $writeAutoFitMultiCell($pdf, 171, $startY, 15, $lineHeight, strtoupper($work->govt_service ?? ''));
            $startY += $lineHeight + 1;
        }
    }
        private function fillPageThree($pdf, $employee, $writeAutoFitMultiCell)
        {
            // Voluntary Works
            $voluntaryWorks = $employee->voluntaryWorks()->orderBy('date_from')->get();

            $startY = 25.5;
            $lineHeight = 5.6;

            foreach ($voluntaryWorks as $vwork) {
                $writeAutoFitMultiCell($pdf, 25,  $startY, 50, $lineHeight, strtoupper($vwork->organization_name));
                $writeAutoFitMultiCell($pdf, 87,  $startY, 25, $lineHeight, $vwork->date_from ? Carbon::parse($vwork->date_from)->format('d/m/Y') : '');
                $writeAutoFitMultiCell($pdf, 101.5, $startY, 25, $lineHeight, $vwork->date_to ? Carbon::parse($vwork->date_to)->format('d/m/Y') : '');
                $writeAutoFitMultiCell($pdf, 116, $startY, 25, $lineHeight, strtoupper($vwork->number_of_hours));
                $writeAutoFitMultiCell($pdf, 143, $startY, 50, $lineHeight, strtoupper($vwork->position_nature_of_work));

                $startY += $lineHeight + 1;
            }
            // Learning and Development
            $learningAndDevelopments = $employee->learningAndDevelopments()->get();

            $startY = 93;
            $lineHeight = 5.5;

            foreach ($learningAndDevelopments as $LD) {
                $writeAutoFitMultiCell($pdf, 25,  $startY, 50, $lineHeight, strtoupper($LD->title));
                $writeAutoFitMultiCell($pdf, 87,  $startY, 25, $lineHeight, $LD->inclusive_date_from ? Carbon::parse($LD->inclusive_date_from)->format('d/m/Y') : '');
                $writeAutoFitMultiCell($pdf, 101.5, $startY, 25, $lineHeight, $LD->inclusive_date_to ? Carbon::parse($LD->inclusive_date_to)->format('d/m/Y') : '');
                $writeAutoFitMultiCell($pdf, 116, $startY, 25, $lineHeight, strtoupper($LD->number_of_hours));
                $writeAutoFitMultiCell($pdf, 137, $startY, 15, $lineHeight, strtoupper($LD->type_of_ld));
                $writeAutoFitMultiCell($pdf, 165, $startY, 25, $lineHeight, strtoupper($LD->conducted_by));

                $startY += $lineHeight + 1;
            }

            // Skills
            $skills = $employee->skills()->get();
            $startY = 237;
            $startX = 21;
            $lineHeight = 5.3;

            foreach ($skills as $skill) {
                $writeAutoFitMultiCell($pdf, $startX, $startY, 25, $lineHeight, strtoupper($skill->skill_name));
                $startY += $lineHeight + 1;
            }
            
            // Non-Academics
            $nonAcademics = $employee->nonAcademics()->get();
            $startY = 237; 
            $startX = 72;
            $lineHeight = 5.3;

            foreach ($nonAcademics as $nonAcademic) {
                $writeAutoFitMultiCell($pdf, $startX, $startY, 70, $lineHeight, strtoupper($nonAcademic->recognition));
                $startY += $lineHeight + 1;
            }
            
            // Organization
            $organizations = $employee->organizations()->get();
            $startY = 237; 
            $startX = 166;
            $lineHeight = 5.3;

            foreach ($organizations as $organization) {
                $writeAutoFitMultiCell($pdf, $startX, $startY, 25, $lineHeight, strtoupper($organization->organization_name));
                $startY += $lineHeight + 1;
            }
        }
         private function fillPageFour($pdf, $employee, $writeAutoFitMultiCell)
        {
        $startY = 186;
        $lineHeight = 5.5;

        // References
        $references = $employee->references()->get(); // Make sure you have this relation
        foreach ($references as $ref) {
            $writeAutoFitMultiCell($pdf, 16, $startY, 60, $lineHeight, strtoupper($ref->name ?? ''));
            $writeAutoFitMultiCell($pdf, 93, $startY, 25, $lineHeight, strtoupper($ref->ref_address ?? ''));
            $writeAutoFitMultiCell($pdf, 130, $startY, 20, $lineHeight, strtoupper($ref->contact_number ?? ''));
            $startY += $lineHeight + 1;

        }
            $govId = $employee->governmentIds->first();
            $writeAutoFitMultiCell($pdf, 29, 239, 30, 4, strtoupper($govId->gov_issued_id ?? ''));
            $writeAutoFitMultiCell($pdf, 34, 245, 30, 4, strtoupper($govId->id_number ?? ''));

            $issuedDate = $govId->date_issuance ? Carbon::parse($govId->date_issuance)->format('d/m/Y') : '';
            $issuedPlace = strtoupper($govId->place_issuance ?? '');

            // Concatenate date and place
            $concatText = trim("$issuedDate | $issuedPlace");

            $writeAutoFitMultiCell($pdf, 37, 251, 30, 4, $concatText);
            
            $employee->otherInformations()->first();

            // Third-degree YES/NO coordinates
            $thirdDegreeMap = [
                'yes' => [133, 24],
                'no'  => [153, 24]
            ];

            $thirdDegree = strtolower(trim($otherInfo->related_within_third_degree ?? 'no'));

            if (isset($thirdDegreeMap[$thirdDegree])) {
                $pdf->SetXY(...$thirdDegreeMap[$thirdDegree]);
                $pdf->Write(0, '/');
            }


            // Fourth-degree YES/NO coordinates
            $fourthDegreeMap = [
                'yes' => [133, 29],
                'no'  => [153, 29]
            ];

            $fourthDegree = strtolower(trim($otherInfo->related_within_fourth_degree ?? 'no'));

            if (isset($fourthDegreeMap[$fourthDegree])) {
                $pdf->SetXY(...$fourthDegreeMap[$fourthDegree]);
                $pdf->Write(0, '/');

                if ($fourthDegree === 'yes') {
                    $Fdetails = strtoupper($otherInfo->related_within_fourth_degree_details ?? '');
                    $writeAutoFitMultiCell($pdf, 140, 35, 60, 2, $Fdetails);
                }
            

            // Guilty Offense YES/NO coordinates
            $guiltyoffenseMap = [
                'yes' => [132.5, 43.5],
                'no'  => [153.5, 43.5]
            ];

            $guiltyoffense = strtolower(trim($otherInfo->found_guilty_admin_offense ?? 'no'));

            if (isset($guiltyoffenseMap[$guiltyoffense])) {
                $pdf->SetXY(...$guiltyoffenseMap[$guiltyoffense]);
                $pdf->Write(0, '/');

                if ($guiltyoffense === 'yes') {
                    $Gdetails = strtoupper($otherInfo->administrative_offense_details ?? '');
                    $writeAutoFitMultiCell($pdf, 140, 50, 60, 2, $Gdetails);
                }
            }

            // Criminally Case
            $criminalcaseMap = [
            'yes' => [132.5, 59],
            'no'  => [154, 59]

            ];

            $criminalcase = strtolower(trim($otherInfo->found_guilty_admin_offense ?? 'no'));

            if (isset($criminalcaseMap[$criminalcase])) {
                $pdf->SetXY(...$criminalcaseMap[$criminalcase]);
                $pdf->Write(0, '/');

                if ($criminalcase === 'yes') {
                    $CDatedetails = '';
                    if (!empty($otherInfo->criminal_date_filed)) {
                        $CDatedetails = \Carbon\Carbon::parse($otherInfo->criminal_date_filed)->format('Y-m-d'); // or 'm/d/Y'
                    }
                    $writeAutoFitMultiCell($pdf, 150, 65, 60, 2, $CDatedetails);

                    $Cdetails = strtoupper($otherInfo->criminal_status ?? '');
                    $writeAutoFitMultiCell($pdf, 160, 70, 30, 2, $Cdetails);
                }
            }

            // Criminally Charge
            $convictedMap = [
                'yes' => [132.5, 78],
                'no'  => [155, 78]
            ];

            $convicted = strtolower(trim($otherInfo->convicted_of_crime ?? 'no'));

            if (isset($convictedMap[$convicted])) {
                $pdf->SetXY(...$convictedMap[$convicted]);
                $pdf->Write(0, '/');

                if ($convicted === 'yes') {
                    $Condetails = strtoupper($otherInfo->crime_details ?? '');
                    $writeAutoFitMultiCell($pdf, 140, 84, 60, 2, $Condetails);
                }
            }

             // Separate
            $separatedMap = [
                'yes' => [132.5, 93],
                'no'  => [155, 93]
            ];

            $separated = strtolower(trim($otherInfo->separated_from_service ?? 'no'));

            if (isset($separatedMap[$separated])) {
                $pdf->SetXY(...$separatedMap[$separated]);
                $pdf->Write(0, '/');

                if ($separated === 'yes') {
                    $Sepdetails = strtoupper($otherInfo->service_separation_details ?? '');
                    $writeAutoFitMultiCell($pdf, 140, 97, 60, 2, $Sepdetails);
                }
            }

            // Candidate
            $candidateMap = [
                'yes' => [132.5, 105.5],
                'no'  => [157, 105.5]
            ];

            $candidate = strtolower(trim($otherInfo->candidate_in_election ?? 'no'));

            if (isset($candidateMap[$candidate])) {
                $pdf->SetXY(...$candidateMap[$candidate]);
                $pdf->Write(0, '/');

                if ($candidate === 'yes') {
                    $Candetails = strtoupper($otherInfo->candidate_in_election_details ?? '');
                    $writeAutoFitMultiCell($pdf, 160, 107.5, 30, 2, $Candetails);
                }
            }

            // Resigned
            $resignedMap = [
            'yes' => [132.5, 114.5],
            'no'  => [157, 114.5]
            ];

            $resigned = strtolower(trim($otherInfo->resigned_before_election ?? 'no'));

            if (isset($resignedMap[$resigned])) {
                $pdf->SetXY(...$resignedMap[$resigned]);
                $pdf->Write(0, '/');

                if ($resigned === 'yes') {
                    $Resdetails = strtoupper($otherInfo->resigned_before_election_details ?? '');
                    $writeAutoFitMultiCell($pdf, 160, 117, 30, 2, $Resdetails);
                }
            }

            // Immigrant
            $immigrantMap = [
                'yes' => [132.5, 125.5],
                'no'  => [155, 125.5]
            ];

            $immigrant = strtolower(trim($otherInfo->immigrant_status ?? 'no'));

            if (isset($immigrantMap[$immigrant])) {
                $pdf->SetXY(...$immigrantMap[$immigrant]);
                $pdf->Write(0, '/');

                if ($immigrant === 'yes') {
                    $Immdetails = strtoupper($otherInfo->immigrant_country ?? '');
                    $writeAutoFitMultiCell($pdf, 140, 131, 60, 2, $Immdetails);
                }
            }

            //Indigenous
            $indigenousMap = [
                'yes' => [132.5, 151],
                'no'  => [157, 151]
            ];

            $indigenous = strtolower(trim($otherInfo->member_of_indigenous_group ?? 'no'));

            if (isset($indigenousMap[$indigenous])) {
                $pdf->SetXY(...$indigenousMap[$indigenous]);
                $pdf->Write(0, '/');

                if ($indigenous === 'yes') {
                    $Inddetails = strtoupper($otherInfo->indigenous_group_details ?? '');
                    $writeAutoFitMultiCell($pdf, 172, 152, 20, 2, $Inddetails);
                }
            }

            // Disability
            $disabilityMap = [
            'yes' => [132.5, 159],
            'no'  => [157, 159]
            ];

            $disability = strtolower(trim($otherInfo->person_with_disability ?? 'no'));

            if (isset($disabilityMap[$disability])) {
                $pdf->SetXY(...$disabilityMap[$disability]);
                $pdf->Write(0, '/');

                if ($disability === 'yes') {
                    $Disdetails = strtoupper($otherInfo->disability_details ?? '');
                    $writeAutoFitMultiCell($pdf, 172, 161, 20, 2, $Disdetails);
                }
            }

            
            // Solo Parent
            $soloparentMap = [
            'yes' => [132.5, 167],
            'no'  => [157, 167]
            ];

            $soloparent = strtolower(trim($otherInfo->solo_parent ?? 'no'));

            if (isset($soloparentMap[$soloparent])) {
                $pdf->SetXY(...$soloparentMap[$soloparent]);
                $pdf->Write(0, '/');

                if ($soloparent === 'yes') {
                    $Soldetails = strtoupper($otherInfo->solo_parent_details ?? '');
                    $writeAutoFitMultiCell($pdf, 172, 168, 20, 2, $Soldetails);
                }
            }


        }


    }

}