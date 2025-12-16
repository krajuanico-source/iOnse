@extends('layouts.contentNavbarLayout')

@section('title', 'CPR List')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<div class="card p-4">
  <div class="d-flex justify-content-between mb-3">
    <h4>CPR List</h4>
    <!-- Button to trigger modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCprModal">
      Add New CPR
    </button>
  </div>

  <table class="table table-bordered" id="cprtable">
    <thead>
      <tr>
        <th>Rating Period</th>
        <th>Semester</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($cprs as $cpr)
      <tr>
        <td>{{ $cpr->rating_period_start }}</td>
        <td>{{ $cpr->semester }}</td>
        <td>
          @if($cpr->status == 'Active')
          <span class="badge bg-success">{{ $cpr->status }}</span>
          @else
          <span class="badge bg-secondary">{{ $cpr->status }}</span>
          @endif
        </td>
        <td>
          <a href="javascript:void(0);"
            class="btn btn-sm btn-primary editCprBtn"
            data-id="{{ $cpr->id }}"
            data-rating="{{ $cpr->rating_period_start }}"
            data-semester="{{ $cpr->semester }}"
            data-status="{{ $cpr->status }}">
            Edit
          </a>
          <form action="{{ route('forms.cpr.destroy', $cpr->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete CPR?')">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center">No CPRs found.</td>
      </tr>
      @endforelse
    </tbody>

  </table>
</div>
<!-- Modal for Add CPR -->
<div class="modal fade" id="addCprModal" tabindex="-1" aria-labelledby="addCprModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('forms.cpr.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addCprModalLabel">Add New CPR</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <!-- Rating Period Start -->
          <div class="mb-3">
            <label class="form-label">Rating Period </label>
            <input type="month" name="rating_period_start" class="form-control" required>
          </div>

          <!-- Semester -->
          <div class="mb-3">
            <label class="form-label">Semester</label>
            <select name="semester" class="form-select" required>
              <option value="" disabled selected>Select Semester</option>
              <option value="1st Semester">1st Semester</option>
              <option value="2nd Semester">2nd Semester</option>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit CPR Modal -->
<div class="modal fade" id="editCprModal" tabindex="-1" aria-labelledby="editCprModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form id="editCprForm" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Edit CPR</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <!-- Rating Period -->
          <div class="mb-3">
            <label class="form-label">Rating Period</label>
            <input type="month"
              name="rating_period_start"
              id="edit_rating_period_start"
              class="form-control"
              pattern="[0-9]{4}-[0-9]{2}"
              required>

          </div>

          <!-- Semester -->
          <div class="mb-3">
            <label class="form-label">Semester</label>
            <select name="semester" id="edit_semester" class="form-select" required>
              <option value="1st Semester">1st Semester</option>
              <option value="2nd Semester">2nd Semester</option>
            </select>
          </div>

          <!-- Status -->
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" id="edit_status" class="form-select" required>
              <option value="Active">Active</option>
              <option value="Not Active">Not Active</option>
            </select>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".editCprBtn").forEach(button => {
      button.addEventListener("click", function() {

        let id = this.dataset.id;
        let rating = this.dataset.rating;
        let semester = this.dataset.semester;
        let status = this.dataset.status;

        // Set form action URL
        document.getElementById("editCprForm").action = "/forms/cpr/" + id;

        // Fill fields
        document.getElementById("edit_rating_period_start").value = rating;
        document.getElementById("edit_semester").value = semester;
        document.getElementById("edit_status").value = status;

        // Open modal
        let modal = new bootstrap.Modal(document.getElementById("editCprModal"));
        modal.show();
      });
    });
  });
  // DataTable Init
  jQuery(function($) {
    $('#cprtable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>