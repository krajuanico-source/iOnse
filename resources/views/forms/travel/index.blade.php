@extends('layouts/contentNavbarLayout')

@section('title', 'Travel Orders')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<div class="card p-4">

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary">Travel Orders</h4>
    <a href="{{ route('forms.travel.create') }}" class="btn btn-primary">New Travel Order</a>
  </div>

  <!-- Travel Orders Table -->
  <table id="travelTable" class="table table-bordered">
    <thead>
      <tr>
        <th>Reference No</th>
        <th>Employees</th>
        <th>Purposes</th>
        <th>Destinations</th>
        <th>Status</th>
        <th>Date Requested</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @php
      $groupedTravels = $travels->groupBy('travel_reference_number');
      @endphp

      @foreach($groupedTravels as $ref => $batch)
      <tr>
        <td>{{ $ref }}</td>
        <td>
          @php
          $employees = $batch->pluck('employee.full_name')->filter()->all();
          $firstEmployee = $employees[0] ?? ($batch->first()->empid ?? 'N/A');
          $employeeCount = count($employees);
          @endphp

          {{ $firstEmployee }}
          @if($employeeCount > 1)
          et. al.
          @endif
        </td>

        <td>
          @php
          $purposes = $batch->pluck('travel_purpose')->filter()->all();
          $firstPurpose = $purposes[0] ?? 'N/A';
          @endphp
          {{ $firstPurpose }}
          @if(count($purposes) > 1)@endif
        </td>

        <td>
          @php
          $destinations = $batch->pluck('travel_destination')->filter()->all();
          $firstDestination = $destinations[0] ?? 'N/A';
          @endphp
          {{ $firstDestination }}
          @if(count($destinations) > 1) @endif
        </td>

        <td>{{ $batch->pluck('status')->unique()->implode(', ') }}</td>
        <td>{{ \Carbon\Carbon::parse($batch->first()->date_requested)->format('Y-m-d H:i') }}</td>
        <td>
          <!-- Action Buttons -->
          <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#signatureTypeModal_{{ $ref }}">
            Sign
          </button>
          <a href="{{ route('forms.travel.edit', $ref) }}" class="btn btn-warning btn-sm">Edit</a>
          <form action="{{ route('forms.travel.destroy', $ref) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this travel batch?')">Delete</button>
          </form>
          <a href="{{ route('forms.travel.print', $ref) }}" class="btn btn-primary btn-sm" target="_blank">Print</a>
        </td>
      </tr>

      <!-- Modals (inside the loop, so $ref is available) -->
      <x-modals.signature-type :ref="$ref" />
      <x-modals.digital-signature :ref="$ref" />
      <x-modals.electronic-signature :ref="$ref" />

      @endforeach
    </tbody>

  </table>

</div>

<!-- Toast Notification -->
@if(session('success'))
<div class="toast-container p-3 position-fixed top-0 end-0">
  <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">{{ session('success') }}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
@endif

@if(session('error'))
<div class="toast-container p-3 position-fixed top-0 end-0">
  <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">{{ session('error') }}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
@endif

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  // DataTable Init
  jQuery(function($) {
    $('#travelTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
  $(function() {
    // Auto-show toast
    $('.toast').toast({
      delay: 5000
    });
    $('.toast').toast('show');
  });
</script>
@endsection
