@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Special Order')

@section('content')
<div class="container py-4">
  <h3>Edit Special Order</h3>
  <hr>

  <form method="POST" action="{{ route('forms.special.update', $specialReferenceNumber) }}">
    @csrf
    @method('PUT')

    {{-- Table of employees in this batch --}}
    <table class="table table-bordered" id="specialTable">
      <thead>
        <tr>
          <th>Employee</th>
          <th>Special Date</th>
          <th>Purpose</th>
          <th>Destination</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($specials as $index => $t)
        <tr>
          <td>
            <select name="user_id[]" class="form-select" required>
              <option value="" disabled>Select employee</option>
              @foreach($employees as $employee)
              <option value="{{ $employee->employee_id }}"
                {{ $t->user_id == $employee->employee_id ? 'selected' : '' }}>
                {{ $employee->first_name }} ({{ $employee->employee_id }})
              </option>
              @endforeach
            </select>
          </td>
          <td><input type="date" name="special_date[]" class="form-control" value="{{ $t->special_date }}" required></td>
          <td><input type="text" name="special_purpose[]" class="form-control" value="{{ $t->special_purpose }}" required></td>
          <td><input type="text" name="special_destination[]" class="form-control" value="{{ $t->special_destination }}" required></td>
          <td>
            <button type="button" class="btn btn-danger btn-sm removeRow">Remove</button>
          </td>
          <input type="hidden" name="id_special[]" value="{{ $t->id_special }}">
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="mb-3">
      <button type="button" id="addRow" class="btn btn-success btn-sm">Add Employee</button>
    </div>

    <div class="text-center mt-4">
      <button type="submit" class="btn btn-primary">Update special Orders</button>
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
          <td><select name="user_id[]" class="form-select" required><option value="" disabled selected>Select employee</option>${employeeOptions}</select></td>
          <td><input type="date" name="special_date[]" class="form-control" required></td>
          <td><input type="text" name="special_purpose[]" class="form-control" required></td>
          <td><input type="text" name="special_destination[]" class="form-control" required></td>
          <td><button type="button" class="btn btn-danger btn-sm removeRow">Remove</button></td>
        </tr>`;
      $('#specialTable tbody').append(newRow);
    });

    // Remove row
    $(document).on('click', '.removeRow', function() {
      $(this).closest('tr').remove();
    });
  });
</script>
@endsection