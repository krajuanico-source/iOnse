@extends('layouts/contentNavbarLayout')

@section('title', 'Leave List')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<div class="card p-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary">Leave List</h4>
  </div>


  <table id="leaveTable" class="table table-bordered">
    <thead>
      <tr>
        <th>Leave No</th>
        <th>Employee</th>
        <th>Type</th>
        <th>From</th>
        <th>To</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($leaves as $leave)
      <tr>
        <td>{{ $leave->leave_no }}</td>
        <td>{{ $leave->employee->full_name ?? 'N/A' }}</td>
        <td>{{ $leave->leave_type }}</td>
        <td>{{ $leave->from_date }}</td>
        <td>{{ $leave->to_date }}</td>
        <td>{{ $leave->status }}</td>
        <td>
          <!-- Approve button triggers signature modal -->
          <button class="btn btn-success btn-sm approve-btn"
            data-ref="{{ $leave->leave_no }}"
            data-bs-toggle="modal"
            data-bs-target="#signatureTypeModal_{{ $leave->leave_no }}"
            @if($leave->status === 'Approved') disabled @endif>
            Approve
          </button>

          <form action="{{ route('leaves.destroy', $leave->leave_no) }}" method="POST" style="display:inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
          </form>

        </td>
      </tr>

      <!-- Signature Modals -->
      <x-modals.signature-type-leave :ref="$leave->leave_no" />
      <x-modals.digital-signature-leave :ref="$leave->leave_no" />
      <x-modals.electronic-signature-leave :ref="$leave->leave_no" />
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
      $.post('/forms/leaves/' + id + '/reject', {
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

      $.post('/forms/leaves/' + id + '/approve', {
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
      $.post('/forms/leaves/' + id + '/approve', {
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
    $('#leaveTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>
@endsection