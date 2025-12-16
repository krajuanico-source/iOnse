<!DOCTYPE html>
<html>

<head>
  <title>Leave Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 7px;
    }

    .container {
      display: flex;
      justify-content: space-between;
    }


    h2 {
      text-align: center;
      font-size: 14px;
      margin-bottom: 5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 2px;
    }

    td {
      vertical-align: top;
    }

    .copy {
      width: 100%;
      padding: 10px;
    }

    .signature {
      margin-top: 10px;
      text-align: center;
    }

    .checkbox {
      display: inline-block;
      width: 15px;
      height: 15px;
      border: 1px solid #000;
      text-align: center;
      margin-right: 5px;
    }

    hr {
      border: 0;
      border-top: 1px solid #000;
      margin: 5px 0;
    }
  </style>
</head>

<body onload="window.print()">

  <div class="container">
    <div class="copy">
      <table style="width: 100%; text-align: center; border-collapse: collapse;">
        <tr>
          <td colspan="2" style="font-size:8px; text-align: left;">CSC Form 6<br>Revised 1998</td>
          <td colspan="2"></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: right;">
            <img src="{{ asset('assets/img/dswd_icon.jpg') }}" width="70" height="70">
          </td>
          <td colspan="2" style="font-size:10px; text-align: center;">
            <b>Republic of the Philippines<br>
              <i>DEPARTMENT OF SOCIAL WELFARE AND DEVELOPMENT<br>
                FIELD OFFICE XI, DAVAO CITY</i></b>
          </td>
          <td colspan="1" style="text-align: left;">
            <img src="{{ asset('assets/img/stamp.png') }}" width="110" height="30">
          </td>
        </tr>
        <tr>
          <td colspan="5" style="font-size:7px; text-align:center; padding-top:10px;">
            <b>APPLICATION FOR LEAVE</b>
          </td>
        </tr>
      </table>
      <label style='font-size:14px'>
        <table width='100%'>

          <tr style='border-top: thin solid;border-left: thin solid;border-right: thin solid'>
            <td colspan='5' align='center'><label style='font-size:10px'>1. Office/Department</label></td>
            <td colspan='5' align='center'><label style='font-size:10px'>2. Name (Last)</td>
            <td colspan='5' align='center'><label style='font-size:10px'>(First)</td>
            <td colspan='5' align='center'><label style='font-size:10px'>(Middle)</td>
          </tr>
          <tr style='border-top: thin solid;border-left: thin solid;border-right: thin solid'>
            <td colspan='5' align='center'><label style='font-size:10px'><b>DSWD FO XI</b></td>
            <td colspan='5' align='center'><b> {{ $leave->employee->last_name }}</b></td>
            <td colspan='5' align='center'><b>{{ $leave->employee->first_name }}</b></td>
            <td colspan='5' align='center'><b>{{ $leave->employee->middle_name }}</b></td>
          </tr>
          <tr style='border-top: thin solid;border-left: thin solid;border-right: thin solid'>
            <td colspan='5' align='center'><label style='font-size:10px'>3. Date of Filing</td>
            <td colspan='5' align='center'><label style='font-size:10px'>4. Position</td>
            <td colspan='5' align='left'></td>
            <td colspan='5' align='center'><label style='font-size:10px'>5. Salary</td>
          </tr>
          <tr style='border-top: thin solid;border-left: thin solid;border-right: thin solid'>
            <td colspan='5' align='center'><b>{{ $leave->datefiled }}</b></td>
            <td colspan='5' align='left'>{{ $leave->employee->position->position_name ?? 'N/A' }}</td>
            <td colspan='5' align='left'></td>
            <td colspan='5' align='left'></td>
          </tr>

          <tr style='border-top: 1px solid;border-left: thin solid;border-right: thin solid'>
            <td style='border-top: 2px solid;border-left: thin solid;border-bottom: 2px solid;border-right: thin solid;border-left: thin solid;' colspan='20' align='center'><b><label style='font-size:8px'>6. DETAILS OF APPLICATION</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-top: 2px solid;border-left: thin solid;' colspan='11' align='left'><label style='font-size:8px'>6.A TYPE OF LEAVE TO BE AVAILED OF</td>
            <td style='border-right: thin solid;' colspan='9' align='left'><label style='font-size:8px'>6.B DETAILS OF LEAVE</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $vac_leave><label style='font-size:8px'>Vacation Leave <label style='font-size:8px'>(Sec. 51, RUle XVI, Omnibus Rules Implementing E.O. No. 292)</label></input></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<label style='font-size:7px  '>1. In case of Vacation Leave/Special Privilege Leave:</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $forced_leave><label style='font-size:8px'>Mandatory/Forced Leave <label style='font-size:8px'>(Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</label></input></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<input onclick='return false;' type='checkbox' name='wp' $phil_spent><label style='font-size:8px'>Within the Philippines</label></input> ______________________</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $sick_leave><label style='font-size:8px'>Sick Leave </input><label style='font-size:8px'> (Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</label></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<input onclick='return false;' type='checkbox' $spent_abroad><label style='font-size:8px'>Abroad (Specify)</label></input> _________<u><label style='font-size:8px'>{{ $leave->leave_specify }}</u>_________</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $mat_leave><label style='font-size:8px'>Maternity </input><label style='font-size:8px'> (R.A. No. 11210 / IRR issued by CSC, DOLE and SSS)</label></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<label style='font-size:8px'>In case of Sick Leave:</td>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $pat_leave><label style='font-size:8px'>Paternity </input><label style='font-size:8px'> (R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended)</label></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<input onclick='return false;' type='checkbox' $spent_host><label style='font-size:8px'>In Hospital (Specify Illness)</label></input> ___<u><label style='font-size:8px'>{{ $leave->leave_specify }}</u>___</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $priv_leave><label style='font-size:8px'>Special Privilege Leave </input><label style='font-size:8px'> (Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</label></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<input onclick='return false;' type='checkbox' $out_patient><label style='font-size:8px'>Out Patient (Specify Illness)</label></input> ___<u><label style='font-size:8px'>$specify_out</u>___</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $solo_leave><label style='font-size:8px'>Solo Parent Leave </input><label style='font-size:8px'> (RA No. 8972 / CSC MC No. 8, s. 2004)</label></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;_________________________________</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $study_leave><label style='font-size:8px'>Study Leave </input><label style='font-size:8px'> (Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</label></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<label style='font-size:8px'>In case of Special Leave Benefits for Woman:</label></td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $vawc_leave><label style='font-size:8px'>10-Day VAWC Leave </input><label style='font-size:8px'> (RA No. 9262 / CSC MC No. 15, s. 2005)</label></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp; <label style='font-size:8px'>(Special Illness) _________________________________</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $rehab_leave><label style='font-size:8px'>Rehabilitation Privilege </input><label style='font-size:8px'> (Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</label></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;__________<u><label style='font-size:8px'>$specify_women</u>__________</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $special_leave><label style='font-size:8px'>Special Leave Benefits for Women
              </label></input><label style='font-size:8px'> (RA No. 9710 / CSC MC No. 25, s. 2010)</label></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<label style='font-size:8px'>In case of Study Leave:</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $emer_leave><label style='font-size:8px'>Special Emergency(Calamity) Leave </input><label style='font-size:8px'>(CSC MC No. 2, s. 2012, as amended)</label></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<input onclick='return false;' type='checkbox' $master_leave><label style='font-size:8px'>Completion of Mater's Degree</label></td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<input onclick='return false;' type='checkbox' $adopt_leave><label style='font-size:8px'>Adoption Leave </input><label style='font-size:8px'> (R.A. No. 8552)</label></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<input onclick='return false;' type='checkbox' $bar_leave><label style='font-size:8px'>BAR/Board Examination Review</label></td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'></td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<label style='font-size:8px'>Other Purpose:</td>
          </tr>

          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<label style='font-size:8px'>Others:</td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<input onclick='return false;' type='checkbox' $mon_leave><label style='font-size:8px'>Monetization of Leave Credits</label></input></td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;_______________<u><label style='font-size:8px'>$other_spec</u>__________________</td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<input onclick='return false;' type='checkbox' $term_leave><label style='font-size:8px'>Terminal Leave</label></input></td>
          </tr>

          <tr>
            <td style='border-right: thin solid;border-left: thin solid;border-top: thin solid;' colspan='11' align='left'><label style='font-size:8px'>6.C NUMBER OF WORKING DAYS APPLIED FOR</td>
            <td style='border-right: thin solid;border-top: thin solid;' colspan='9' align='left'><label style='font-size:8px'>6. D) COMMUTATION</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11'>&emsp;_______________<u><label style='font-size:8px'>{{ $leave->leave_no_wdays }}</u>_______________</td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<input onclick='return false;' type='checkbox' $com_not>&emsp;<label style='font-size:8px'> Not Requested</label></input></td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;<label style='font-size:8px'>INCLUSIVE DATES</label> </td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<input onclick='return false;' type='checkbox' $com_req>&emsp;<label style='font-size:8px'>Requested</label></input></td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;_____<u><label style='font-size:8px'>{{ $leave->from_date }} TO {{ $leave->to_date }}</u>____</td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;&emsp;<label style='font-size:8px'> (Signature of Applicant)</label>&emsp;__________________________</td>
          </tr>

          <tr>
            <td style='border-top: 2px solid;border-left: thin solid;border-bottom: 2px solid;border-right: thin solid;border-left: thin solid;' colspan='20' align='center'><b><label style='font-size:8px'>DETAILS OF ACTION ON APPLICATION</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'><label style='font-size:8px'>7.A CERTIFICATION OF LEAVE CREDITS</td>
            <td style='border-right: thin solid;' colspan='9' align='left'><label style='font-size:8px'>7.B RECOMMENDATION</td>
          </tr>

          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; <label style='font-size:8px'>As of_________________________ </td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<input onclick='return false;' type='checkbox' $for_app>&emsp;<label style='font-size:8px'>For approval</input></td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'></td>
            <td style='border-right: thin solid;' colspan='9' align='left'></td>
          </tr>
          <tr>
            <td style='border-left: thin solid;' colspan='1' align='left'>&emsp;&emsp;</td>
            <td style='border: thin solid;' colspan='3'></td>
            <td style='border: thin solid;' colspan='3' align='center'><label style='font-size:10px'>Vacation</td>
            <td style='border: thin solid;' colspan='3' align='center'><label style='font-size:10px'>Sick&emsp;</td>
            <td style='border-right: thin solid;' colspan='1' align='left'>&emsp;&emsp;</td>
            <td style='border-right: thin solid;' colspan='9' align='left'>&emsp;<input onclick='return false;' type='checkbox'>&emsp;<label style='font-size:8px'>For disapproval due to</input> _____________________</td>
          </tr>
          <tr>
            <td style='border-left: thin solid;' colspan='1' align='left'>&emsp;&emsp;</td>
            <td style='border: thin solid;' colspan='3'><label style='font-size:10px'>Total Earned</label></td>
            <td style='border: thin solid;' colspan='3' align='center'><label style='font-size:10px'>$sick</td>
            <td style='border: thin solid;' colspan='3' align='center'><label style='font-size:10px'>$sick&emsp;</td>
            <td style='border-right: thin solid;' colspan='1' align='left'>&emsp;&emsp;</td>
            <td style='border-right: thin solid;' colspan='9' align='center'>______________________________________________</td>
          </tr>
          <tr>
            <td style='border-left: thin solid;' colspan='1' align='left'>&emsp;&emsp;</td>
            <td style='border: thin solid;' colspan='3'><label style='font-size:10px'>Less this application</label></td>
            <td style='border: thin solid;' colspan='3' align='center'><label style='font-size:10px'>$lessvac</td>
            <td style='border: thin solid;' colspan='3' align='center'><label style='font-size:10px'>$lesssick</td>
            <td style='border-right: thin solid;' colspan='1' align='left'>&emsp;&emsp;</td>
            <td style='border-right: thin solid;' colspan='9' align='center'>______________________________________________</td>
          </tr>
          <tr>
            <td style='border-left: thin solid;' colspan='1' align='left'>&emsp;&emsp;</td>
            <td style='border: thin solid;' colspan='3'><label style='font-size:10px'>Balance</label></td>
            <td style='border: thin solid;' colspan='3' align='center'><label style='font-size:10px'>$bal_sick</td>
            <td style='border: thin solid;' colspan='3' align='center'><label style='font-size:10px'>$bal_sick</td>
            <td style='border-right: thin solid;' colspan='1' align='left'>&emsp;&emsp;</td>
            <td style='border-right: thin solid;' colspan='9' align='center'>______________________________________________</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='left'></td>
            <td style='border-right: thin solid;' colspan='9' align='center'></td>
          </tr>
          <!--push-->
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='center'><br><br><br>_____________<b><u><label style='font-size:8px'>JANELLE G. MATUGAS</u></b>_______________</td>
            <td style='border-right: thin solid;' colspan='9' align='center'><br><br><br>_________<b><u><label style='font-size:8px'>$chief</u></b>___________</td>
          </tr>
          <tr>
            <td style='border-right: thin solid;border-left: thin solid;' colspan='11' align='center'><label style='font-size:8px'>(Authorized Officer)</td>
            <td style='border-right: thin solid;' colspan='9' align='center'><label style='font-size:8px'>(Authorized Officer)</td>
          </tr>
          <tr>
            <td style='border-top: 2px solid;border-left: thin solid;border-bottom: 2px solid;border-right: thin solid;border-left: thin solid;' colspan='20' align='center'></td>
          </tr>
          <tr>
            <td style='border-left: thin solid;' colspan='11' align='left'><label style='font-size:8px'>7.C APPROVED FOR:</td>
            <td style='border-right: thin solid;' colspan='9' align='left'><label style='font-size:8px'>7.D DISAPPROVED DUE TO:</td>
          </tr>
          <tr>
            <td style='border-left: thin solid;' colspan='11' align='left'><label style='font-size:8px'>&emsp;________days with pay</td>
            <td style='border-right: thin solid;' colspan='9' align='center'>______________________________________________</td>

          </tr>
          <tr>
            <td style='border-left: thin solid;' colspan='11' align='left'><label style='font-size:8px'>&emsp;________days without pay</td>
            <td style='border-right: thin solid;' colspan='9' align='center'>______________________________________________</td>
          </tr>
          <tr>
            <td style='border-left: thin solid;' colspan='11' align='left'><label style='font-size:8px'>&emsp;________other (Specify)</td>
            <td style='border-right: thin solid;' colspan='9' align='center'>______________________________________________</td>
          </tr>
          <tr style='border-left: thin solid;border-right: thin solid;'>
            <td style='border-right: thin solid;border-bottom: thin solid;' colspan='20' align='center'><label style='font-size:8px'><br><br>_________<b><u>$approval</u></b>___________<br><b>(Authorized Official)</b></td>
          </tr>

        </table>

    </div>
  </div>

</body>

</html>