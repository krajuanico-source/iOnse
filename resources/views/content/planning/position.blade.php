@extends('layouts/contentNavbarLayout')

@section('title', 'Positions')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Positions</h4>
      <button id="openModalBtn" class="btn btn-success">+ Add New Position</button>
    </div>

    <div class="table-responsive">
      <table id="positionTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Position Name</th>
            <th>Abbreviation</th>
            <th>Item No</th>
            <th>Salary Grade</th>
            <th>Employment Status</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($positions as $index => $position)
          <tr data-id="{{ $position->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $position->position_name }}</td>
            <td>{{ $position->abbreviation }}</td>
            <td>{{ $position->item_no }}</td>
            <td>{{ $position->salaryGrade->sg_num ?? '' }}</td>
            <td>{{ $position->employmentStatus->name ?? '' }}</td>
            <td>{{ ucfirst($position->status) }}</td>
            <td class="text-nowrap">
              <div class="d-inline-flex gap-1">
                <button class="btn btn-sm btn-primary edit-btn"
                  data-id="{{ $position->id }}"
                  data-position_name="{{ $position->position_name }}"
                  data-abbreviation="{{ $position->abbreviation }}"
                  data-item_no="{{ $position->item_no }}"
                  data-salary_grade_id="{{ $position->salary_grade_id }}"
                  data-employment_status_id="{{ $position->employment_status_id }}"
                  data-status="{{ $position->status }}">
                  Edit
                </button>

                <button class="btn btn-sm btn-danger delete-btn"
                  data-id="{{ $position->id }}">
                  Delete
                </button>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="positionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add New Position</h5>
      <form id="positionForm">
        <div class="modal-body">
          @include('content.planning.position_form')
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
<div class="modal fade" id="editPositionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Edit Position</h5>
      <form id="editPositionForm">
        <input type="hidden" name="id" id="editPositionId">
        <div class="modal-body">
          @include('content.planning.position_form_edit')
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
      </div>
      <div class="modal-body">Are you sure you want to delete this position?</div>
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
    $('#positionForm')[0].reset();
    new bootstrap.Modal(document.getElementById('positionModal')).show();
  });

  $('#positionForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: '{{ route("position.store") }}',
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        toastr.success('Position added successfully!');
        bootstrap.Modal.getInstance(document.getElementById('positionModal')).hide();
        setTimeout(() => location.reload(), 500);
      }
    });
  });

  $(document).on('click', '.edit-btn', function() {
    let data = $(this).data();
    $('#editPositionId').val(data.id);
    $('#edit_position_name').val(data.position_name);
    $('#edit_abbreviation').val(data.abbreviation);
    $('#edit_item_no').val(data.item_no);
    $('#edit_salary_grade_id').val(data.salary_grade_id);
    $('#edit_employment_status_id').val(data.employment_status_id);
    $('#edit_status').val(data.status);
    new bootstrap.Modal(document.getElementById('editPositionModal')).show();
  });

  $('#editPositionForm').submit(function(e) {
    e.preventDefault();
    let id = $('#editPositionId').val();
    $.ajax({
      url: `/planning/position/${id}/update`,
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        toastr.success('Position updated successfully!');
        bootstrap.Modal.getInstance(document.getElementById('editPositionModal')).hide();
        setTimeout(() => location.reload(), 500);
      }
    });
  });

  let deleteId = null;
  $(document).on('click', '.delete-btn', function() {
    deleteId = $(this).data('id');
    new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
  });

  $('#confirmDeleteBtn').click(function() {
    $.ajax({
      url: `/planning/position/${deleteId}/delete`,
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        toastr.success('Deleted successfully!');
        bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
        setTimeout(() => location.reload(), 500);
      }
    });
  });

  $('#positionTable').DataTable();
</script>
@endpush
