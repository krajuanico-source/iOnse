@extends('layouts/contentNavbarLayout')

@section('title', 'Out Slip Form')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card p-4" style="max-width: 700px; margin:auto;">
  <h4 class="mb-4 text-primary">Out Slip Form</h4>

  <form id="outSlipForm">
    @csrf
    <div class="mb-3">
      <label>Date</label>
      <input type="date" name="date" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Employee ID</label>
      <input type="text" name="user_id" class="form-control" value="{{ $user_id }}" readonly>
    </div>

    <div class="mb-3">
      <label>Destination</label>
      <textarea name="destination" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
      <label>Type of Slip</label>
      <select name="type_of_slip" class="form-select" required>
        <option value="">Select type...</option>
        <option value="Official ">Official</option>
        <option value="Personal">Personal</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Purpose</label>
      <textarea name="purpose" class="form-control"></textarea>
    </div>

    <div class="text-end">
      <button type="submit" class="btn btn-primary">Apply</button>
    </div>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  $(document).ready(function() {
    $('#outSlipForm').on('submit', function(e) {
      e.preventDefault();

      $.ajax({
        url: '{{ route("outslips.store") }}',
        method: 'POST',
        data: $(this).serialize(),
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
          toastr.success(res.message);
          // Redirect to Out Slips index page
          window.location.href = '{{ route("outslips.index") }}';
        },
        error: function(xhr) {
          const errors = xhr.responseJSON?.errors;
          if (errors) {
            Object.values(errors).forEach(msg => toastr.error(msg[0]));
          } else {
            toastr.error('Failed to submit out slip.');
          }
        }
      });
    });
  });
</script>

@endsection
