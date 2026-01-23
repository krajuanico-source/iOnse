@extends('layouts/contentNavbarLayout')

@section('title', 'Leave List')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<div class="container py-4">
  <div class="d-flex justify-content-between mb-3">
    <h3>GAD Profiles</h3>
    <a href="{{ route('forms.gad_profile.create') }}" class="btn btn-primary">Add Profile</a>
  </div>


  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif


  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Employee ID</th>
        <th>Gender</th>
        <th>Date Submitted</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($profiles as $p)
      <tr>
        <td>{{ $p->id }}</td>
        <td>{{ $p->user_id }}</td>
        <td>{{ $p->gender }}</td>
        <td>{{ $p->submitted_at }}</td>
        <td>
          <a href="{{ route('forms.gad_profile.show',$p->id) }}" class="btn btn-sm btn-info">View</a>
          <a href="{{ route('forms.gad_profile.edit',$p->id) }}" class="btn btn-sm btn-warning">Edit</a>
          <a href="{{ route('forms.gad_profile.print',$p->id) }}" class="btn btn-sm btn-secondary" target="_blank">Print</a>
        </td>
      </tr>
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
    $('#leaveTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>
@endsection