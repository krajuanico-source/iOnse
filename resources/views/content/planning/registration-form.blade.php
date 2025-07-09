@extends('layouts/contentNavbarLayout')

@section('title', 'Employee Registration')

@section('content')
<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Employee Details</h4>
    </div>
    <div class="card-body">

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">

        @csrf

        <div class="row mb-4">
          <div class="col-md-4">
            <label>Employee ID</label>
            <input type="text" class="form-control" name="employee_id"
                   value="{{ old('employee_id', $generatedEmployeeId ?? '') }}"
                   readonly required
                   style="text-transform: uppercase; background-color: #e9ecef; cursor: not-allowed;">
          </div>

          <div class="col-md-4">
            <label>First Name</label>
            <input type="text" class="form-control" name="first_name"
                   value="{{ old('first_name') }}" required style="text-transform: uppercase;">
          </div>

          <div class="col-md-4">
            <label>Middle Name</label>
            <input type="text" class="form-control" name="middle_name"
                   value="{{ old('middle_name') }}" style="text-transform: uppercase;">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label>Last Name</label>
            <input type="text" class="form-control" name="last_name"
                   value="{{ old('last_name') }}" required style="text-transform: uppercase;">
          </div>

          <div class="col-md-4">
            <label>Extension Name</label>
            <select class="form-select" name="extension_name">
              <option value="">Choose...</option>
              @foreach(['JR','SR','II','III','IV'] as $ext)
                <option value="{{ $ext }}" {{ old('extension_name') == $ext ? 'selected' : '' }}>{{ $ext }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label>Gender</label>
            <select class="form-select" name="gender" required>
              <option value="" disabled selected>Choose...</option>
              <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
              <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label>Employment Status</label>
            <select class="form-select" name="employment_status" required>
              <option value="" disabled selected>Choose...</option>
              @foreach($employmentStatuses as $status)
                <option value="{{ $status->id }}" {{ old('employment_status') == $status->id ? 'selected' : '' }}>
                  {{ $status->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label>Division</label>
            <select class="form-select" name="division" id="divisionSelect" required>
              <option value="" disabled selected>Choose...</option>
              @foreach($divisions as $division)
                <option value="{{ $division->id }}" {{ old('division') == $division->id ? 'selected' : '' }}>
                  {{ $division->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label>Section</label>
            <select class="form-select" name="section" id="sectionSelect" required>
              <option value="">Choose division first...</option>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label>Email Address</label>
            <input type="email" class="form-control" name="email"
                   value="{{ old('email') }}" required autocomplete="off">
          </div>

          <div class="col-md-4">
            <label>Password</label>
            <input type="password" class="form-control" name="password" required autocomplete="new-password">
            <small class="text-muted">Min: 6 characters</small>
          </div>

          <div class="col-md-4">
            <label>Confirm Password</label>
            <input type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-12">
            <label for="profile_image">Profile Image</label>
            <input type="file" name="profile_image" class="form-control" accept="image/*">
            <small class="text-muted">Allowed: jpeg, png, jpg. Max: 2MB</small>
          </div>
        </div>
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-success">Register Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- jQuery --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
  $(document).ready(function() {
    function loadSections(divisionId, selectedSection = null) {
      $('#sectionSelect').html('<option>Loading...</option>');
      $.ajax({
        url: '{{ route("employee.sections") }}',
        type: 'GET',
        data: { division_id: divisionId },
        success: function(data) {
          $('#sectionSelect').html('<option value="">Choose...</option>');
          $.each(data, function(index, section) {
            $('#sectionSelect').append('<option value="' + section.id + '">' + section.name + '</option>');
          });
          if (selectedSection) {
            $('#sectionSelect').val(selectedSection);
          }
        }
      });
    }

    const oldDivision = '{{ old("division") }}';
    const oldSection = '{{ old("section") }}';
    if (oldDivision) {
      loadSections(oldDivision, oldSection);
    }

    $('#divisionSelect').on('change', function() {
      loadSections($(this).val());
    });
  });
</script>
@endsection
