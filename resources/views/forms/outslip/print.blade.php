<!DOCTYPE html>
<html>

<head>
  <title>Outslip #{{ $slip->id }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      margin: 20px;
    }

    .container {
      display: flex;
      justify-content: space-between;
    }

    .copy {
      width: 48%;
      border: 1px solid #000;
      padding: 10px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .header img {
      height: 40px;
    }

    h2 {
      text-align: center;
      font-size: 14px;
      margin-bottom: 5px;
    }

    .checkbox {
      display: inline-block;
      width: 15px;
      height: 15px;
      border: 1px solid #000;
      text-align: center;
      margin-right: 5px;
    }

    table {
      width: 100%;
      margin-top: 5px;
      border-collapse: collapse;
    }

    td {
      padding: 2px 5px;
      vertical-align: top;
    }

    .signature {
      margin-top: 20px;
      text-align: center;
    }

    .footer {
      margin-top: 15px;
      font-size: 11px;
    }

    hr {
      border: 0;
      border-top: 1px solid #000;
      margin: 5px 0;
    }

    button.print-btn {
      margin-top: 10px;
      padding: 5px 15px;
      font-size: 12px;
    }
  </style>
</head>

<body>
  <div class="container">
    <!-- Employee Copy -->
    <div class="copy">

      <div class="header">
        <table width='100%'>
          <tr>
            <td colspan='2' align='left'>&emsp;<font size='1'>
                <img src="{{ asset('assets/img/logo-dswd-form.png') }}" alt="DSWD Logo"></td>
            <td colspan='2' align='right'>
              <font size='1'><b>EMPLOYEE'S COPY</b>
            </td>

          </tr>
          <tr>
            <td colspan='4'></td>
          </tr>
        </table>
      </div>

      <h2>DSWD FO XI EMPLOYEE OUTSLIP</h2><br>
      <center>
        <p>
          <span style="margin-right:10px;">
            <input type="checkbox" {{ $slip->type_of_slip == 'Official' ? 'checked' : '' }}>
            <b>OFFICIAL BUSINESS</b>
          </span>

          <span>
            <input type="checkbox" {{ $slip->type_of_slip == 'Personal' ? 'checked' : '' }}>
            <b>PERSONAL BUSINESS</b>
          </span>
        </p>
      </center>
      <br>
      <table>
        <tr>
          <td width="30%">DATE</td>
          <td>: {{ $slip->date }}</td>
        </tr>
        <tr>
          <td>NAME</td>
          <td>: {{ $slip->employee->full_name }}</td>
        </tr>
        <tr>
          <td>DIVISION/SECTION</td>
          <td>: {{ $slip->employee->division->abbreviation }} / {{ $slip->employee->section->abbreviation }}</td>
          </td>
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

      <div class="footer">


        <table width='100%'>
          <tr>
            <td width="10%"></td>

            <td width="40%" align="right">
              <b>
                <input type="checkbox" name="emp_stat"
                  {{ in_array($slip->employee->employment_status_id, [1, 2, 4]) || $slip->employee->employment_status->name == 'CASUAL' ? 'checked' : '' }}>
                &nbsp;&nbsp;REG/CONT/CASUAL
              </b>
            </td>

            <td width="30%" align="left">
              <b>
                <input type="checkbox" name="emp_stat"
                  {{ in_array($slip->employee->employment_status_id, [3, 5]) ? 'checked' : '' }}>
                &nbsp;&nbsp;MOA
              </b>
            </td>

            <td width="20%"></td>
          </tr>

          <tr>
            <td colspan='4' align='center'><br>_____________________</td>

          </tr>
          <tr>
            <td colspan='4' align='center'>Signature of Division Chief or Authorized Signatory</td>

          </tr>

          <tr>
            <td colspan='4'></td>
          </tr>
        </table><br>
        <table width='100%'>
          <tr>
            <td align='left' colspan='2'>Time Out:_________________</td>
            <td align='left' colspan='2'>Time of Return:_________________</td>
          </tr>
          <tr>
            <td colspan='4' align='left'>Guard on Duty:_______________________________________________</td>
          </tr>
          <tr>
            <td colspan='4' align='left'>____________________________________________________________________________</td>
          </tr>
          <tr>
            <td colspan='4' align='left'><b>FOR OFFICIAL BUSINESS ONLY</b></td>
          </tr>
          <tr>
            <td colspan='4'></td>
          </tr>

          <tr>
            <td colspan='4' align='left'><br>
              &emsp;I hereby certify that the above mentioned employee was in the office on the specified date and ime.</td>
          </tr>
        </table>
        <table width='100%'>
          <tbody>
            <tr>
              <td width='30%'>&emsp;__________________</td>
              <td width='40%'>_________________________ </td>
              <td width='30%'>__________________ </td>
            </tr>
            <tr>
              <td width='30%'>&emsp;__________________ </td>
              <td width='40%'>_________________________ </td>
              <td width='30%'>__________________ </td>
            </tr>
            <tr>
              <td width='30%'>&emsp;__________________ </td>
              <td width='40%'>_________________________ </td>
              <td width='30%'>__________________ </td>
            </tr>
            <tr>
              <td width='30%'>&emsp;__________________ </td>
              <td width='40%'>_________________________ </td>
              <td width='30%'>__________________ </td>
            </tr>
            <tr>
              <td width='30%' padding='1'>&emsp;(Office/Establishment) </td>
              <td width='40%'>(Signature over printed name) </td>
              <td width='30%' colspan='3'>(Designation) </td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>&emsp;</td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>

    <!-- HR Copy -->
    <div class="copy">

      <div class="header">
        <table width='100%'>
          <tr>
            <td colspan='2' align='left'>&emsp;<font size='1'>
                <img src="{{ asset('assets/img/logo-dswd-form.png') }}" alt="DSWD Logo"></td>
            <td colspan='2' align='right'>
              <font size='1'><b>HR COPY</b>
            </td>

          </tr>
          <tr>
            <td colspan='4'></td>
          </tr>
        </table>
      </div>

      <h2>DSWD FO XI EMPLOYEE OUTSLIP</h2><br>
      <center>
        <p>
          <span style="margin-right:10px;">
            <input type="checkbox" {{ $slip->type_of_slip == 'Official' ? 'checked' : '' }}>
            <b>OFFICIAL BUSINESS</b>
          </span>

          <span>
            <input type="checkbox" {{ $slip->type_of_slip == 'Personal' ? 'checked' : '' }}>
            <b>PERSONAL BUSINESS</b>
          </span>
        </p>
      </center>
      <br>
      <table>
        <tr>
          <td width="30%">DATE</td>
          <td>: {{ $slip->date }}</td>
        </tr>
        <tr>
          <td>NAME</td>
          <td>: {{ $slip->employee->full_name }}</td>
        </tr>
        <tr>
          <td>DIVISION/SECTION</td>
          <td>: {{ $slip->employee->division->abbreviation }} / {{ $slip->employee->section->abbreviation }}</td>
          </td>
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

      <div class="footer">


        <table width='100%'>
          <tr>
            <td width="10%"></td>

            <td width="40%" align="right">
              <b>
                <input type="checkbox" name="emp_stat"
                  {{ in_array($slip->employee->employment_status_id, [1, 2, 4]) || $slip->employee->employment_status->name == 'CASUAL' ? 'checked' : '' }}>
                &nbsp;&nbsp;REG/CONT/CASUAL
              </b>
            </td>

            <td width="30%" align="left">
              <b>
                <input type="checkbox" name="emp_stat"
                  {{ in_array($slip->employee->employment_status_id, [3, 5]) ? 'checked' : '' }}>
                &nbsp;&nbsp;MOA
              </b>
            </td>

            <td width="20%"></td>
          </tr>

          <tr>
            <td colspan='4' align='center'><br>_____________________</td>

          </tr>
          <tr>
            <td colspan='4' align='center'>Signature of Division Chief or Authorized Signatory</td>

          </tr>

          <tr>
            <td colspan='4'></td>
          </tr>
        </table><br>
        <table width='100%'>
          <tr>
            <td align='left' colspan='2'>Time Out:_________________</td>
            <td align='left' colspan='2'>Time of Return:_________________</td>
          </tr>
          <tr>
            <td colspan='4' align='left'>Guard on Duty:_______________________________________________</td>
          </tr>
          <tr>
            <td colspan='4' align='left'>____________________________________________________________________________</td>
          </tr>
          <tr>
            <td colspan='4' align='left'><b>FOR OFFICIAL BUSINESS ONLY</b></td>
          </tr>
          <tr>
            <td colspan='4'></td>
          </tr>

          <tr>
            <td colspan='4' align='left'><br>
              &emsp;I hereby certify that the above mentioned employee was in the office on the specified date and ime.</td>
          </tr>
        </table>
        <table width='100%'>
          <tbody>
            <tr>
              <td width='30%'>&emsp;__________________</td>
              <td width='40%'>_________________________ </td>
              <td width='30%'>__________________ </td>
            </tr>
            <tr>
              <td width='30%'>&emsp;__________________ </td>
              <td width='40%'>_________________________ </td>
              <td width='30%'>__________________ </td>
            </tr>
            <tr>
              <td width='30%'>&emsp;__________________ </td>
              <td width='40%'>_________________________ </td>
              <td width='30%'>__________________ </td>
            </tr>
            <tr>
              <td width='30%'>&emsp;__________________ </td>
              <td width='40%'>_________________________ </td>
              <td width='30%'>__________________ </td>
            </tr>
            <tr>
              <td width='30%' padding='1'>&emsp;(Office/Establishment) </td>
              <td width='40%'>(Signature over printed name) </td>
              <td width='30%' colspan='3'>(Designation) </td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>&emsp;</td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>
  </div>
</body>

</html>
