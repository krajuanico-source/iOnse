@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Employee')

@section('content')
<div class="container py-4">
  <div class="card p-4 shadow-sm">
    <h4 style="color: #1d4bb2;" class="mb-4">Edit Employee</h4>

    <form method="POST" action="{{ route('employee.update', $employee->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="d-flex justify-content-center mb-4">
          @if($employee->profile_image)
            <div style="width: 200px; height: 200px; border: 2px solid #1d4bb2; border-radius: 50%; overflow: hidden;">
              <img src="{{ asset($employee->profile_image) }}" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
          @else
            <div style="width: 200px; height: 200px; border: 4px solid #6c757d; border-radius: 50%; overflow: hidden;">
              <img src="{{ asset('default-user.png') }}" alt="No Photo" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
          @endif
        </div>

        <div class="col-md-12 mb-4 mx-auto">
          <label for="profile_image" class="form-label">Change Profile Image</label>
          <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*">
          <small class="text-muted">Accepted: jpeg, png, jpg (max: 2MB)</small>
        </div>

        <div class="col-md-6 mb-3">
          <label for="employee_id" class="form-label">ID No</label>
          <input type="text" name="employee_id" id="employee_id" class="form-control text-uppercase" value="{{ strtoupper($employee->employee_id) }}" readonly>
        </div>

        <div class="col-md-6 mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" id="username" class="form-control" value="{{ $employee->username }}" readonly>
        </div>

        <!-- Employment Status -->
        <div class="col-md-3 mb-3">
          <label for="employment_status_id" class="form-label">Employment Status</label>
          <select name="employment_status_id" id="employment_status_id" class="form-select text-uppercase">
            <option value="">-- Select Status --</option>
            @foreach(App\Models\EmploymentStatus::all() as $status)
              <option value="{{ $status->id }}" {{ $employee->employment_status_id == $status->id ? 'selected' : '' }}>
                {{ strtoupper($status->name) }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Division -->
        <div class="col-md-4 mb-3">
          <label for="division_id" class="form-label">Division</label>
          <select name="division_id" id="division_id" class="form-select text-uppercase">
            <option value="">-- Select Division --</option>
            @foreach(App\Models\Division::all() as $division)
              <option value="{{ $division->id }}" {{ $employee->division_id == $division->id ? 'selected' : '' }}>
                {{ strtoupper($division->name) }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Section -->
        <div class="col-md-4 mb-3">
          <label for="section_id" class="form-label">Section</label>
          <select name="section_id" id="section_id" class="form-select text-uppercase">
            <option value="">-- Select Section --</option>
            @foreach(App\Models\Section::where('division_id', $employee->division_id)->get() as $section)
              <option value="{{ $section->id }}" {{ $employee->section_id == $section->id ? 'selected' : '' }}>
                {{ strtoupper($section->name) }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Status -->
        <div class="col-md-4 mb-3">
          <label for="status" class="form-label">Status</label>
          <select name="status" id="status" class="form-select text-uppercase" required>
            <option value="">-- Select Status --</option>
            @foreach(['active', 'resigned', 'retired'] as $status)
              <option value="{{ $status }}" {{ $employee->status === $status ? 'selected' : '' }}>
                {{ strtoupper($status) }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('employee.view-blade') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-success">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('#division_id').on('change', function() {
    const divisionId = $(this).val();
    $('#section_id').html('<option value="">-- Loading --</option>');

    if (divisionId) {
      $.ajax({
        url: `/division/${divisionId}/sections`,
        type: 'GET',
        success: function(sections) {
          let options = '<option value="">-- Select Section --</option>';
          sections.forEach(section => {
            options += `<option value="${section.id}">${section.name.toUpperCase()}</option>`;
          });
          $('#section_id').html(options);
        },
        error: function() {
          $('#section_id').html('<option value="">-- Error Loading Section --</option>');
          alert('Error loading sections.');
        }
      });
    } else {
      $('#section_id').html('<option value="">-- Select Section --</option>');
    }
  });
</script>
@endsection
