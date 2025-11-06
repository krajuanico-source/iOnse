<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Personal Data Sheet (CSC Form 212)</title>
  <style>
    @page { margin: 12mm 10mm; size: legal portrait; }
    body {font-family: 'Arial Black', Arial, sans-serif; font-size: 10px; color: #000; margin: 0; }
    .container { width: 100%; padding: 4px; box-sizing: border-box; }
    .header { width: 100%; }
    .title { font-size: 20px; font-weight: 800; text-align: center; margin: 2px 0 6px; }
    .subtitle { text-align: center; font-size: 9px; margin-bottom: 6px; }
    table { width: 100%; border-collapse: collapse; }
    .tbl, .tbl th, .tbl td { border: 1px solid #000; }
    .tbl th, .tbl td { padding: 4px; vertical-align: top; }
    .section { background: #ddd; font-weight: 700; padding-left: 6px; }
    .small { font-size: 9px; }
    .checkbox { display:inline-block; width:12px; height:12px; border:1px solid #000; margin-right:6px; vertical-align:middle; }
    .checked { background:#000; }
    .no-border { border: none !important; }
    .right { text-align: right; }
    .center { text-align: center; }
    .muted { color: #000000ff; font-size:9px; }
    .page-break { page-break-after: always; }

    /* narrow columns for form-like layout */
    .col-1 { width: 12%; }
    .col-2 { width: 18%; }
    .col-3 { width: 25%; }
    .col-4 { width: 45%; }

    /* small inputs styling (visually) */
    .input-inline { padding-left:6px; }

    /* compact rows */
    .compact td { padding: 3px 4px; font-size:10px; }
    .no-wrap { white-space: nowrap; }
  </style>
</head>
<body>
<div class="container">

  {{-- PAGE 1 --}}
  <div class="header">
    <div style="display:flex; justify-content:space-between; align-items:center;">
    <div style="font-size:11px; font-weight:700; font-style:italic;">CS Form No. 212</div>
    <div style="font-size:9px; font-style:italic; font-weight:bold;">Revised 2017</div>
    <div style="font-size: 22px; font-weight:700;text-align: center;">
    PERSONAL DATA SHEET
    </div>
    </div>

    <p class="subtitle muted" style="font-style: italic; font-size: 10px; text-align: left; font-weight: bold; margin: 0; line-height: 1.2;">
    WARNING: Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet shall cause the filing of administrative/criminal case/s against the person concerned.
    </p>

    <p style="font-style: italic; font-size: 10px; text-align: left; font-weight: bold; margin: 0; line-height: 1.2;">
    READ THE ATTACHED GUIDE TO FILLING OUT THE PERSONAL DATA SHEET (PDS) BEFORE ACCOMPLISHING THE PDS FORM.
    </p>

    <div style="display: flex; justify-content: space-between; align-items: center; margin: 0; line-height: 1.2;">
    <p style="font-size: 9px; text-align: left; margin: 0;">
        Print legibly. Tick appropriate boxes 
        (<span style="display:inline-block; width:10px; height:10px; border:1px solid #000; vertical-align: middle;"></span>) 
        and use separate sheet if necessary. Indicate N/A if not applicable. 
        <span style="font-weight: bold;">DO NOT ABBREVIATE.</span>
    </p>

    <table class="float-end" style="width: 30%; font-size: 10px; border-collapse: collapse; margin: 0; ">
        <tr>
        <td style="width: 30%; background-color: #d9d9d9; border: 1px solid #000; padding: 2px; font-weight: bold;">
            1. CS ID No.
        </td>
        <td style="border: 1px solid #000; padding: 2px; font-style: italic;">
            (Do not fill up. For CSC use only)
        </td>
        </tr>
    </table>
    </div>

  <table class="tbl compact" style="margin-bottom:6px;">
    <tr>
      <td colspan="2" class="section" style="width: 30%;">I. PERSONAL INFORMATION</td>
    </tr>

    <tr>
      <td class="col-1">1. SURNAME</td>
      <td class="col-3">{{ strtoupper($basicInfo->last_name ?? '') }}</td>
      <td class="col-1">NAME EXTENSION (JR., SR.)</td>
      <td class="col-3">{{ strtoupper($basicInfo->name_extension ?? '') }}</td>
    </tr>

    <tr>
      <td>2. FIRST NAME</td>
      <td>{{ strtoupper($basicInfo->first_name ?? '') }}</td>
      <td>3. MIDDLE NAME</td>
      <td>{{ strtoupper($basicInfo->middle_name ?? '') }}</td>
    </tr>

    <tr>
      <td>4. DATE OF BIRTH</td>
      <td>{{ $basicInfo->birth_date ?? '' }}</td>
      <td>5. PLACE OF BIRTH</td>
      <td>{{ $basicInfo->birth_place ?? '' }}</td>
    </tr>

    <tr>
      <td>6. SEX</td>
      <td colspan="3">
        <span class="checkbox @if(isset($basicInfo->sex) && strtolower($basicInfo->sex)=='male') checked @endif"></span> Male
        &nbsp;&nbsp;&nbsp;
        <span class="checkbox @if(isset($basicInfo->sex) && strtolower($basicInfo->sex)=='female') checked @endif"></span> Female
      </td>
    </tr>

    <tr>
      <td>7. CIVIL STATUS</td>
      <td colspan="3">
        @php $cs = strtolower($basicInfo->civil_status ?? '') @endphp
        <span class="checkbox @if($cs=='single') checked @endif"></span> Single
        <span class="checkbox @if($cs=='married') checked @endif" style="margin-left:8px;"></span> Married
        <span class="checkbox @if($cs=='widowed') checked @endif" style="margin-left:8px;"></span> Widowed
        <span class="checkbox @if($cs=='separated') checked @endif" style="margin-left:8px;"></span> Separated
        <span style="margin-left:8px;">Other/s: <span class="input-inline">{{ $basicInfo->civil_status_other ?? '' }}</span></span>
      </td>
    </tr>

    <tr>
      <td>8. HEIGHT (m)</td>
      <td>{{ $basicInfo->height ?? '' }}</td>
      <td>9. WEIGHT (kg)</td>
      <td>{{ $basicInfo->weight ?? '' }}</td>
    </tr>

    <tr>
      <td>10. BLOOD TYPE</td>
      <td>{{ $basicInfo->blood_type ?? '' }}</td>
      <td>11. GSIS ID NO.</td>
      <td>{{ $governmentIds->gsis ?? '' }}</td>
    </tr>

    <tr>
      <td>12. PAG-IBIG ID NO.</td>
      <td>{{ $governmentIds->pagibig ?? '' }}</td>
      <td>13. PHILHEALTH NO.</td>
      <td>{{ $governmentIds->philhealth ?? '' }}</td>
    </tr>

    <tr>
      <td>14. SSS NO.</td>
      <td>{{ $governmentIds->sss ?? '' }}</td>
      <td>15. TIN</td>
      <td>{{ $governmentIds->tin ?? '' }}</td>
    </tr>

    <tr>
      <td colspan="1">16. CITIZENSHIP</td>
      <td colspan="3">
        <span class="checkbox @if(isset($basicInfo->citizenship) && strtolower($basicInfo->citizenship)=='filipino') checked @endif"></span> Filipino
        &nbsp;&nbsp;
        <span class="checkbox @if(isset($basicInfo->citizenship) && strtolower($basicInfo->citizenship)=='dual') checked @endif"></span> Dual Citizenship
        &nbsp;&nbsp; @if(isset($basicInfo->dual_citizenship_by))
          (by {{ $basicInfo->dual_citizenship_by }}) Country: {{ $basicInfo->dual_citizenship_country ?? '' }}
        @endif
      </td>
    </tr>

    <tr>
      <td>17. RESIDENTIAL ADDRESS</td>
      <td colspan="3">
        {{ $basicInfo->res_house_block_lot ?? '' }} {{ $basicInfo->res_street ?? '' }} <br>
        {{ $basicInfo->res_subdivision_village ?? '' }} {{ $basicInfo->res_barangay ?? '' }} <br>
        {{ $basicInfo->res_city_municipality ?? '' }}, {{ $basicInfo->res_province ?? '' }} {{ $basicInfo->res_zip_code ?? '' }}
      </td>
    </tr>

    <tr>
      <td>18. PERMANENT ADDRESS</td>
      <td colspan="3">
        {{ $basicInfo->perm_house_block_lot ?? '' }} {{ $basicInfo->perm_street ?? '' }} <br>
        {{ $basicInfo->perm_subdivision_village ?? '' }} {{ $basicInfo->perm_barangay ?? '' }} <br>
        {{ $basicInfo->perm_city_municipality ?? '' }}, {{ $basicInfo->perm_province ?? '' }} {{ $basicInfo->perm_zip_code ?? '' }}
      </td>
    </tr>

    <tr>
      <td>19. TELEPHONE NO.</td>
      <td>{{ $basicInfo->telephone ?? '' }}</td>
      <td>20. MOBILE NO.</td>
      <td>{{ $basicInfo->mobile ?? '' }}</td>
    </tr>

    <tr>
      <td>21. EMAIL ADDRESS</td>
      <td colspan="3">{{ $basicInfo->email ?? '' }}</td>
    </tr>

    <tr>
      <td>22. RELIGION</td>
      <td colspan="3">{{ $basicInfo->religion ?? '' }}</td>
    </tr>
  </table>

  {{-- FAMILY BACKGROUND (page 1 cont) --}}
  <table class="tbl compact" style="margin-top:6px;">
    <tr><td class="section">II. FAMILY BACKGROUND</td></tr>
    <tr>
      <td>
        <table style="width:100%; border-collapse: collapse;">
          <tr>
            <td style="width:33%; border:1px solid #000; padding:4px">Spouse's Surname</td>
            <td style="width:33%; border:1px solid #000; padding:4px">Spouse's First Name</td>
            <td style="width:34%; border:1px solid #000; padding:4px">Spouse's Occupation/Employer/Business</td>
          </tr>
          <tr>
            <td style="padding:6px;">{{ $family->spouse_last_name ?? '' }}</td>
            <td style="padding:6px;">{{ $family->spouse_first_name ?? '' }}</td>
            <td style="padding:6px;">{{ $family->spouse_occupation ?? '' }}</td>
          </tr>
        </table>

        <p style="margin:6px 0 3px; font-weight:700;">Children (if none, indicate N/A):</p>
        <table style="width:100%; border-collapse: collapse;">
          <tr>
            <th style="border:1px solid #000; padding:4px;">Name</th>
            <th style="border:1px solid #000; padding:4px;">Date of Birth</th>
            <th style="border:1px solid #000; padding:4px;">Age</th>
          </tr>
          @if(isset($family->children) && is_array($family->children))
            @foreach($family->children as $child)
            <tr>
              <td style="padding:4px;">{{ $child['name'] ?? '' }}</td>
              <td style="padding:4px;">{{ $child['birth_date'] ?? '' }}</td>
              <td style="padding:4px;">{{ $child['age'] ?? '' }}</td>
            </tr>
            @endforeach
          @else
            <tr><td style="padding:6px;" colspan="3">N/A</td></tr>
          @endif
        </table>
      </td>
    </tr>
  </table>

  {{-- PAGE BREAK --}}
  <div class="page-break"></div>

  {{-- PAGE 2 --}}
  <div style="margin-top:4px;">
    <table class="tbl compact">
      <tr><td class="section">III. EDUCATIONAL BACKGROUND</td></tr>
      <tr>
        <td style="padding:6px;">
          <table style="width:100%; border-collapse: collapse;">
            <tr>
              <th style="border:1px solid #000; padding:4px; width:12%;">Level</th>
              <th style="border:1px solid #000; padding:4px; width:38%;">Name of School</th>
              <th style="border:1px solid #000; padding:4px; width:25%;">Basic/Academic Degree/Course</th>
              <th style="border:1px solid #000; padding:4px; width:12%;">Period of Attendance</th>
              <th style="border:1px solid #000; padding:4px; width:13%;">Highest Level/Units Earned</th>
              <th style="border:1px solid #000; padding:4px; width:10%;">Year Graduated</th>
              <th style="border:1px solid #000; padding:4px; width:12%;">Scholarship/Academic Honors Received</th>
            </tr>

            @if(count($education ?? []) > 0)
              @foreach($education as $ed)
                <tr>
                  <td style="padding:4px;">{{ $ed->level ?? '' }}</td>
                  <td style="padding:4px;">{{ $ed->school_name ?? '' }}</td>
                  <td style="padding:4px;">{{ $ed->course ?? '' }}</td>
                  <td style="padding:4px;">{{ $ed->period_from ?? '' }} - {{ $ed->period_to ?? '' }}</td>
                  <td style="padding:4px;">{{ $ed->highest_level_units ?? '' }}</td>
                  <td style="padding:4px;">{{ $ed->year_graduated ?? '' }}</td>
                  <td style="padding:4px;">{{ $ed->honors_received ?? '' }}</td>
                </tr>
              @endforeach
            @else
              @for($i=0;$i<6;$i++)
                <tr>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                </tr>
              @endfor
            @endif
          </table>
        </td>
      </tr>
    </table>

    <table class="tbl compact" style="margin-top:6px;">
      <tr><td class="section">IV. CIVIL SERVICE ELIGIBILITY</td></tr>
      <tr>
        <td style="padding:6px;">
          <table style="width:100%; border-collapse: collapse;">
            <tr>
              <th style="border:1px solid #000; padding:4px; width:30%;">Eligibility</th>
              <th style="border:1px solid #000; padding:4px; width:15%;">Rating</th>
              <th style="border:1px solid #000; padding:4px; width:20%;">Date of Exam/Release</th>
              <th style="border:1px solid #000; padding:4px; width:35%;">License No. (if applicable)</th>
            </tr>
            @if(count($eligibility ?? []) > 0)
              @foreach($eligibility as $el)
                <tr>
                  <td style="padding:4px;">{{ $el->eligibility ?? '' }}</td>
                  <td style="padding:4px;">{{ $el->rating ?? '' }}</td>
                  <td style="padding:4px;">{{ $el->exam_date ?? '' }}</td>
                  <td style="padding:4px;">{{ $el->license_number ?? '' }}</td>
                </tr>
              @endforeach
            @else
              @for($i=0;$i<4;$i++)
                <tr>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                </tr>
              @endfor
            @endif
          </table>
        </td>
      </tr>
    </table>

    <table class="tbl compact" style="margin-top:6px;">
      <tr><td class="section">V. WORK EXPERIENCE (Start with your present employer)</td></tr>
      <tr>
        <td style="padding:6px;">
          <table style="width:100%; border-collapse: collapse;">
            <tr>
              <th style="border:1px solid #000; padding:4px; width:20%;">From</th>
              <th style="border:1px solid #000; padding:4px; width:20%;">To</th>
              <th style="border:1px solid #000; padding:4px; width:25%;">Position Title</th>
              <th style="border:1px solid #000; padding:4px; width:25%;">Company/Organization</th>
              <th style="border:1px solid #000; padding:4px; width:10%;">Monthly Salary</th>
            </tr>

            @if(count($work ?? []) > 0)
              @foreach($work as $w)
                <tr>
                  <td style="padding:4px;">{{ $w->from_date ?? '' }}</td>
                  <td style="padding:4px;">{{ $w->to_date ?? '' }}</td>
                  <td style="padding:4px;">{{ $w->position_title ?? '' }}</td>
                  <td style="padding:4px;">{{ $w->company_name ?? '' }} <br> {{ $w->address ?? '' }}</td>
                  <td style="padding:4px;">{{ $w->monthly_salary ?? '' }}</td>
                </tr>
              @endforeach
            @else
              @for($i=0;$i<6;$i++)
                <tr>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                </tr>
              @endfor
            @endif
          </table>
        </td>
      </tr>
    </table>
  </div>

  {{-- PAGE BREAK --}}
  <div class="page-break"></div>

  {{-- PAGE 3 --}}
  <div>
    <table class="tbl compact">
      <tr><td class="section">VI. VOLUNTARY WORK/INVOLVEMENT</td></tr>
      <tr>
        <td style="padding:6px;">
          <table style="width:100%; border-collapse: collapse;">
            <tr>
              <th style="border:1px solid #000; padding:4px; width:40%;">Name & Address of Organization</th>
              <th style="border:1px solid #000; padding:4px; width:20%;">From</th>
              <th style="border:1px solid #000; padding:4px; width:20%;">To</th>
              <th style="border:1px solid #000; padding:4px; width:20%;">Number of Hours</th>
            </tr>
            @if(count($voluntary ?? []) > 0)
              @foreach($voluntary as $v)
                <tr>
                  <td style="padding:4px;">{{ $v->organization_name ?? '' }} <br> {{ $v->address ?? '' }}</td>
                  <td style="padding:4px;">{{ $v->from_date ?? '' }}</td>
                  <td style="padding:4px;">{{ $v->to_date ?? '' }}</td>
                  <td style="padding:4px;">{{ $v->hours ?? '' }}</td>
                </tr>
              @endforeach
            @else
              @for($i=0;$i<6;$i++)
                <tr>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                </tr>
              @endfor
            @endif
          </table>
        </td>
      </tr>
    </table>

    <table class="tbl compact" style="margin-top:6px;">
      <tr><td class="section">VII. LEARNING AND DEVELOPMENT (L&D)</td></tr>
      <tr>
        <td style="padding:6px;">
          <table style="width:100%; border-collapse: collapse;">
            <tr>
              <th style="border:1px solid #000; padding:4px; width:40%;">Title of Learning Event</th>
              <th style="border:1px solid #000; padding:4px; width:20%;">From</th>
              <th style="border:1px solid #000; padding:4px; width:20%;">To</th>
              <th style="border:1px solid #000; padding:4px; width:20%;">No. of Hours</th>
            </tr>
            @if(count($learning ?? []) > 0)
              @foreach($learning as $l)
                <tr>
                  <td style="padding:4px;">{{ $l->title ?? '' }} <br> {{ $l->conducted_by ?? '' }}</td>
                  <td style="padding:4px;">{{ $l->from_date ?? '' }}</td>
                  <td style="padding:4px;">{{ $l->to_date ?? '' }}</td>
                  <td style="padding:4px;">{{ $l->hours ?? '' }}</td>
                </tr>
              @endforeach
            @else
              @for($i=0;$i<6;$i++)
                <tr>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                </tr>
              @endfor
            @endif
          </table>
        </td>
      </tr>
    </table>

    <table class="tbl compact" style="margin-top:6px;">
      <tr><td class="section">VIII. OTHER INFORMATION</td></tr>
      <tr>
        <td style="padding:6px;">
          <table style="width:100%; border-collapse: collapse;">
            <tr>
              <td style="width:33%; border:1px solid #000; padding:4px;">Special Skills / Hobbies</td>
              <td style="width:67%; border:1px solid #000; padding:4px;">{{ implode(', ', $skills->pluck('name')->toArray() ?? []) }}</td>
            </tr>
            <tr>
              <td style="border:1px solid #000; padding:4px;">Non-Academic Distinctions / Recognitions</td>
              <td style="border:1px solid #000; padding:4px;">
                @if(count($nonAcademic ?? []) > 0)
                  <ul style="margin:6px 0 0 16px; padding:0;">
                    @foreach($nonAcademic as $n)
                      <li>{{ $n->title ?? '' }} ({{ $n->year ?? '' }})</li>
                    @endforeach
                  </ul>
                @else
                  N/A
                @endif
              </td>
            </tr>
            <tr>
              <td style="border:1px solid #000; padding:4px;">Membership in Associations/Organizations</td>
              <td style="border:1px solid #000; padding:4px;">
                @if(count($organization ?? []) > 0)
                  <ul style="margin:6px 0 0 16px; padding:0;">
                    @foreach($organization as $o)
                      <li>{{ $o->name ?? '' }} — {{ $o->position ?? '' }}</li>
                    @endforeach
                  </ul>
                @else
                  N/A
                @endif
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

  </div>

  {{-- PAGE BREAK --}}
  <div class="page-break"></div>

  {{-- PAGE 4 --}}
  <div>
    <table class="tbl compact">
      <tr><td class="section">IX. REFERENCES</td></tr>
      <tr>
        <td style="padding:6px;">
          <table style="width:100%; border-collapse: collapse;">
            <tr>
              <th style="border:1px solid #000; padding:4px; width:35%;">Name</th>
              <th style="border:1px solid #000; padding:4px; width:45%;">Address</th>
              <th style="border:1px solid #000; padding:4px; width:20%;">Contact No.</th>
            </tr>
            @if(count($references ?? []) > 0)
              @foreach($references as $r)
                <tr>
                  <td style="padding:4px;">{{ $r->name ?? '' }}</td>
                  <td style="padding:4px;">{{ $r->address ?? '' }}</td>
                  <td style="padding:4px;">{{ $r->contact ?? '' }}</td>
                </tr>
              @endforeach
            @else
              @for($i=0;$i<5;$i++)
                <tr>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                  <td style="padding:6px;">&nbsp;</td>
                </tr>
              @endfor
            @endif
          </table>
        </td>
      </tr>
    </table>

    <table class="tbl compact" style="margin-top:6px;">
      <tr><td class="section">X. CERTIFICATION</td></tr>
      <tr>
        <td style="padding:8px;">
          I CERTIFY that all the entries in this Personal Data Sheet are true and correct to the best of my knowledge and belief.
          <br><br>
          <table style="width:100%; border-collapse: collapse;">
            <tr>
              <td style="width:60%; border:1px solid #000; padding:6px; height:60px;">
                <div style="font-weight:700;">Signature Over Printed Name</div>
                <div class="muted">Date: {{ now()->format('F d, Y') }}</div>
              </td>
              <td style="width:40%; border:1px solid #000; padding:6px;">
                <div style="font-weight:700;">Right Thumbmark</div>
                <div style="height:40px;">&nbsp;</div>
              </td>
            </tr>
          </table>

          <p style="margin-top:8px; font-size:10px;">(Continue on next page if necessary)</p>
        </td>
      </tr>
    </table>

  </div>
</div>
</body>
</html>
