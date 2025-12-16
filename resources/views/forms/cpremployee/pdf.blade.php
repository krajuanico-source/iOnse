<!DOCTYPE html>
<html>

<head>
  <style>
    @font-face {
      font-family: 'Arial';
      src: url("/fonts/arial.ttf") format('truetype');
      font-weight: normal;
      font-style: normal;
    }

    body {
      font-family: 'Arial', sans-serif;
      font-size: 12px;
      margin: 50px;
    }

    .header-title {
      text-align: left;
      font-size: 14px;
      margin-top: 10px;
      margin-bottom: 30px;
    }

    .subtitle {
      text-align: center;
      font-weight: bold;
      margin-top: 20px;
      margin-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      font-size: 11px;
    }

    td,
    th {
      border: 1px solid #000;
      padding: 4px;
    }

    .center {
      text-align: center;
    }

    .footer {
      position: fixed;
      bottom: 20px;
      width: 100%;
      font-size: 10px;
      text-align: center;
    }

    .signature {
      margin-top: 10px;
      text-align: center;
    }

    .signature img {
      width: 180px;
      height: auto;
    }

    .signature-name {
      margin-top: 5px;
      font-weight: bold;
      font-size: 12px;
    }

    .signature-position {
      font-size: 11px;
    }
  </style>
</head>

<body>

  {{-- HEADER --}}
  @php
  $logoPath = public_path('assets/img/dswd_bagong.png');
  $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
  $logoData = file_get_contents($logoPath);
  $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
  @endphp

  <p>
    <img src="{{ $logoBase64 }}" alt="DSWD Logo" style="width:170px; height:50px;">
  </p>

  <p style="text-align: right;">Date: ____________________</p>

  <h2 class="header-title">
    <strong>Travel Order</strong><br>
    No. ________ &nbsp;&nbsp; <br>Series of 2025
  </h2>

  <p class="subtitle">SUBJECT: AUTHORITY TO TRAVEL</p>

  <p style="text-align: justify;">
    In the interest and exigency of service, the following staff is/are hereby authorized to
    <strong>{{ $travels[0]->travel_purpose ?? '____________________' }}</strong> (inclusive of travel time)
    to the place/s on the date/s as listed below:
  </p>

  {{-- TRAVEL TABLE --}}
  <table>
    <thead>
      <tr>
        <th class="center" style="width: 5%;">No.</th>
        <th style="width: 25%;">Name<br><span style="font-size:10px;">(Last Name, First Name, Middle Initial)</span></th>
        <th style="width: 15%;">Position</th>
        <th style="width: 15%;">Date</th>
        <th style="width: 20%;">Official Station</th>
        <th style="width: 20%;">Place(s)</th>
      </tr>
    </thead>

    <tbody>
      @foreach($travels as $index => $travel)
      <tr>
        <td class="center">{{ $index + 1 }}</td>
        <td>
          {{ $travel->employee->last_name ?? '' }},
          {{ $travel->employee->first_name ?? '' }}
          {{ $travel->employee->middle_name ? substr($travel->employee->middle_name,0,1).'.' : '' }}
        </td>
        <td>{{ $travel->employee->position->position_name ?? 'N/A' }}</td>
        <td>{{ $travel->travel_date }}</td>
        <td>{{ $travel->official_station ?? '' }}</td>
        <td>{{ $travel->travel_destination }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <p style="text-align: justify;">
    This travel order also authorizes the above-named staff to claim travel expenses subject to COA
    Accounting and Auditing Rules and Regulations. Further, it is understood that a report shall be
    submitted upon completion of this travel together with Certificate of Appearance from each place
    visited and other documentary evidence of travel.
  </p>

  {{-- SIGNATORIES --}}

  {{-- Recommending Approval --}}
  <div class="signature">
    @if(isset($recommendingSignature))
    <img src="{{ $recommendingSignature }}" alt="Recommending Signature">
    @else
    <br><br><br>
    @endif
    <div class="signature-name">NAME OF ARD CONCERNED</div>
    <div class="signature-position">Position</div>
  </div>

  <br><br>

  {{-- Approved By --}}
  <div class="signature">
    @if(isset($approvedSignature))
    <img src="{{ $approvedSignature }}" alt="Approved Signature">
    @else
    <br><br><br>
    @endif
    <div class="signature-name">DIR. RHUELO D. ARADANAS, PH.D.</div>
    <div class="signature-position">Regional Director</div>
  </div>

  {{-- FOOTER --}}
  <div class="footer">
    DSWD Field Office XI, Ramon Magsaysay Avenue Corner, Damaso Suazo Street, Davao City, Philippines 8000<br>
    Website: fo11.dswd.gov.ph &nbsp;&nbsp; Tel Nos.: (082) 227 - 1964
  </div>

</body>

</html>