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
      <button id="openModalBtn" class="btn btn-success">Add New Position</button>
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

                {{-- Edit button --}}
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


                {{-- Add Requirements button --}}
                <button class="btn btn-sm btn-warning requirements-btn"
                  data-id="{{ $position->id }}">
                  Requirements
                </button>

                {{-- Add Qualifications button --}}
                <button class="btn btn-sm btn-success add-qual-btn"
                  data-id="{{ $position->id }}">
                  Qualifications
                </button>

                {{-- Delete button --}}
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
    $('#edit_position_name').val(data.positionName); // camelCase
    $('#edit_abbreviation').val(data.abbreviation);
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

  // Open Requirements modal
  $(document).on('click', '.req-btn', function() {
    let id = $(this).data('id');
    let name = $(this).data('position-name');

    $('#reqPositionId').val(id);
    $('#reqPositionName').text(name);

    $.get(`/planning/position/${id}/requirements`, function(data) {
      let list = $('#requirementsList').empty();
      if (data.length === 0) list.append('<li class="list-group-item">No requirements yet.</li>');
      data.forEach(req => {
        list.append(`<li class="list-group-item d-flex justify-content-between align-items-center">
        ${req.requirement}
        <button class="btn btn-sm btn-danger delete-req" data-id="${req.id}">X</button>
      </li>`);
      });
    });

    new bootstrap.Modal(document.getElementById('reqModal')).show();
  });

  // Add Requirement
  $('#addRequirementBtn').click(function() {
    let id = $('#reqPositionId').val();
    let requirement = $('#requirement').val();
    $.post(`/planning/position/${id}/requirements/store`, {
      requirement: requirement,
      _token: $('meta[name="csrf-token"]').attr('content')
    }, function() {
      toastr.success('Requirement added!');
      $('.req-btn[data-id="' + id + '"]').click(); // reload list
    });
  });

  // Delete Requirement
  $(document).on('click', '.delete-req', function() {
    let id = $(this).data('id');
    $.post(`/planning/requirements/${id}/delete`, {
      _token: $('meta[name="csrf-token"]').attr('content')
    }, function() {
      toastr.success('Requirement deleted!');
      $('.req-btn[data-id="' + $('#reqPositionId').val() + '"]').click();
    });
  });

  // Open Qualifications modal
  $(document).on('click', '.qual-btn', function() {
    let id = $(this).data('id');
    let name = $(this).data('position-name');

    $('#qualPositionId').val(id);
    $('#qualPositionName').text(name);

    $.get(`/planning/position/${id}/qualifications`, function(data) {
      let list = $('#qualificationsList').empty();
      if (data.length === 0) list.append('<li class="list-group-item">No qualifications yet.</li>');
      data.forEach(q => {
        list.append(`<li class="list-group-item d-flex justify-content-between align-items-center">
        ${q.qualification}
        <button class="btn btn-sm btn-danger delete-qual" data-id="${q.id}">X</button>
      </li>`);
      });
    });

    new bootstrap.Modal(document.getElementById('qualModal')).show();
  });

  // Add Qualification
  $('#addQualificationBtn').click(function() {
    let id = $('#qualPositionId').val();
    let qualification = $('#qualification').val();
    alert(qualification);
    $.post(`/planning/position/${id}/qualifications/store`, {
      qualification: qualification,
      _token: $('meta[name="csrf-token"]').attr('content')
    }, function() {
      toastr.success('Qualification added!');
      $('.qual-btn[data-id="' + id + '"]').click(); // reload list
    });
  });

  // Delete Qualification
  $(document).on('click', '.delete-qual', function() {
    let id = $(this).data('id');
    $.post(`/planning/qualifications/${id}/delete`, {
      _token: $('meta[name="csrf-token"]').attr('content')
    }, function() {
      toastr.success('Qualification deleted!');
      $('.qual-btn[data-id="' + $('#qualPositionId').val() + '"]').click();
    });
  });


  $('#positionTable').DataTable();
