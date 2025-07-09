@extends('layouts/contentNavbarLayout')

@section('title', 'Parenthetical Title')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Parenthetical Titles</h4>
      <button id="openModalBtn" class="btn btn-success">+ Add New Parenthetical Title</button>
    </div>

    <div class="table-responsive">
      <table id="parentheticalTitleTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Parenthetical Title</th>
            <th>Abbreviation</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($parentheticalTitles as $index => $parentheticalTitle)
          <tr data-id="{{ $parentheticalTitle->id }}">
            <td>{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ strtoupper($parentheticalTitle->position_name) }}</td>
            <td>{{ strtoupper($parentheticalTitle->abbreviation) }}</td>
            <td>
              <button class="btn btn-sm btn-primary edit-btn"
                data-id="{{ $parentheticalTitle->id }}"
                data-position_name="{{ $parentheticalTitle->position_name }}"
                data-abbreviation="{{ $parentheticalTitle->abbreviation }}">
                Edit
              </button>
              <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $parentheticalTitle->id }}">Delete</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="parentheticalTitleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add New Parenthetical Title</h5>
      <form id="parentheticalTitleForm">
        <div class="modal-body">
          <div class="mb-3">
            <label>Parenthetical Title</label>
            <input type="text" name="position_name" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Abbreviation</label>
            <input type="text" name="abbreviation" class="form-control text-uppercase" required>
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
<div class="modal fade" id="editParentheticalTitleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editParentheticalTitleForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Parenthetical Title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editParentheticalTitleId">
          <div class="mb-3">
            <label>Parenthetical Title</label>
            <input type="text" name="position_name" id="editParentheticalTitleName" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Abbreviation</label>
            <input type="text" name="abbreviation" id="editParentheticalTitleAbbreviation" class="form-control text-uppercase" required>
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
        Are you sure you want to delete this parenthetical title?
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
  $('#parentheticalTitleTable').DataTable();

  $('#openModalBtn').click(function() {
    $('#parentheticalTitleForm')[0].reset();
    new bootstrap.Modal(document.getElementById('parentheticalTitleModal')).show();
  });

  $('#parentheticalTitleForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: '{{ route("parenthetical-title.store") }}',
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        toastr.success('Parenthetical Title added successfully!');
        bootstrap.Modal.getInstance(document.getElementById('parentheticalTitleModal')).hide();
        setTimeout(() => location.reload(), 500);
      }
    });
  });

  $(document).on('click', '.edit-btn', function() {
    const data = $(this).data();
    $('#editParentheticalTitleId').val(data.id);
    $('#editParentheticalTitleName').val(data.position_name);
    $('#editParentheticalTitleAbbreviation').val(data.abbreviation);
    new bootstrap.Modal(document.getElementById('editParentheticalTitleModal')).show();
  });

  $('#editParentheticalTitleForm').submit(function(e) {
    e.preventDefault();
    const id = $('#editParentheticalTitleId').val();
    $.ajax({
      url: `/planning/parenthetical-title/${id}/update`,
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        toastr.success('Parenthetical Title updated successfully!');
        bootstrap.Modal.getInstance(document.getElementById('editParentheticalTitleModal')).hide();
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
      url: `/planning/parenthetical-title/${deleteId}/delete`,
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
</script>
@endpush
