@extends('layouts/contentNavbarLayout')

@section('title', 'Educational Background')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
 
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">Educational Background</h4>
      <button id="openEducationModalBtn" class="btn btn-success">Add Education</button>
    </div>

    <div class="table-responsive">
        <table id="educationTable" class="table">
        <thead class="table-light text-center ">
            <tr>
            <th style="width: 10%;">Level of Education</th>
            <th style="width: 10%;">Name of School</th>
            <th style="width: 14%;">Degree / Course</th>
            <th style="width: 7%;">From</th>
            <th style="width: 7%;">To</th>
            <th style="width: 13%;">Highest Level Earned</th>
            <th style="width: 8%;">Year Graduated</th>
            <th style="width: 14%;">Action</th>
            </tr>
        </thead>
        <tbody>
          @foreach($educations as $index => $edu)
          <tr data-id="{{ $edu->id }}">
            <td>{{ $edu->level_of_education }}</td>
            <td>{{ $edu->school_name }}</td>
            <td>{{ $edu->degree_course }}</td>
            <td>{{ $edu->from }}</td>
            <td>{{ $edu->to }}</td>
            <td>{{ $edu->highest_level_earned }}</td>
            <td>{{ $edu->year_graduated }}</td>
            <td>
              <button
                class="btn btn-sm btn-primary edit-education-btn"
                data-id="{{ $edu->id }}"
                data-level="{{ $edu->level_of_education }}"
                data-school="{{ $edu->school_name }}"
                data-degree="{{ $edu->degree_course }}"
                data-from="{{ $edu->from }}"
                data-to="{{ $edu->to }}"
                data-level-earned="{{ $edu->highest_level_earned }}"
                data-year="{{ $edu->year_graduated }}">
                Update
              </button>
              <button class="btn btn-sm btn-danger delete-education-btn" data-id="{{ $edu->id }}">Delete</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="educationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <h5 class="modal-title m-4">Add Education</h5>
      <form id="educationForm">
        @csrf
        <div class="modal-body">
          <div class="row mb-5">
            <div class="col-md-12">
              <label>Level of Education</label>
              <select name="level_of_education" class="form-select" required>
                <option value="">Select Level</option>
                <option value="Elementary">Elementary</option>
                <option value="Secondary">Secondary</option>
                <option value="Vocational">Vocational</option>
                <option value="College">College</option>
                <option value="Graduate">Graduate</option>
              </select>
            </div>
            <div class="col-md-12 mt-4">
              <label>Name of School</label>
              <input type="text" name="school_name" class="form-control" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-12">
              <label>Degree / Course</label>
              <input type="text" name="degree_course" class="form-control">
            </div>
            </div>
            <div class="row mb-3 mt-4">
            <div class="col-md-6">
              <label>Highest Level Earned</label>
              <input type="text" name="highest_level_earned" class="form-control">
            </div>
            <div class="col-md-6">
              <label>Scholarship / Honors</label>
              <input type="text" name="scholarship_honors" class="form-control">
            </div>
          </div>
            <div class="row mb-3">
            <div class="col-md-4">
              <label>From</label>
              <input type="number" name="from" class="form-control">
            </div>
            <div class="col-md-4">
              <label>To</label>
              <input type="number" name="to" class="form-control">
            </div>
            <div class="col-md-4">
              <label>Year Graduated</label>
              <input type="number" name="year_graduated" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editEducationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="editEducationForm">
        @csrf
        <input type="hidden" name="id" id="editEducationId">
        <div class="modal-header">
          <h5 class="modal-title">Edit Education</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-12">
              <label>Level of Education</label>
              <select name="level_of_education" id="editLevel" class="form-select" required>
                <option value="Elementary">Elementary</option>
                <option value="High School">High School</option>
                <option value="College">College</option>
                <option value="Graduate">Graduate</option>
              </select>
            </div>
            </div>
            <div class="row mb-3">
            <div class="col-md-12">
              <label>Name of School</label>
              <input type="text" name="school_name" id="editSchool" class="form-control" required>
            </div>
            </div>
          <div class="row mb-3">
            <div class="col-md-12">
              <label>Degree / Course</label>
              <input type="text" name="degree_course" id="editDegree" class="form-control">
            </div>
            </div>
            <div class="row mb-3">
            <div class="col-md-4">
              <label>From</label>
              <input type="number" name="from" id="editFrom" class="form-control">
            </div>
            <div class="col-md-4">
              <label>To</label>
              <input type="number" name="to" id="editTo" class="form-control">
            </div>
            <div class="col-md-4">
              <label>Year Graduated</label>
              <input type="number" name="year_graduated" id="editYear" class="form-control">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label>Highest Level Earned</label>
              <input type="text" name="highest_level_earned" id="editLevelEarned" class="form-control">
            </div>
            <div class="col-md-6">
              <label>Scholarship / Honors</label>
              <input type="text" name="scholarship_honors" id="editHonors" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Confirm Delete -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this education record?
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
  // Open Add Modal
  $('#openEducationModalBtn').click(function() {
    $('#educationForm')[0].reset();
    const modal = new bootstrap.Modal(document.getElementById('educationModal'));
    modal.show();
  });

  // Save Education
  $('#educationForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: '{{ route("profile.education.save") }}',
      method: 'POST',
      data: $(this).serialize(),
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: function(response) {
        toastr.success('Education added successfully!');
        bootstrap.Modal.getInstance(document.getElementById('educationModal')).hide();
        setTimeout(() => location.reload(), 500);
      },
      error: function() { toastr.error('Failed to add education.'); }
    });
  });

  // Edit Modal Fill
    $(document).on('click', '.edit-education-btn', function() {
    $('#editEducationId').val($(this).data('id'));
    $('#editLevel').val($(this).data('level'));
    $('#editSchool').val($(this).data('school'));
    $('#editDegree').val($(this).data('degree'));
    $('#editFrom').val($(this).data('from'));
    $('#editTo').val($(this).data('to'));
    $('#editLevelEarned').val($(this).data('levelEarned'));
    $('#editYear').val($(this).data('year'));
    $('#editHonors').val($(this).data('honors'));
    new bootstrap.Modal(document.getElementById('editEducationModal')).show();
    });

    $('#editEducationForm').submit(function(e) {
    e.preventDefault();
    const id = $('#editEducationId').val();

    $.ajax({
        url: `/profile/education/${id}/update`,
        method: 'POST',
        data: $(this).serialize() + '&_method=PUT', // 👈 add _method override
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
        toastr.success('Education updated successfully!');
        bootstrap.Modal.getInstance(document.getElementById('editEducationModal')).hide();
        setTimeout(() => location.reload(), 500);
        },
        error: function(xhr) {
        console.log(xhr.status);
        console.log(xhr.responseText);
        toastr.error('Failed to update education.');
        }
    });
    });


        // Delete Modal
        let deleteId = null;
        $(document).on('click', '.delete-education-btn', function() {
            deleteId = $(this).data('id');
            new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
        });

        $('#confirmDeleteBtn').click(function() {
        if (!deleteId) return;

        $.ajax({
            url: `/profile/education/delete/${deleteId}`,
            method: 'POST', // still POST
            data: { _method: 'DELETE' }, // 👈 tell Laravel to treat this as DELETE
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
            toastr.success('Education deleted successfully!');
            bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
            setTimeout(() => location.reload(), 500);
            },
            error: function(xhr) {
            console.log(xhr.responseText);
            toastr.error('Failed to delete education.');
            }
        });
        });

  // DataTable Init
  jQuery(function($) {
    $('#educationTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>
@endpush