</script>
<script>
  $(document).ready(function() {
    let currentPositionId = null;

    // Open modal and load requirements
    $(document).on('click', '.requirements-btn', function() {
      currentPositionId = $(this).data('id');
      $('#requirementsModal').modal('show');
      loadRequirements(currentPositionId);
    });

    // Load requirements
    function loadRequirements(positionId) {
      $.get(`/requirements/position/${positionId}`, function(data) {
        let rows = '';
        data.requirements.forEach(req => {
          rows += `
        <tr data-id="${req.id}">
          <td width="70%">
            <input type="text" class="form-control requirement-input" value="${req.requirement}">
          </td>
       <td>
      <button class="btn btn-sm btn-primary save-req" title="Update">
        <i class="fas fa-save">Update</i>
      </button>
      <button class="btn btn-sm btn-danger delete-req" title="Delete">
        <i class="bi bi-trash">Remove</i>
      </button>
    </td>
        </tr>
      `;
        });
        $('#requirementsList').html(rows);
      });
    }

    // Add requirement
    $('#addRequirementBtn').click(function() {
      const newReq = $('#newRequirement').val();
      if (!newReq) return alert("Enter a requirement");

      $.ajax({
        url: `/requirements/store/${currentPositionId}`,
        method: "POST",
        data: {
          requirement: [newReq],
          _token: "{{ csrf_token() }}"
        },
        success: function() {
          $('#newRequirement').val('');
          loadRequirements(currentPositionId);
        }
      });
    });

    // Save edited requirement
    $(document).on('click', '.save-req', function() {
      const row = $(this).closest('tr');
      const id = row.data('id');
      const value = row.find('.requirement-input').val();

      $.ajax({
        url: `/requirements/${id}`,
        method: "PUT",
        data: {
          requirement: value,
          _token: "{{ csrf_token() }}"
        },
        success: function() {
          loadRequirements(currentPositionId);
        }
      });
    });

    // Delete requirement
    $(document).on('click', '.delete-req', function() {
      const id = $(this).closest('tr').data('id');
      if (!confirm("Delete this requirement?")) return;

      $.ajax({
        url: `/requirements/${id}`,
        method: "DELETE",
        data: {
          _token: "{{ csrf_token() }}"
        },
        success: function() {
          loadRequirements(currentPositionId);
        }
      });
    });
  });
</script>
<script>
  $(document).ready(function() {
    let currentPositionId = null;

    // Open modal for qualifications
    $(document).on('click', '.add-qual-btn', function() {
      currentPositionId = $(this).data('id');
      $('#qualificationsModal').modal('show');
      loadQualifications(currentPositionId);
    });

    // Load qualifications
    function loadQualifications(positionId) {
      $.get(`/qualifications/position/${positionId}`, function(data) {
        let rows = '';
        data.qualifications.forEach(q => {
          rows += `
          <tr data-id="${q.id}">
            <td>
              <input type="text" class="form-control qualification-input" value="${q.title}">
            </td>
            <td>
              <button class="btn btn-sm btn-primary save-qual" title="Update">
                <i class="bi bi-save"></i>
              </button>
              <button class="btn btn-sm btn-danger delete-qual" title="Delete">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        `;
        });
        $('#qualificationsList').html(rows);
      });
    }

    // Add qualification
    $('#addQualificationBtn').click(function() {
      const newQ = $('#newQualification').val();
      if (!newQ) return alert("Enter a qualification");
      $.ajax({
        url: `/qualifications/store/${currentPositionId}`,
        method: "POST",
        data: {
          qualification: [newQ],
          _token: "{{ csrf_token() }}"
        },
        success: function() {
          $('#newQualification').val('');
          loadQualifications(currentPositionId);
        }
      });
    });

    // Save edited qualification
    $(document).on('click', '.save-qual', function() {
      const row = $(this).closest('tr');
      const id = row.data('id');
      const value = row.find('.qualification-input').val();

      $.ajax({
        url: `/qualifications/${id}`,
        method: "PUT",
        data: {
          qualification: value,
          _token: "{{ csrf_token() }}"
        },
        success: function() {
          loadQualifications(currentPositionId);
        }
      });
    });

    // Delete qualification
    $(document).on('click', '.delete-qual', function() {
      const id = $(this).closest('tr').data('id');
      if (!confirm("Delete this qualification?")) return;

      $.ajax({
        url: `/qualifications/${id}`,
        method: "DELETE",
        data: {
          _token: "{{ csrf_token() }}"
        },
        success: function() {
          loadQualifications(currentPositionId);
        }
      });
    });
  });
</script>
@endpush
