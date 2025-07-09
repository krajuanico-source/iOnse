@extends('layouts/contentNavbarLayout')

@section('title', 'Position Level')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Position Levels</h4>
      <button id="openModalBtn" class="btn btn-success">+ Add New Position Level</button>
    </div>

    <div class="table-responsive">
      <table id="positionLevelTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Level Name</th>
            <th>Abbreviation</th>
            <th>Level Rank</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($positionLevels as $index => $level)
          <tr data-id="{{ $level->id }}">
            <td>{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ Str::upper($level->level_name) }}</td>
            <td>{{ Str::upper($level->abbreviation) }}</td>
            <td>{{ $level->level_rank }}</td>
            <td>
              <div class="d-flex gap-1">
                <button class="btn btn-sm btn-primary edit-btn"
                  data-id="{{ $level->id }}"
                  data-level_name="{{ $level->level_name }}"
                  data-abbreviation="{{ $level->abbreviation }}"
                  data-level_rank="{{ $level->level_rank }}">
                  Edit
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $level->id }}">Delete</button>
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
<div class="modal fade" id="positionLevelModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add New Position Level</h5>
      <form id="positionLevelForm">
        <div class="modal-body">
          <div class="mb-3">
            <label>Level Name</label>
            <input type="text" name="level_name" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Abbreviation</label>
            <input type="text" name="abbreviation" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Level Rank</label>
            <input type="number" name="level_rank" class="form-control text-uppercase" required>
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
<div class="modal fade" id="editPositionLevelModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editPositionLevelForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Position Level</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editPositionLevelId">
          <div class="mb-3">
            <label>Level Name</label>
            <input type="text" name="level_name" id="editPositionLevelName" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Abbreviation</label>
            <input type="text" name="abbreviation" id="editPositionLevelAbbreviation" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Level Rank</label>
            <input type="number" name="level_rank" id="editPositionLevelRank" class="form-control text-uppercase" required>
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
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this position level?
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  $('#positionLevelTable').DataTable();

  $('#openModalBtn').click(function () {
    $('#positionLevelForm')[0].reset();
    new bootstrap.Modal(document.getElementById('positionLevelModal')).show();
  });

  $('#positionLevelForm').submit(function (e) {
    e.preventDefault();
    $.ajax({
      url: '{{ route("position-level.store") }}',
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
        toastr.success('Position Level added successfully!');
        bootstrap.Modal.getInstance(document.getElementById('positionLevelModal')).hide();
        setTimeout(() => location.reload(), 500);
      },
      error: function (xhr) {
        toastr.error('Error: ' + (xhr.responseJSON.message ?? 'Failed to add.'));
      }
    });
  });

  $(document).on('click', '.edit-btn', function () {
    const data = $(this).data();
    $('#editPositionLevelId').val(data.id);
    $('#editPositionLevelName').val(data.level_name);
    $('#editPositionLevelAbbreviation').val(data.abbreviation);
    $('#editPositionLevelRank').val(data.level_rank);
    new bootstrap.Modal(document.getElementById('editPositionLevelModal')).show();
  });

  $('#editPositionLevelForm').submit(function (e) {
    e.preventDefault();
    const id = $('#editPositionLevelId').val();
    $.ajax({
      url: `/planning/position-level/${id}/update`,
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
        toastr.success('Position Level updated successfully!');
        bootstrap.Modal.getInstance(document.getElementById('editPositionLevelModal')).hide();
        setTimeout(() => location.reload(), 500);
      },
      error: function (xhr) {
        toastr.error('Error: ' + (xhr.responseJSON.message ?? 'Failed to update.'));
      }
    });
  });

  let deleteId = null;
  $(document).on('click', '.delete-btn', function () {
    deleteId = $(this).data('id');
    new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
  });

  $('#confirmDeleteBtn').click(function () {
    $.ajax({
      url: `/planning/position-level/${deleteId}/delete`,
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
        toastr.success('Position Level deleted successfully!');
        bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
        setTimeout(() => location.reload(), 500);
      },
      error: function (xhr) {
        toastr.error('Error: ' + (xhr.responseJSON.message ?? 'Failed to delete.'));
      }
    });
  });
</script>
@endpush
