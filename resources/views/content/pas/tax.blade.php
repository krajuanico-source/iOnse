@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Tax')


@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />



<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4>Tax Computation Table</h4>
      <button id="openModalBtn" class="btn btn-success">Add New Tax Column</button>
    </div>

    <div class="table-responsive">
      <table id="taxTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Salary Grade</th>
            <th>Tax</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($taxes as $index => $tax_item)
          <tr data-id="{{ $tax_item->id }}">
            <td>{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ $tax_item->salary_grade }}</td>
            <td>{{ $tax_item->tax }}</td>
            <td>
              <button
                class="btn btn-sm btn-primary edit-btn"
                data-id="{{ $tax_item->id }}"
                data-salary_grade="{{ $tax_item->salary_grade }}"
                data-tax="{{ $tax_item->tax }}">
                Edit
              </button>
              <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $tax_item->id }}">Delete</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="taxModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add New Tax Details</h5>
      <form id="taxForm">
        <div class="modal-body">
          <div class="mb-3">
            <label>Salary Grade</label>
            <input type="number" name="salary_grade" value="{{ old('salary_grade') }}" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Tax</label>
            <input type="number" name="tax" value="{{ old('tax') }}" class="form-control" required>
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
<div class="modal fade" id="edittaxModal" tabindex="-1" aria-labelledby="edittaxModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="edittaxForm">
        <div class="modal-header">
          <h5 class="modal-title" id="edittaxModalLabel">Edit Tax</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edittaxId">
          <div class="mb-3">
            <label>Salary Grade</label>
            <input type="number" name="salary_grade" value="{{ old('salary_grade') }}" id="edittaxName" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Tax</label>
            <input type="number" name="tax" value="{{ old('tax') }}" id="edittax" class="form-control" required>
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

<!--Delete-->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this tax?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

</div>

</>@endsection



@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  $('#openModalBtn').click(function() {
    $('#taxForm')[0].reset();
    var modal = new bootstrap.Modal(document.getElementById('taxModal'));
    modal.show();
  });
  $('#taxForm').submit(function(e) {
    e.preventDefault();

    $.ajax({
      url: '{{ route("tax.store") }}',
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Tax Details Added Successfully!');
          var modal = bootstrap.Modal.getInstance(document.getElementById('taxModal'));
          modal.hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Failed to Add Tax Details.");
        }
      },
      error: function(xhr, status, error) {
        console.log("XHR Response: ", xhr.responseText);
        console.log("Status: ", status);
        console.log("Error: ", error);
        toastr.error("Something went wrong: Check console log");
      }
    });

  });
  $(document).on('click', '.edit-btn', function() {
    const id = $(this).data('id');
    const salary_grade = $(this).data('salary_grade');
    const tax = $(this).data('tax');

    $('#edittaxId').val(id);
    $('#edittaxName').val(salary_grade);
    $('#edittax').val(tax);

    var editModal = new bootstrap.Modal(document.getElementById('edittaxModal'));
    editModal.show();
  });
  $('#edittaxForm').submit(function(e) {
    e.preventDefault();

    const id = $('#edittaxId').val();

    $.ajax({
      url: `/pas/tax/${id}/update`,
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Tax Details Updated Successfully!');
          var editModal = bootstrap.Modal.getInstance(document.getElementById('edittaxModal'));
          editModal.hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Failed to Update Tax Details.");
        }
      },
      error: function(xhr, status, error) {
        console.log("XHR Response: ", xhr.responseText);
        console.log("Status: ", status);
        console.log("Error: ", error);
        toastr.error("Something went wrong: Check console log");
      }
    });
  });
  let deleteId = null;

  $(document).on('click', '.delete-btn', function() {
    deleteId = $(this).data('id');
    var deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    deleteModal.show();
  });

  $('#confirmDeleteBtn').click(function() {
    if (!deleteId) return;

    $.ajax({
      url: `/pas/tax/${deleteId}/delete`,
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Tax Details Deleted Successfully!');
          var deleteModal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
          deleteModal.hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Failed to Delete Tax Details.");
        }
      },
      error: function(xhr, status, error) {
        console.log("XHR Response: ", xhr.responseText);
        console.log("Status: ", status);
        console.log("Error: ", error);
        toastr.error("Something went wrong: Check console log");
      }
    });
  });
</script>
<script>
  jQuery(function($) {
    $('#taxTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>

@endpush
