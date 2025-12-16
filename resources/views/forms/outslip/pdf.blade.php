<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Outslip #{{ $slip->id }}</title>

  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      margin: 0;
      padding: 0;
    }

    @page {
      size: landscape;
      margin: 10mm;
    }

    .main-table {
      width: 100%;
      border-collapse: collapse;
    }

    .copy-box {
      border: 1px solid #000;
      padding: 5px;
      height: 100%;
      vertical-align: top;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    td {
      padding: 2px 3px;
      vertical-align: top;
    }

    .title {
      text-align: center;
      font-size: 15px;
      margin-top: -10px;
      margin-bottom: 4px;
      font-weight: bold;
    }

    .checkbox-label {
      font-weight: bold;
    }

    .signature-line {
      padding-top: 10px;
      text-align: center;
    }

    .small-text {
      font-size: 10px;
    }

    .logo {
      height: 40px;
    }

    hr {
      border: 0;
      border-top: 1px solid #000;
      margin: 5px 0;
    }
  </style>
</head>

<body>

  <table class="main-table">
    <tr>
      <!-- EMPLOYEE COPY -->
      <td class="copy-box" width="50%">

        <table>
          <tr>
            <td width="50%">
              <img src="{{ public_path('assets/img/logo-dswd-form.png') }}" class="logo">
            </td>
            <td width="50%" align="right">
              <b>EMPLOYEE'S COPY</b>
            </td>
          </tr>
        </table>

        <div class="title">DSWD FO XI EMPLOYEE OUTSLIP</div>

        <center>
          <p>
            <input type="checkbox" {{ $slip->type_of_slip == 'Official' ? 'checked' : '' }}>
            <span class="checkbox-label">OFFICIAL BUSINESS</span>

            &nbsp;&nbsp;&nbsp;

            <input type="checkbox" {{ $slip->type_of_slip == 'Personal' ? 'checked' : '' }}>
            <span class="checkbox-label">PERSONAL BUSINESS</span>
          </p>
        </center>

        <table>
          <tr>
            <td width="35%">DATE</td>
            <td>: {{ $slip->date }}</td>
          </tr>
          <tr>
            <td>NAME</td>
            <td>: {{ $slip->employee->full_name }}</td>
          </tr>
          <tr>
            <td>DIVISION/SECTION</td>
            <td>: {{ $slip->employee->division->abbreviation }} / {{ $slip->employee->section->abbreviation }}</td>
          </tr>
          <tr>
            <td>DESTINATION</td>
            <td>: {{ $slip->destination }}</td>
          </tr>
          <tr>
            <td>PURPOSE</td>
            <td>: {{ $slip->purpose }}</td>
          </tr>
        </table>

        <table>
          <tr>
            <td align="right" width="50%">
              <input type="checkbox" {{ in_array($slip->employee->employment_status_id, [1,2,4]) ? 'checked' : '' }}>
              <b>REG/CONT/CASUAL</b>
            </td>

            <td>
              <input type="checkbox" {{ in_array($slip->employee->employment_status_id, [3,5]) ? 'checked' : '' }}>
              <b>MOA</b>
            </td>
          </tr>
        </table>

        <div class="signature-line">_____________________</div>
        <div class="small-text" align="center">Signature of Division Chief or Authorized Signatory</div>

        <br>

        <table>
          <tr>
            <td>Time Out: __________________</td>
            <td>Time of Return: __________________</td>
          </tr>
          <tr>
            <td colspan="2">Guard on Duty: _______________________________________________</td>
          </tr>
          <tr>
            <td colspan="2">______________________________________________________________________________</td>
          </tr>
          <tr>
            <td colspan="2"><b>FOR OFFICIAL BUSINESS ONLY</b></td>
          </tr>
        </table>

        <p class="small-text">
          &emsp;I hereby certify that the above mentioned employee was in the office on the specified date and time.
        </p>

        <table class="small-text">
          <tr>
            <td>__________________</td>
            <td>_________________________</td>
            <td>__________________</td>
          </tr>
          <tr>
            <td>__________________</td>
            <td>_________________________</td>
            <td>__________________</td>
          </tr>
          <tr>
            <td>__________________</td>
            <td>_________________________</td>
            <td>__________________</td>
          </tr>
          <tr>
            <td>__________________</td>
            <td>_________________________</td>
            <td>__________________</td>
          </tr>
          <tr>
            <td>(Office/Establishment)</td>
            <td>(Signature over printed name)</td>
            <td>(Designation)</td>
          </tr>
        </table>

      </td>

      <!-- HR COPY -->
      <td class="copy-box" width="50%">

        <table>
          <tr>
            <td width="50%">
              <img src="{{ public_path('assets/img/logo-dswd-form.png') }}" class="logo">
            </td>
            <td width="50%" align="right">
              <b>HR COPY</b>
            </td>
          </tr>
        </table>

        <div class="title">DSWD FO XI EMPLOYEE OUTSLIP</div>

        <center>
          <p>
            <input type="checkbox" {{ $slip->type_of_slip == 'Official' ? 'checked' : '' }}>
            <span class="checkbox-label">OFFICIAL BUSINESS</span>

            &nbsp;&nbsp;&nbsp;

            <input type="checkbox" {{ $slip->type_of_slip == 'Personal' ? 'checked' : '' }}>
            <span class="checkbox-label">PERSONAL BUSINESS</span>
          </p>
        </center>

        <table>
          <tr>
            <td width="35%">DATE</td>
            <td>: {{ $slip->date }}</td>
          </tr>
          <tr>
            <td>NAME</td>
            <td>: {{ $slip->employee->full_name }}</td>
          </tr>
          <tr>
            <td>DIVISION/SECTION</td>
            <td>: {{ $slip->employee->division->abbreviation }} / {{ $slip->employee->section->abbreviation }}</td>
          </tr>
          <tr>
            <td>DESTINATION</td>
            <td>: {{ $slip->destination }}</td>
          </tr>
          <tr>
            <td>PURPOSE</td>
            <td>: {{ $slip->purpose }}</td>
          </tr>
        </table>

        <table>
          <tr>
            <td align="right" width="50%">
              <input type="checkbox" {{ in_array($slip->employee->employment_status_id, [1,2,4]) ? 'checked' : '' }}>
              <b>REG/CONT/CASUAL</b>
            </td>

            <td>
              <input type="checkbox" {{ in_array($slip->employee->employment_status_id, [3,5]) ? 'checked' : '' }}>
              <b>MOA</b>
            </td>
          </tr>
        </table>

        <div class="signature-line">_____________________</div>
        <div class="small-text" align="center">Signature of Division Chief or Authorized Signatory</div>

        <br>

        <table>
          <tr>
            <td>Time Out: __________________</td>
            <td>Time of Return: __________________</td>
          </tr>
          <tr>
            <td colspan="2">Guard on Duty: _______________________________________________</td>
          </tr>
          <tr>
            <td colspan="2">______________________________________________________________________________</td>
          </tr>
          <tr>
            <td colspan="2"><b>FOR OFFICIAL BUSINESS ONLY</b></td>
          </tr>
        </table>

        <p class="small-text">
          &emsp;I hereby certify that the above mentioned employee was in the office on the specified date and time.
        </p>

        <table class="small-text">
          <tr>
            <td>__________________</td>
            <td>_________________________</td>
            <td>__________________</td>
          </tr>
          <tr>
            <td>__________________</td>
            <td>_________________________</td>
            <td>__________________</td>
          </tr>
          <tr>
            <td>__________________</td>
            <td>_________________________</td>
            <td>__________________</td>
          </tr>
          <tr>
            <td>__________________</td>
            <td>_________________________</td>
            <td>__________________</td>
          </tr>
          <tr>
            <td>(Office/Establishment)</td>
            <td>(Signature over printed name)</td>
            <td>(Designation)</td>
          </tr>
        </table>

      </td>
    </tr>
  </table>

</body>

</html>