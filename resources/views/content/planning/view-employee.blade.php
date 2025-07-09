@extends('layouts/contentNavbarLayout')

@section('title', 'View Employee')

@section('content')
<div class="container py-4">

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Employee Details</h4>
   </div>
        <div class="d-flex justify-content-center mb-4">
          @if($employee->profile_image)
            <div style="width: 200px; height: 200px; border: 2px solid #1d4bb2; border-radius: 50%; overflow: hidden;">
              <img src="{{ asset($employee->profile_image) }}"
                  alt="Profile Image"
                  style="width: 100%; height: 100%; object-fit: cover;">
            </div>
          @else
            <div style="width: 200px; height: 200px; border: 4px solid #6c757d; border-radius: 50%; overflow: hidden;">
              <img src="{{ asset('default-user.png') }}"
                  alt="No Photo"
                  style="width: 100%; height: 100%; object-fit: cover;">
            </div>
          @endif
        </div>
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      <form>
        <div class="row mb-3">
          <label class="col-sm-3 col-form-label"><strong>ID No.</strong></label>
          <div class="col-sm-9">
            <input type="text" readonly class="form-control-plaintext" value="{{ $employee->employee_id }}">
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-3 col-form-label"><strong>Full Name</strong></label>
          <div class="col-sm-9">
            <input type="text" readonly class="form-control-plaintext"
              value="{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }} {{ $employee->extension_name }}">
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-3 col-form-label"><strong>Username</strong></label>
          <div class="col-sm-9">
            <input type="text" readonly class="form-control-plaintext" value="{{ $employee->username }}">
          </div>
        </div>

        {{-- ✅ Email --}}
        <div class="row mb-3">
          <label class="col-sm-3 col-form-label"><strong>Email</strong></label>
          <div class="col-sm-9">
            <input type="text" readonly class="form-control-plaintext" value="{{ $employee->email }}">
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-3 col-form-label"><strong>Employment Status</strong></label>
          <div class="col-sm-9">
            <input type="text" readonly class="form-control-plaintext"
              value="{{ $employee->employmentStatus->abbreviation ?? '-' }}">
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-3 col-form-label"><strong>Section</strong></label>
          <div class="col-sm-9">
            <input type="text" readonly class="form-control-plaintext"
              value="{{ $employee->section->abbreviation ?? '-' }}">
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-3 col-form-label"><strong>Division</strong></label>
          <div class="col-sm-9">
            <input type="text" readonly class="form-control-plaintext"
              value="{{ $employee->division->abbreviation ?? '-' }}">
          </div>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('employee.view-blade') }}" class="btn btn-secondary me-3">Back</a>
          <a href="{{ url('/employee/' . $employee->id . '/edit') }}" class="btn btn-primary">Edit</a>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>
@endsection
