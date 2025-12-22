@extends('layouts/contentNavbarLayout')

@section('title', 'Employee Details')

@section('content')
<div class="container mb-4">
  <div class="card">
    <div class="card-header bg-white">
      <h5 style="color: #1d4bb2;" class="mb-0">Employee Details</h5>
    </div>
    <div class="card-body">
      <a href="{{ route('employee.view-blade') }}" class="btn btn-secondary mb-3">Back to List</a>

      <div class="row mb-3">
        <div class="col-md-4">
          <label><strong>Employee ID:</strong></label>
          <p>{{ $employee->employee_id }}</p>
        </div>

        <div class="col-md-4">
          <label><strong>Name:</strong></label>
          <p>
            {{ $employee->first_name }}
            {{ $employee->middle_name ? $employee->middle_name[0] . '.' : '' }}
            {{ $employee->last_name }}
            {{ $employee->extension_name }}
          </p>
        </div>

        <div class="col-md-4">
          <label><strong>Username:</strong></label>
          <p>{{ $employee->username }}</p>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label><strong>Division:</strong></label>
          <p>{{ $employee->division->name ?? 'N/A' }}</p>
        </div>

        <div class="col-md-4">
          <label><strong>Section:</strong></label>
          <p>{{ $employee->section->name ?? 'N/A' }}</p>
        </div>

        <div class="col-md-4">
          <label><strong>Employment Status:</strong></label>
          <p>{{ $employee->employmentStatus->abbreviation ?? 'N/A' }}</p>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-warning">Edit</a>

        <form action="{{ route('employee.delete', $employee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
