@extends('layouts/contentNavbarLayout')

@section('title', 'Salary Grades')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Salary Grades</h4>
      <button id="openModalBtn" class="btn btn-success">+ Add Salary Grade</button>
    </div>

    <div class="table-responsive">
      <table id="salaryGradeTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>SG Number</th>
            <th>Step Increment</th>
            <th>SG Amount</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($salaryGrades as $index => $grade)
          <tr data-id="{{ $grade->id }}">
            <td>{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ $grade->sg_num }}</td>
            <td>{{ number_format($grade->step_increment, 2) }}</td>
            <td>{{ number_format($grade->sg_amount, 2) }}</td>
            <td>
              <button class="btn btn-sm btn-primary edit-btn"
                data-id="{{ $grade->id }}"
                data-sg_num="{{ $grade->sg_num }}"
                data-step_increment="{{ $grade->step_increment }}"
                data-sg_amount="{{ $grade->sg_amount }}">
                Edit
              </button>
              <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $grade->id }}">Delete</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="salaryGradeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add Salary Grade</h5>
      <form id="salaryGradeForm">
        <div class="modal-body">
          <div class="mb-3">
            <label>SG Number</label>
            <input type="number" name="sg_num" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Step Increment</label>
            <input type="number" step="0.01" name="step_increment" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>SG Amount</label>
            <input type="number" step="0.01" name="sg_amount" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editSalaryGradeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editSalaryGradeForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Salary Grade</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editId">
          <div class="mb-3">
            <label>SG Number</label>
            <input type="number" name="sg_num" id="editSgNum" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Step Increment</label>
            <input type="number" step="0.01" name="step_increment" id="editStepIncrement" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>SG Amount</label>
            <input type="number" step="0.01" name="sg_amount" id="editSgAmount" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this salary grade?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  $('#openModalBtn').click(function() {
    $('#salaryGradeForm')[0].reset();
    var modal = new bootstrap.Modal(document.getElementById('salaryGradeModal'));
    modal.show();
  });

  $('#salaryGradeForm').submit(function(e) {
    e.preventDefault();

    const formData = {
      sg_num: parseInt($('input[name="sg_num"]').val()),
      step_increment: parseFloat($('input[name="step_increment"]').val()),
      sg_amount: parseFloat($('input[name="sg_amount"]').val()),
    };

    $.ajax({
      url: '{{ route("salary-grade.store") }}',
      method: 'POST',
      data: formData,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Salary Grade added successfully!');
          var modal = bootstrap.Modal.getInstance(document.getElementById('salaryGradeModal'));
          modal.hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Failed to add salary grade.");
        }
      },
      error: function(xhr) {
        toastr.error("Something went wrong: " + xhr.responseText);
      }
    });
  });

  $(document).on('click', '.edit-btn', function() {
    $('#editId').val($(this).data('id'));
    $('#editSgNum').val($(this).data('sg_num'));
    $('#editStepIncrement').val($(this).data('step_increment'));
    $('#editSgAmount').val($(this).data('sg_amount'));

    var editModal = new bootstrap.Modal(document.getElementById('editSalaryGradeModal'));
    editModal.show();
  });

  $('#editSalaryGradeForm').submit(function(e) {
    e.preventDefault();

    const id = $('#editId').val();
    const formData = {
      sg_num: parseInt($('#editSgNum').val()),
      step_increment: parseFloat($('#editStepIncrement').val()),
      sg_amount: parseFloat($('#editSgAmount').val()),
    };

    $.ajax({
      url: `/planning/salary-grade/${id}/update`,
      method: 'POST',
      data: formData,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Salary Grade updated successfully!');
          var modal = bootstrap.Modal.getInstance(document.getElementById('editSalaryGradeModal'));
          modal.hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Failed to update salary grade.");
        }
      },
      error: function(xhr) {
        toastr.error("Something went wrong: " + xhr.responseText);
      }
    });
  });

  let deleteId = null;

  $(document).on('click', '.delete-btn', function() {
    deleteId = $(this).data('id');
    var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    modal.show();
  });

  $('#confirmDeleteBtn').click(function() {
    if (!deleteId) return;

    $.ajax({
      url: `/planning/salary-grade/${deleteId}/delete`,
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Salary Grade deleted successfully!');
          var modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
          modal.hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Failed to delete salary grade.");
        }
      },
      error: function(xhr) {
        toastr.error("Something went wrong: " + xhr.responseText);
      }
    });
  });

  $('#salaryGradeTable').DataTable();
</script>
@endpush
