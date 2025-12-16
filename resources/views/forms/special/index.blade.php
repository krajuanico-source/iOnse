@extends('layouts/contentNavbarLayout')

@section('title', 'Special Orders')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<div class="card p-4">

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary">Special Orders</h4>
    <a href="{{ route('forms.special.create') }}" class="btn btn-primary">New Special Order</a>
  </div>

  <!-- Special Orders Table -->
  <table id="specialTable" class="table table-bordered">
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
      // Group all items by special_reference_number
      $groupedSpecials = $specials->groupBy('special_reference_number');
      @endphp

      @foreach($groupedSpecials as $ref => $batch)
      <tr>
        <td>{{ $ref }}</td>

        <!-- EMPLOYEES -->
        <td>
          @php
          $employees = $batch->pluck('employee.full_name')->filter()->values()->all();
          $firstEmployee = $employees[0] ?? ($batch->first()->empid ?? 'N/A');
          @endphp

          {{ $firstEmployee }}
          @if(count($employees) > 1)
          et. al.
          @endif
        </td>

        <!-- PURPOSES -->
        <td>
          @php
          $purposes = $batch->pluck('special_purpose')->filter()->values()->all();
          $firstPurpose = $purposes[0] ?? 'N/A';
          @endphp

          {{ $firstPurpose }}
          @if(count($purposes) > 1)
          et. al.
          @endif
        </td>

        <!-- DESTINATIONS -->
        <td>
          @php
          $destinations = $batch->pluck('special_destination')->filter()->values()->all();
          $firstDestination = $destinations[0] ?? 'N/A';
          @endphp

          {{ $firstDestination }}
          @if(count($destinations) > 1)
          et. al.
          @endif
        </td>

        <!-- STATUS -->
        <td>{{ $batch->pluck('status')->unique()->implode(', ') }}</td>

        <!-- DATE REQUESTED -->
        <td>{{ \Carbon\Carbon::parse($batch->first()->date_requested)->format('Y-m-d H:i') }}</td>

        <!-- ACTION -->
        <td>
          <!-- Sign -->
          <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#signatureTypeModal_{{ $ref }}">
            Sign
          </button>

          <!-- Edit -->
          <a href="{{ route('forms.special.edit', $batch->first()->id) }}" class="btn btn-warning btn-sm">
            Edit
          </a>

          <!-- Delete -->
          <form action="{{ route('forms.special.destroy', $batch->first()->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this special batch?')">
              Delete
            </button>
          </form>

          <!-- Print -->
          <a href="{{ route('forms.special.print', $ref) }}" class="btn btn-primary btn-sm" target="_blank">
            Print
          </a>
        </td>
      </tr>

      <!-- Modals -->
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
  <div class="toast text-bg-success show">
    <div class="d-flex">
      <div class="toast-body">{{ session('success') }}</div>
      <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
@endif

@if(session('error'))
<div class="toast-container p-3 position-fixed top-0 end-0">
  <div class="toast text-bg-danger show">
    <div class="d-flex">
      <div class="toast-body">{{ session('error') }}</div>
      <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
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
    $('#specialTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });

  $(function() {

    $('.toast').toast({
      delay: 5000
    }).toast('show');
  });
</script>
@endsection
