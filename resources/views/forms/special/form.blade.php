@extends('layouts/contentNavbarLayout')

@section('title', 'Special Request Form')

@section('content')

<div class="card p-4" style="max-width: 1200px; margin:auto;">
  <div class="container py-4">
    <h3>Special Request Form</h3>
    <hr>

    <form method="POST" action="{{ route('forms.special.store') }}">
      @csrf

      {{-- SECTION --}}
      <div class="mb-3">
        <label class="form-label">Section</label>
        <select name="sp_section" class="form-select" required>
          <option value="" disabled selected>--- Select Section ---</option>
          @foreach($sections as $section)
          <option value="{{ $section->id }}">{{ $section->name }}</option>
          @endforeach
        </select>
      </div>


      {{-- SUBJECT --}}
      <div class="mb-3">
        <label class="form-label">Subject</label>
        <select name="special_subject" id="subjectSelect" class="form-select" required>
          <option value="" disabled selected>--- Select Subject ---</option>
          <option value="seminar">Seminar</option>
          <option value="training">Training</option>
          <option value="travel">Travel</option>
          <option value="workshop">Workshop</option>
          <option value="deployment">Deployment</option>
        </select>
      </div>

      {{-- TYPE OF TRAINING (visible only if subject=training) --}}
      <div class="mb-3" id="trainingTypeDiv" style="display:none;">
        <label class="form-label">Type of Training</label>
        <select name="training_type" class="form-select">
          <option value="" disabled selected>--- Select Training Type ---</option>
          <option value="technical">Technical Training</option>
          <option value="orientation">Orientation</option>
          <option value="capacity_building">Capacity Building</option>
        </select>
      </div>

      {{-- ACTIVITY TITLE --}}
      <div class="mb-3">
        <label class="form-label">Activity Title</label>
        <textarea name="activity_title" class="form-control" rows="2" required></textarea>
      </div>

      {{-- EMPLOYEE ROWS --}}
      <h5>List of Staff</h5>
      <div id="employeeRows">

        <div class="employee-row row mb-3">
          <div class="col-md-5">
            <label class="form-label">Employee</label>
            <select name="user_id[]" class="form-select" required>
              <option value="" disabled selected>Select Employee</option>
              @foreach($employees as $employee)
              <option value="{{ $employee->employee_id }}">
                {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_id }})
              </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-5">
            <label class="form-label">Purpose</label>
            <input type="text" name="special_purpose[]" class="form-control" required>
          </div>

          <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger removeRow">-</button>
          </div>
        </div>

      </div>

      {{-- Add Employee Button --}}
      <button type="button" class="btn btn-secondary mb-3" id="addEmployeeRow">+ Add Employee</button>

      {{-- SUBMIT --}}
      <div class="text-center">
        <button type="submit" class="btn btn-primary">Save Special Request</button>
      </div>

    </form>
  </div>
</div>

<script>
  // SHOW TRAINING TYPE WHEN SUBJECT = TRAINING
  document.getElementById('subjectSelect').addEventListener('change', function() {
    const trainingDiv = document.getElementById('trainingTypeDiv');
    trainingDiv.style.display = this.value === 'training' ? 'block' : 'none';
  });

  // ADD EMPLOYEE ROW
  document.getElementById('addEmployeeRow').addEventListener('click', function() {
    const rows = document.getElementById('employeeRows');
    const newRow = rows.children[0].cloneNode(true);

    newRow.querySelectorAll("select, input").forEach(el => el.value = "");

    rows.appendChild(newRow);
  });

  // REMOVE EMPLOYEE ROW
  document.getElementById('employeeRows').addEventListener('click', function(e) {
    if (e.target.classList.contains('removeRow')) {
      if (document.querySelectorAll('.employee-row').length > 1) {
        e.target.closest('.employee-row').remove();
      }
    }
  });
</script>

@endsection