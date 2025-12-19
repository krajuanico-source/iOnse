@extends('layouts/contentNavbarLayout')

@section('title', 'CPR Form')

@section('content')
<div class="card p-4" style="max-width: 1200px; margin:auto;">
  <div class="container py-4">
    <h3>Create CPR Details</h3>
    <hr>

    <form method="POST" action="{{ route('forms.cpr.store') }}">
      @csrf

      <div id="cprRows">
        <div class="cpr-row row mb-3">
          <div class="col-md-3">
            <label class="form-label">Employee ID</label>
            <input type="text" name="empid" class="form-control"
              value="{{ $employee->employee_id }}" readonly>
          </div>

          {{-- Rating Period --}}
          <div class="col-md-3">
            <label class="form-label">Rating Period</label>
            <input type="text" name="rating_period[]" class="form-control" placeholder="e.g., Jan-Jun 2025" required>
          </div>

          {{-- Performance Rating --}}
          <div class="col-md-3">
            <label class="form-label">Performance Rating</label>
            <input type="number" step="0.01" min="1" max="5" name="performance_rating[]" class="form-control" required>
          </div>

          {{-- Remarks --}}
          <div class="col-md-3">
            <label class="form-label">Remarks</label>
            <input type="text" name="remarks[]" class="form-control">
          </div>

        </div>
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-primary">Save CPR Details</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById('addRow').addEventListener('click', function() {
    const cprRows = document.getElementById('cprRows');
    const newRow = cprRows.children[0].cloneNode(true);

    // Clear values
    newRow.querySelectorAll('select, input').forEach(input => input.value = '');
    cprRows.appendChild(newRow);
  });

  document.getElementById('cprRows').addEventListener('click', function(e) {
    if (e.target && e.target.matches('button.remove-row')) {
      const rows = document.querySelectorAll('.cpr-row');
      if (rows.length > 1) { // Keep at least one row
        e.target.closest('.cpr-row').remove();
      }
    }
  });
</script>
@endsection
