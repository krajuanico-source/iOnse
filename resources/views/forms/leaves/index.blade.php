@extends('layouts/contentNavbarLayout')

@section('title', 'Divisions')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<div class="card p-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary">Leave List</h4>

    <a href="{{ url('forms/leaves/form') }}" class="btn btn-primary">Apply Leave</a>
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
          <a href="{{ route('leaves.edit', $leave->leave_no) }}" class="btn btn-warning btn-sm">Edit</a>

          <form action="{{ route('leaves.destroy', $leave->leave_no) }}" 
                method="POST" style="display:inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
              Delete
            </button>
          </form>

          <a href="{{ route('leaves.print', $leave->leave_no) }}" 
             target="_blank" 
             class="btn btn-primary btn-sm">
            Print
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  jQuery(function($) {
    $('#leaveTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>

@endsection
