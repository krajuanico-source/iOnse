@extends('layouts/contentNavbarLayout')

@section('title', 'OutSlip List')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<div class="card p-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary">Outslip List</h4>
    <!-- New Out Slip Button -->
    <a href="{{ url('forms/outslip/form') }}" class="btn btn-primary">Apply Outslip</a>
  </div>

  <table id="outslipTable" class="table table-bordered">
    <thead>
      <tr>
        <th>Date</th>
        <th>Employee ID</th>
        <th>Destination</th>
        <th>Type</th>
        <th>Purpose</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($outSlips as $slip)
      <tr>
        <td>{{ $slip->date }}</td>
        <td>{{ $slip->empid }}</td>
        <td>{{ $slip->destination }}</td>
        <td>{{ $slip->type_of_slip }}</td>
        <td>{{ $slip->purpose }}</td>
        <td>{{ $slip->status }}</td>
        <td>
          <!-- Approve button -->
          <button class="btn btn-success btn-sm approve-btn"
            data-ref="{{ $slip->id }}"
            data-bs-toggle="modal"
            data-bs-target="#signatureTypeModal_{{ $slip->id }}"
            @if($slip->status === 'Approved') disabled @endif>
            Approve
          </button>

          <!-- Reject button -->
          <button class="btn btn-danger btn-sm reject-btn"
            data-id="{{ $slip->id }}"
            @if($slip->status === 'Approved') disabled @endif>
            Reject
          </button>

          <!-- Print button (always enabled) -->
          <a href="{{ url('forms/outslips/'.$slip->id.'/print') }}"
            target="_blank" class="btn btn-secondary btn-sm">
            Print
          </a>
        </td>


      </tr>

      <!-- Signature Modals -->
      <x-modals.signature-type :ref="$slip->id" />
      <x-modals.digital-signature-outslip :ref="$slip->id" />
      <x-modals.electronic-signature-outslip :ref="$slip->id" />
      @endforeach
    </tbody>
  </table>

</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  $(document).ready(function() {
    // Cancel (Reject) button click
    $('.rejects').click(function() {
      const id = $(this).data('id');

      // Confirmation dialog
      if (!confirm('Are you sure you want to cancel this Out Slip?')) {
        return; // Stop if user clicks "Cancel"
      }

      // AJAX request to reject the out slip
      $.post('/forms/outslips/' + id + '/reject', {
        _token: '{{ csrf_token() }}'
      }, function(res) {
        toastr.error(res.message);
        location.reload();
      });
    });

    // Approve button click
    $('.approve').click(function() {
      const id = $(this).data('id');

      if (!confirm('Are you sure you want to approve this Out Slip?')) {
        return; // Stop if user clicks "Cancel"
      }

      $.post('/forms/outslips/' + id + '/approve', {
        _token: '{{ csrf_token() }}'
      }, function(res) {
        toastr.success(res.message);
        location.reload();
      });
    });
  });
</script>


<script>
  $(document).ready(function() {
    $('.approve').click(function() {
      const id = $(this).data('id');
      $.post('/forms/outslips/' + id + '/approve', {
        _token: '{{ csrf_token() }}'
      }, function(res) {
        toastr.success(res.message);
        location.reload();
      });
    });

    $('.reject').click(function() {
      const id = $(this).data('id');
      $.post('/forms/outslips/' + id + '/reject', {
        _token: '{{ csrf_token() }}'
      }, function(res) {
        toastr.error(res.message);
        location.reload();
      });
    });
  });

  // DataTable Init
  jQuery(function($) {
    $('#outslipTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>
@endsection