<!-- HEADER LOGOS -->
<table class="logo-row" width="100%">
  <tr>
    <td>

      <img src="{{ asset('assets/img/logo-dswd-form.png') }}" alt="DSWD Logo" width="100" height="45">
    </td>
    </td>
    <td style="text-align:right;">
      <!-- Temporary regional logo -->
      <img src="" class="logo">
    </td>
  </tr>
</table>

<div class="header-code">
  DSWD-HRMDS-GF-015 | REV 01 / 07 NOV 2022
</div>

<div class="title">DOCUMENT REQUEST FORM</div>

<!-- DATE -->
<p style="text-align:right;">
  <strong>{{ $requestForm->req_date }}</strong><br>
  <span style="font-size:10px;">Date&emsp;&emsp;</span>
</p>

<!-- MESSAGE -->
<p>
  Laurencia P. Mondreza, SAO / OIC HRMDD Chief, Human Resource Management and Development<br>
  Division - DSWD Field Office No. XI
</p>

<p>May I request for issuance of:</p>

<!-- DOCUMENT TYPES -->
@php
$reqDoc = $requestForm->req_doc ?? '';
@endphp

<table class="options" width="100%">
  @php
  $docs = [
  'Service Record',
  'Certificate of Employment',
  'Certificate of Leave without Pay',
  'Certificate of Leave Credits',
  'Duly Accomplished Office Clearance Certificate Form'
  ];
  @endphp

  @foreach ($docs as $doc)
  <tr>
    <td>
      <input type="checkbox" {{ $reqDoc == $doc ? 'checked' : '' }}>
      {{ $doc }}
    </td>
  </tr>
  @endforeach

  <tr>
    <td>
      <input type="checkbox" {{ $reqDoc == 'Others (please specify)' ? 'checked' : '' }}>
      Others (please specify): <u>{{ $requestForm->req_specify_other }}</u>
    </td>
  </tr>
</table>


<!-- EMPLOYMENT STATUS -->
<p><strong>Employment Status:</strong></p>
@php
$empStatus = $requestForm->employee->employment_status ?? null;
@endphp

<table class="options" width="100%">
  <tr>
    <td>
      <input type="checkbox" {{ in_array($requestForm->employee->employment_status_id, [1]) || ($empStatus?->name == 'Current') ? 'checked' : '' }}>
      Current
    </td>
    <td>
      <input type="checkbox" {{ in_array($requestForm->employee->employment_status_id, [2,3]) || ($empStatus?->name == 'Permanent/Contractual/Casual') ? 'checked' : '' }}>
      Permanent/Contractual/Casual
    </td>
  </tr>
  <tr>
    <td>
      <input type="checkbox" {{ in_array($requestForm->employee->employment_status_id, [4]) || ($empStatus?->name == 'Separated') ? 'checked' : '' }}>
      Separated
    </td>
    <td>
      <input type="checkbox" {{ ($empStatus?->name == 'Contract of Service Worker') ? 'checked' : '' }}>
      Contract of Service Worker
    </td>
  </tr>
</table>




<!-- ADDITIONAL INFO -->
<p><strong>Additional Information (If requested):</strong></p>
@php
$reqSpecify = $requestForm->req_specify ?? '';
@endphp

<table class="options" width="100%">
  <tr>
    <td>
      <input type="checkbox" {{ $reqSpecify == 'Salary / Cost Service' ? 'checked' : '' }}>
      Salary/Cost of Service
    </td>
  </tr>
  <tr>
    <td>
      <input type="checkbox" {{ $reqSpecify == 'Service / Contract Gaps (if any)' ? 'checked' : '' }}>
      Service/Contract Gaps (if any)
    </td>
  </tr>
  <tr>
    <td>
      <input type="checkbox" {{ $reqSpecify == 'Others (please specify)' ? 'checked' : '' }}>
      Others (please specify): <u>{{ $requestForm->req_specify_other }}</u>
    </td>
  </tr>
</table>

<!-- PURPOSE & CONTACT -->
<p><strong>Purpose :</strong> <u>{{ strtoupper($requestForm->req_purpose ?? '') }}</u></p>

<p>
  <strong>Contact no. :</strong> <u>{{ strtoupper($requestForm->employee->mobile_no ?? '________________') }}</u>
  &nbsp;&nbsp;
  <strong>Email Add. :</strong> <u>{{ strtoupper($requestForm->employee->email ?? '________________') }}</u>
</p>

<p><strong>Mode of Receipt:</strong> <u>{{ strtoupper($requestForm->req_mode ?? '') }}</u></p>

<!-- SIGNATURE -->
<table class="signature-block" width="100%" style="margin-top: 30px;">
  <tr>
    <!-- Office / Division / Section -->
    <td style="vertical-align: top; width: 30%;">
      {{ $requestForm->employee->section->abbreviation ?? 'N/A' }}<br>
      <div class="signature-line" style="border-top:1px solid #000; width: 100px; margin:5px auto;"></div>
      <strong>Office</strong>
    </td>

    <!-- Signature -->
    <td style="text-align: center; vertical-align: bottom; width: 50%;">
      <div>{{ strtoupper($requestForm->employee->full_name ?? 'N/A') }}</div>
      <div class="signature-line" style="border-top:1px solid #000; width: 240px; margin:5px auto;"></div>
      <div style="font-size:10px;">Signature over printed name</div>
    </td>

  </tr>
</table>


<!-- FOOTER -->
<div class="footer">
  DSWD Field Office XI, D. Suazo St., cor. R. Magsaysay Avenue, Davao City, Philippines 8000<br>
  Website: http://www.fo11.dswd.gov.ph &nbsp;&nbsp; Tel Nos.: (082)226-2857; (082)227-1964 &nbsp;&nbsp; Telefax (082)226-2857
</div>
