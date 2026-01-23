@extends('layouts/contentNavbarLayout')

@section('title', 'Leave List')

@section('content')
<h3>Leave Application</h3>

<div class="card p-4" style="max-width: 700px; margin:auto;">
  <form action="{{ route('leaves.store') }}" method="POST">
    @csrf

    <div class="form-group">
      <label>Date of Filing</label>
      <input type="date" name="datefiled" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Employee ID</label>
      <input type="text" name="user_id" value="{{ auth()->user()->employee_id }}" class="form-control" readonly required>

    </div>
    <div class="form-group">
      <label>A. Type of Leave</label>
      <select name="leave_type" id="leave_type" class="form-control" onchange="handleLeaveTypeChange(this)">
        <option disabled selected>----------</option>
        <option value="Vacation">VACATION</option>
        <option value="Sick">SICK</option>
        <option value="Maternity">MATERNITY</option>
        <option value="Paternity">PATERNITY</option>
        <option value="Mandatory/Force">MANDATORY/FORCE</option>
        <option value="Special Privilege">SPECIAL PRIVILEGE</option>
        <option value="Study">STUDY</option>
        <option value="Solo Parent">SOLO PARENT</option>
        <option value="10-Day Vawc">10-DAY VAWC</option>
        <option value="Rehabilitation Privilege">REHABILITATION PRIVILEGE</option>
        <option value="Special Leave Benefits For Women">SPECIAL LEAVE BENEFITS FOR WOMEN</option>
        <option value="Special Emergency (Calamity)">SPECIAL EMERGENCY (CALAMITY)</option>
        <option value="Adoption">ADOPTION</option>
        <option value="Others">OTHERS</option>
      </select>
      <input type="text" name="leave_type_specify" id="other_specify" class="form-control mt-2" style="display:none" placeholder="Please Specify...">
    </div>

    <!-- Vacation Section -->
    <div id="vac_show2" style="display:none;" class="mt-3">
      <label>In case of Vacation/Special Privilege Leave:</label>
      <select class="form-control" name="leave_spent_vac_specify" id="leave_spent_vac_specify" onchange="handleVacationSelect(this)">
        <option disabled selected>--------</option>
        <option value="Within the Philippines">Within the Philippines</option>
        <option value="Abroad">Abroad (Specify)</option>
      </select>
      <input type="text" name="abroad_specify" id="abroad_specify" style="display:none; margin-top:5px;" class="form-control" placeholder="Specify Country">
    </div>

    <script>
      function handleVacationSelect(select) {
        const input = document.getElementById('abroad_specify');
        if (select.value === 'Abroad') {
          input.style.display = 'block';
          input.required = true;
        } else {
          input.style.display = 'none';
          input.required = false;
          input.value = '';
        }
      }

      // Prefill on edit (if user already selected Abroad)
      window.addEventListener('DOMContentLoaded', () => {
        const vacSelect = document.getElementById('leave_spent_vac_specify');
        const abroadInput = document.getElementById('abroad_specify');

        if (vacSelect.value === 'Abroad') {
          abroadInput.style.display = 'block';
          abroadInput.required = true;
        }
      });
    </script>



    <!-- Sick Section -->
    <div id="sick_show2" style="display:none;" class="mt-3">
      <label>In case of Sick Leave:</label>
      <select class="form-control" name="leave_spent_sick_specify" id="leave_spent_sick_specify" onchange="toggleSickInputs(this)">
        <option disabled selected>---------</option>
        <option value="In Hospital">In Hospital (Specify Illness)</option>
        <option value="Out Patient">Out Patient (Specify Illness)</option>
      </select>

      <!-- Input for In Hospital -->
      <input type="text" name="hospital_specify" id="hospital_specify" class="form-control mt-2" style="display:none"
        placeholder="Specify Illness for Hospital" />

      <!-- Input for At Home -->
      <input type="text" name="at_home_specify" id="at_home_specify" class="form-control mt-2" style="display:none"
        placeholder="Specify Illness for Out Patient / At Home" />
    </div>

    <script>
      function toggleSickInputs(select) {
        const hospitalInput = document.getElementById('hospital_specify');
        const atHomeInput = document.getElementById('at_home_specify');

        // Hide both initially
        hospitalInput.style.display = 'none';
        hospitalInput.required = false;
        hospitalInput.value = '';

        atHomeInput.style.display = 'none';
        atHomeInput.required = false;
        atHomeInput.value = '';

        // Show relevant input based on selection
        if (select.value === 'In Hospital') {
          hospitalInput.style.display = 'block';
          hospitalInput.required = true;
        } else if (select.value === 'Out Patient') {
          atHomeInput.style.display = 'block';
          atHomeInput.required = true;
        }
      }

      // Prefill when editing
      window.addEventListener('DOMContentLoaded', () => {
        const sickSelect = document.getElementById('leave_spent_sick_specify');
        if (sickSelect) toggleSickInputs(sickSelect);
      });
    </script>


    <!-- Study Section -->
    <div id="study_show2" style="display:none;" class="mt-3">
      <label>In case of Study Leave:</label>
      <select class="form-control" name="leave_study_specify" id="leave_study_specify">
        <option disabled selected>---------</option>
        <option value="Completion of Masters Degree">Completion of Masters Degree</option>
        <option value="BAR/Board Examination Review">BAR/Board Examination Review</option>
      </select>
    </div>

    <!-- Dates and Working Days -->
    <div class="form-group mt-3">
      <label>From Date</label>
      <input type="date" name="from_date" id="from_date" class="form-control" required onchange="calculateWorkingDays()">
    </div>

    <div class="form-group">
      <label>To Date</label>
      <input type="date" name="to_date" id="to_date" class="form-control" required onchange="calculateWorkingDays()">
    </div>

    <div class="form-group">
      <label>C. No. of Working Days applied for:</label>
      <input type="number" name="leave_no_wdays" id="leave_no_wdays" class="form-control" readonly required>
    </div>

    <script>
      // Show/hide sections based on Type of Leave
      function handleLeaveTypeChange(select) {
        const vac = document.getElementById('vac_show2');
        const sick = document.getElementById('sick_show2');
        const study = document.getElementById('study_show2');
        const otherInput = document.getElementById('other_specify');

        vac.style.display = 'none';
        sick.style.display = 'none';
        study.style.display = 'none';
        otherInput.style.display = 'none';
        otherInput.required = false;
        otherInput.value = '';

        if (select.value === 'Vacation') vac.style.display = 'block';
        if (select.value === 'Sick') sick.style.display = 'block';
        if (select.value === 'Study') study.style.display = 'block';
        if (select.value === 'Others') {
          otherInput.style.display = 'block';
          otherInput.required = true;
        }
      }

      // Show/hide sub-inputs like Abroad or Hospital
      function toggleSubInput(inputId, select) {
        const input = document.getElementById(inputId);
        if (select.value.includes('Specify')) {
          input.style.display = 'block';
          input.required = true;
        } else {
          input.style.display = 'none';
          input.required = false;
          input.value = '';
        }
      }

      // Calculate working days excluding weekends
      function calculateWorkingDays() {
        const fromDate = document.getElementById('from_date').value;
        const toDate = document.getElementById('to_date').value;
        const output = document.getElementById('leave_no_wdays');

        if (fromDate && toDate) {
          const start = new Date(fromDate);
          const end = new Date(toDate);

          if (end < start) {
            alert('To Date cannot be before From Date');
            output.value = '';
            return;
          }

          let count = 0;
          let current = new Date(start);

          while (current <= end) {
            const day = current.getDay();
            if (day !== 0 && day !== 6) count++; // exclude weekends
            current.setDate(current.getDate() + 1);
          }

          output.value = count;
        }
      }

      // Prefill sections on edit
      window.addEventListener('DOMContentLoaded', () => {
        const typeSelect = document.getElementById('leave_type');
        handleLeaveTypeChange(typeSelect);

        const vacSelect = document.getElementById('leave_spent_vac_specify');
        const sickSelect = document.getElementById('leave_spent_sick_specify');

        if (vacSelect) toggleSubInput('abroad_specify', vacSelect);
        if (sickSelect) toggleSubInput('hospital_specify', sickSelect);
        if (sickSelect) toggleSubInput('at_home_specify', sickSelect);
      });
    </script>


    <div class="form-group">
      <label>D.Commutation:</label>
      <select name="other_purpose" class="form-control">
        <option disabled selected>----------</option>
        <option value="">None</option>
        <option value="Monetization of Leave Credits">Monetization of Leave Credits</option>
        <option value="Terminal Leave">Terminal Leave</option>
      </select>
    </div>
    <br>
    <a href="{{ route('forms.leaves.index') }}" class="btn btn-secondary">Back</a>
    <button type="submit" class="btn btn-primary">Apply</button>
  </form>
</div>
@endsection