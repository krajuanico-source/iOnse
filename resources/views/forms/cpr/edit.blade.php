@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Travel Order')

@section('content')
<div class="container py-4">
  <h3>Edit Travel Order</h3>
  <hr>

  <form method="POST" action="{{ route('forms.travel.update', $travelReferenceNumber) }}">
    @csrf
    @method('PUT')

    {{-- Table of employees in this batch --}}
    <table class="table table-bordered" id="travelTable">
      <thead>
        <tr>
          <th>Employee</th>
          <th>Travel Date</th>
          <th>Purpose</th>
          <th>Destination</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($travels as $index => $t)
        <tr>
          <td>
            <select name="empid[]" class="form-select" required>
              <option value="" disabled>Select employee</option>
              @foreach($employees as $employee)
              <option value="{{ $employee->employee_id }}"
                {{ $t->empid == $employee->employee_id ? 'selected' : '' }}>
                {{ $employee->first_name }} ({{ $employee->employee_id }})
              </option>
              @endforeach
            </select>
          </td>
          <td><input type="date" name="travel_date[]" class="form-control" value="{{ $t->travel_date }}" required></td>
          <td><input type="text" name="travel_purpose[]" class="form-control" value="{{ $t->travel_purpose }}" required></td>
          <td><input type="text" name="travel_destination[]" class="form-control" value="{{ $t->travel_destination }}" required></td>
          <td>
            <button type="button" class="btn btn-danger btn-sm removeRow">Remove</button>
          </td>
          <input type="hidden" name="id_travel[]" value="{{ $t->id_travel }}">
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="mb-3">
      <button type="button" id="addRow" class="btn btn-success btn-sm">Add Employee</button>
    </div>

    <div class="text-center mt-4">
      <button type="submit" class="btn btn-primary">Update Travel Orders</button>
    </div>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
  $(document).ready(function() {
    // Add new row
    $('#addRow').click(function() {
      let employeeOptions = `@foreach($employees as $employee)<option value="{{ $employee->employee_id }}">{{ $employee->first_name }} ({{ $employee->employee_id }})</option>@endforeach`;
      let newRow = `
        <tr>
          <td><select name="empid[]" class="form-select" required><option value="" disabled selected>Select employee</option>${employeeOptions}</select></td>
          <td><input type="date" name="travel_date[]" class="form-control" required></td>
          <td><input type="text" name="travel_purpose[]" class="form-control" required></td>
          <td><input type="text" name="travel_destination[]" class="form-control" required></td>
          <td><button type="button" class="btn btn-danger btn-sm removeRow">Remove</button></td>
        </tr>`;
      $('#travelTable tbody').append(newRow);
    });

    // Remove row
    $(document).on('click', '.removeRow', function() {
      $(this).closest('tr').remove();
    });
  });
</script>
@endsection
