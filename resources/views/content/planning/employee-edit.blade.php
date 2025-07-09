@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Employee')

@section('content')
<div class="container mb-4">
  <div class="card">
    <div class="card-header bg-white">
      <h5 style="color: #1d4bb2;" class="mb-0">Edit Employee</h5>
    </div>
    <div class="card-body">

      {{-- Display Validation Errors --}}
      @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form action="{{ route('employee.update', $employee->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Employee ID --}}
        <div class="row mb-3">
          <div class="col-md-4">
            <label>Employee ID</label>
            <input type="text" class="form-control" name="employee_id" value="{{ old('employee_id', $employee->employee_id) }}" required>
          </div>
          <div class="col-md-4">
            <label>First Name</label>
            <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
          </div>
          <div class="col-md-4">
            <label>Middle Name</label>
            <input type="text" class="form-control" name="middle_name" value="{{ old('middle_name', $employee->middle_name) }}">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label>Last Name</label>
            <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
          </div>
          <div class="col-md-4">
            <label>Extension Name</label>
            <select class="form-select" name="extension_name">
              <option value="">Choose...</option>
              @foreach(['JR','SR','II','III','IV'] as $ext)
              <option value="{{ $ext }}" {{ old('extension_name', $employee->extension_name) == $ext ? 'selected' : '' }}>{{ $ext }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label>Employment Status</label>
            <select class="form-select" name="employment_status" required>
              <option value="" disabled>Choose...</option>
              @foreach($employmentStatuses as $status)
              <option value="{{ $status->id }}" {{ old('employment_status', $employee->employment_status_id) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Division and Section --}}
        <div class="row mb-3">
          <div class="col-md-4">
            <label>Division</label>
            <select class="form-select" name="division" id="divisionSelect" required>
              <option value="" disabled>Choose...</option>
              @foreach($divisions as $division)
              <option value="{{ $division->id }}" {{ old('division', $employee->division_id) == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label>Section</label>
            <select class="form-select" name="section" id="sectionSelect" required>
              <option value="">Choose...</option>
              @foreach($sections as $section)
              <option value="{{ $section->id }}" {{ old('section', $employee->section_id) == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label>Password (Leave blank if unchanged)</label>
            <input type="password" class="form-control" name="password">
          </div>
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Update Employee</button>
        </div>

      </form>

    </div>
  </div>
</div>

{{-- jQuery for dependent dropdown --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
  $(document).ready(function() {
    $('#divisionSelect').on('change', function() {
      const divisionId = $(this).val();
      $('#sectionSelect').empty().append('<option>Loading...</option>');

      $.ajax({
        url: '{{ route("employee.sections") }}',
        type: 'GET',
        data: {
          division_id: divisionId
        },
        success: function(data) {
          $('#sectionSelect').empty().append('<option value="">Choose...</option>');
          $.each(data, function(key, section) {
            $('#sectionSelect').append('<option value="' + section.id + '">' + section.name + '</option>');
          });
        }
      });
    });
  });
</script>

@endsection
