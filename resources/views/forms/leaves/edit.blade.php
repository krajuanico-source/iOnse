@extends('layouts/contentNavbarLayout')

@section('title', 'Leave List')

@section('content')
<div class="container">
  <h2>{{ isset($leave) ? 'Edit Leave' : 'Apply Leave' }}</h2>

  <div class="card p-4" style="max-width:700px; margin:auto;">
    <form action="{{ isset($leave) ? route('leaves.update', $leave->leave_no) : route('leaves.store') }}" method="POST">
      @csrf
      @if(isset($leave)) @method('PUT') @endif

      <!-- Employee ID -->
      <div class="mb-3">
        <label>Employee ID</label>
        <input type="text" name="empid" class="form-control" readonly required
          value="{{ old('empid', $leave->empid ?? auth()->user()->employee_id) }}">
      </div>

      <!-- Type of Leave -->
      <div class="mb-3">
        <label>A. Type of Leave</label>
        <select name="leave_type" id="leave_type" class="form-control" onchange="handleLeaveTypeChange(this)">
          <option disabled {{ isset($leave) ? '' : 'selected' }}>----------</option>
          @php
          $leave_types = [
          'Vacation', 'Sick', 'Maternity', 'Paternity', 'Mandatory/Force',
          'Special Privilege', 'Study', 'Solo Parent', '10-Day Vawc',
          'Rehabilitation Privilege', 'Special Leave Benefits For Women',
          'Special Emergency (Calamity)', 'Adoption', 'Others'
          ];
          @endphp
          @foreach($leave_types as $type)
          <option value="{{ $type }}" {{ (isset($leave) && $leave->leave_type == $type) ? 'selected' : '' }}>
            {{ strtoupper($type) }}
          </option>
          @endforeach
        </select>
      </div>

      <!-- Specify Others -->
      <div class="mb-3" id="other_leave_div" style="{{ (isset($leave) && $leave->leave_type == 'Others') ? 'display:block;' : 'display:none;' }}">
        <label>Specify if Others:</label>
        <input type="text" name="leave_type_specify" class="form-control" placeholder="Specify Other Leave"
          value="{{ old('leave_type_specify', $leave->leave_type_specify ?? '') }}">
      </div>

      <!-- Vacation / Special Privilege -->
      <div id="vac_show2" class="mb-3" style="{{ (isset($leave) && in_array($leave->leave_type, ['Vacation','Special Privilege'])) ? 'display:block;' : 'display:none;' }}">
        <label>In case of Vacation/Special Privilege Leave:</label>
        <select name="leave_spent_vac_specify" id="leave_spent_vac_specify" class="form-control" onchange="toggleVacationSubInput(this)">
          <option disabled selected>--------</option>
          <option value="Within the Philippines" {{ (isset($leave) && $leave->leave_spent == 'Within the Philippines') ? 'selected' : '' }}>Within the Philippines</option>
          <option value="Abroad" {{ (isset($leave) && $leave->leave_spent == 'Abroad') ? 'selected' : '' }}>Abroad (Specify)</option>
        </select>
        <input type="text" name="abroad_specify" id="abroad_specify" class="form-control mt-2" placeholder="Specify Country"
          style="{{ (isset($leave) && $leave->leave_spent == 'Abroad') ? 'display:block;' : 'display:none;' }}"
          value="{{ old('abroad_specify', $leave->leave_specify ?? '') }}">
      </div>

      <!-- Sick -->
      <div id="sick_show2" class="mb-3" style="{{ (isset($leave) && $leave->leave_type == 'Sick') ? 'display:block;' : 'display:none;' }}">
        <label>In case of Sick Leave:</label>
        <select name="leave_spent_sick_specify" id="leave_spent_sick_specify" class="form-control" onchange="toggleSickInput(this)">
          <option disabled selected>---------</option>
          <option value="In Hospital" {{ (isset($leave) && $leave->leave_spent == 'In Hospital') ? 'selected' : '' }}>In Hospital (Specify Illness)</option>
          <option value="Out Patient" {{ (isset($leave) && $leave->leave_spent == 'Out Patient') ? 'selected' : '' }}>Out Patient (Specify Illness)</option>
        </select>
        <input type="text" name="hospital_specify" id="hospital_specify" class="form-control mt-2" placeholder="Specify Illness"
          style="{{ (isset($leave) && in_array($leave->leave_spent, ['In Hospital','Out Patient'])) ? 'display:block;' : 'display:none;' }}"
          value="{{ old('hospital_specify', $leave->leave_specify ?? '') }}">
      </div>

      <!-- Study Leave -->
      <div id="study_show" class="mb-3" style="{{ (isset($leave) && $leave->leave_type == 'Study') ? 'display:block;' : 'display:none;' }}">
        <label>Specify Study Leave:</label>
        <input type="text" name="leave_study_specify" class="form-control"
          value="{{ old('leave_study_specify', $leave->leave_spent ?? '') }}" placeholder="Specify Study Leave">
      </div>

      <!-- Dates & Working Days -->
      <div class="mb-3">
        <label>From Date</label>
        <input type="date" name="from_date" id="from_date" class="form-control"
          value="{{ old('from_date', $leave->from_date ?? '') }}" required onchange="calculateWorkingDays()">
      </div>

      <div class="mb-3">
        <label>To Date</label>
        <input type="date" name="to_date" id="to_date" class="form-control"
          value="{{ old('to_date', $leave->to_date ?? '') }}" required onchange="calculateWorkingDays()">
      </div>

      <div class="mb-3">
        <label>C. No. of Working Days applied for:</label>
        <input type="number" name="leave_no_wdays" id="leave_no_wdays" class="form-control"
          value="{{ old('leave_no_wdays', $leave->leave_no_wdays ?? '') }}" readonly required>
      </div>

      <!-- Other Purpose -->
      <div class="mb-3">
        <label>D.Commutation:</label>
        <select name="other_purpose" class="form-control">
          <option disabled {{ isset($leave) ? '' : 'selected' }}>----------</option>
          <option value="" {{ (isset($leave) && $leave->other_purpose == '') ? 'selected' : '' }}>None</option>
          <option value="Monetization of Leave Credits" {{ (isset($leave) && $leave->other_purpose == 'Monetization of Leave Credits') ? 'selected' : '' }}>Monetization of Leave Credits</option>
          <option value="Terminal Leave" {{ (isset($leave) && $leave->other_purpose == 'Terminal Leave') ? 'selected' : '' }}>Terminal Leave</option>
        </select>
      </div>

      <a href="{{ route('forms.leaves.index') }}" class="btn btn-secondary">Back</a>
      <button type="submit" class="btn btn-primary">{{ isset($leave) ? 'Update' : 'Apply' }}</button>
    </form>

    <script>
      function handleLeaveTypeChange(select) {
        document.getElementById('other_leave_div').style.display = (select.value === 'Others') ? 'block' : 'none';
        document.getElementById('vac_show2').style.display = (select.value === 'Vacation' || select.value === 'Special Privilege') ? 'block' : 'none';
        document.getElementById('sick_show2').style.display = (select.value === 'Sick') ? 'block' : 'none';
        document.getElementById('study_show').style.display = (select.value === 'Study') ? 'block' : 'none';
      }

      function toggleVacationSubInput(select) {
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

      function toggleSickInput(select) {
        const input = document.getElementById('hospital_specify');
        if (select.value === 'In Hospital' || select.value === 'Out Patient') {
          input.style.display = 'block';
          input.required = true;
        } else {
          input.style.display = 'none';
          input.required = false;
          input.value = '';
        }
      }

      function calculateWorkingDays() {
        const from = document.getElementById('from_date').value;
        const to = document.getElementById('to_date').value;
        const output = document.getElementById('leave_no_wdays');
        if (!from || !to) return;

        const start = new Date(from);
        const end = new Date(to);
        if (end < start) {
          output.value = '';
          return;
        }

        let count = 0;
        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
          if (d.getDay() !== 0 && d.getDay() !== 6) count++;
        }
        output.value = count;
      }

      // Prefill values on page load
      window.addEventListener('DOMContentLoaded', () => {
        handleLeaveTypeChange(document.getElementById('leave_type'));
        toggleVacationSubInput(document.getElementById('leave_spent_vac_specify'));
        toggleSickInput(document.getElementById('leave_spent_sick_specify'));
        calculateWorkingDays();
      });
    </script>

  </div>

</div>
@endsection
