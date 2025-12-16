@extends('layouts/contentNavbarLayout')

@section('title', 'Request Forms')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<div class="card p-4">

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary">Request Forms</h4>
    <a href="{{ route('forms.request_forms.create') }}" class="btn btn-primary">New Request</a>
  </div>

  <!-- Request Forms Table -->
  <table id="requestTable" class="table table-bordered">
    <thead>
      <tr>
        <th>Request No.</th>
        <th>Employee</th>
        <th>Type of Request</th>
        <th>Purpose</th>
        <th>Mode of Receipt</th>
        <th>Status</th>
        <th>Date Requested</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
      @foreach($requests as $req)
      <tr>
        <td>{{ $req->req_num }}</td>

        <td>
          @if($req->employee)
          {{ $req->employee->full_name }}
          @else
          {{ $req->empid }}
          @endif
        </td>

        <td>{{ $req->req_doc }}</td>
        <td>{{ $req->req_purpose }}</td>
        <td>{{ $req->req_mode }}</td>

        <td>
          <span class="badge
            @if($req->req_status === 'Approved') bg-success
            @elseif($req->req_status === 'Pending') bg-warning
            @elseif($req->req_status === 'Rejected') bg-danger
            @else bg-secondary @endif">
            {{ $req->req_status }}
          </span>
        </td>

        <td>{{ \Carbon\Carbon::parse($req->req_date)->format('Y-m-d') }}</td>

        <td>
          <a href="{{ route('forms.request_forms.show', $req->req_num) }}" class="btn btn-secondary btn-sm">View</a>

          <form action="{{ route('forms.request_forms.destroy', $req->req_num) }}"
            method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm"
              onclick="return confirm('Delete this request?')">
              Delete
            </button>
          </form>

          <a href="{{ route('forms.request_forms.print', $req->req_num) }}"
            class="btn btn-primary btn-sm" target="_blank">
            Print
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>

  </table>
</div>

<!-- Toast Notification -->
@if(session('success'))
<div class="toast-container p-3 position-fixed top-0 end-0">
  <div class="toast align-items-center text-bg-success border-0 show">
    <div class="d-flex">
      <div class="toast-body">{{ session('success') }}</div>
      <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
@endif

@if(session('error'))
<div class="toast-container p-3 position-fixed top-0 end-0">
  <div class="toast align-items-center text-bg-danger border-0 show">
    <div class="d-flex">
      <div class="toast-body">{{ session('error') }}</div>
      <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
@endif

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
  // DataTable Init
  jQuery(function($) {
    $('#requestTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });

  // Auto-show toast
  $(function() {
    $('.toast').toast({
      delay: 5000
    });
    $('.toast').toast('show');
  });
</script>

@endsection
