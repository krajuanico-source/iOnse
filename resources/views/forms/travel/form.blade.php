@extends('layouts/contentNavbarLayout')

@section('title', 'Travel Order')

@section('content')
<div class="card p-4" style="max-width: 1200px; margin:auto;">
  <div class="container py-4">
    <h3>Create Travel Order</h3>
    <hr>

    <form method="POST" action="{{ route('forms.travel.store') }}">
      @csrf

      <div id="travelRows">
        <div class="travel-row row mb-3">
          {{-- Employee --}}
          <div class="col-md-3">
            <label class="form-label">Employee</label>
            <select name="user_id[]" class="form-select" required>
              <option value="" disabled selected>Select employee</option>
              @foreach($employees as $employee)
              <option value="{{ $employee->employee_id }}">
                {{ $employee->first_name }} ({{ $employee->employee_id }})
              </option>
              @endforeach
            </select>
          </div>

          {{-- Travel Date --}}
          <div class="col-md-3">
            <label class="form-label">Travel Date</label>
            <input type="date" name="travel_date[]" class="form-control" required>
          </div>

          {{-- Purpose --}}
          <div class="col-md-3">
            <label class="form-label">Purpose</label>
            <input type="text" name="travel_purpose[]" class="form-control" required>
          </div>

          {{-- Destination --}}
          <div class="col-md-2">
            <label class="form-label">Destination</label>
            <input type="text" name="travel_destination[]" class="form-control" required>
          </div>

          <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-danger remove-row">-</button>
          </div>
        </div>
      </div>

      <button type="button" class="btn btn-secondary mb-3" id="addRow">Add Employee</button>

      <div class="text-center">
        <button type="submit" class="btn btn-primary">Save Travel Order</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById('addRow').addEventListener('click', function() {
    const travelRows = document.getElementById('travelRows');
    const newRow = travelRows.children[0].cloneNode(true);

    // Clear values
    newRow.querySelectorAll('select, input').forEach(input => input.value = '');
    travelRows.appendChild(newRow);
  });

  document.getElementById('travelRows').addEventListener('click', function(e) {
    if (e.target && e.target.matches('button.remove-row')) {
      const rows = document.querySelectorAll('.travel-row');
      if (rows.length > 1) { // Keep at least one row
        e.target.closest('.travel-row').remove();
      }
    }
  });
</script>
@endsection