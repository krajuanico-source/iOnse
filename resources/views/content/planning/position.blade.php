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
              <th style="width:5%;">No.</th>
              <th style="width:20%;">Item Number</th>
              <th style="width:25%;">Position Name</th>
              <th style="width:10%;">Abbreviation</th>
              <th style="width:10%;">Status</th>
              <th style="width:10%;">Massive</th>
              <th style="width:10%;">Date of Publication</th>
              <th style="width:20%;">Action</th>
            </tr>
          </thead>
        <tbody>
          @foreach($positions as $index => $position)
          <tr data-id="{{ $position->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $position->item_no }}</td>
            <td>{{ $position->position_name }}</td>
            <td>{{ $position->abbreviation }}</td>
            <td>{{ ucfirst($position->status) }}</td>
            <td>
              @if($position->is_mass_hiring)
                YES/{{ $position->positions_count ?? 1 }}
              @else
                NO
              @endif
            </td>
            <td>
              {{ $position->date_of_publication?->format('M d, Y') ?? '-' }}
            </td>
            <td class="text-nowrap">
              <div class="d-inline-flex gap-1">
                <button class="btn btn-sm btn-primary edit-btn"
                      data-id="{{ $position->id }}"
                      data-item_no="{{ $position->item_no }}"
                      data-position_name="{{ $position->position_name }}"
                      data-abbreviation="{{ $position->abbreviation }}"
                      data-program="{{ $position->program }}"
                      data-created_at="{{ $position->created_at?->format('Y-m-d') }}"
                      data-parenthetical_title="{{ $position->parenthetical_title }}"
                      data-designation="{{ $position->designation }}"
                      data-special_order="{{ $position->special_order }}"
                      data-obsu="{{ $position->obsu }}"
                      data-fund_source="{{ $position->fund_source }}"
                      data-type_of_request="{{ $position->type_of_request }}"
                      data-employment_status_id="{{ $position->employment_status_id }}"
                      data-office_location_id="{{ $position->office_location_id }}"
                      data-position_level_id="{{ $position->position_level_id }}"
                      data-division_id="{{ $position->division_id }}"
                      data-section_id="{{ $position->section_id }}"
                      data-salary_tranche_id="{{ $position->salary_tranche_id }}"
                      data-salary_grade_id="{{ $position->salary_grade_id }}"
                      data-salary_step_id="{{ $position->salary_step_id }}"
                      data-monthly_rate="{{ $position->monthly_rate }}"
                      data-date_of_publication="{{ $position->date_of_publication?->format('Y-m-d') }}"
                  >Edit</button>
                <button class="btn btn-sm btn-danger delete-btn"
                    data-id="{{ $position->id }}">Delete</button>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Position Modal -->
<div class="modal fade" id="positionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="positionForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add New Position</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
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

<!-- Edit Position Modal -->
<div class="modal fade" id="editPositionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">
        Edit Position
        <span id="massiveLabel" class="badge bg-warning text-dark d-none">Massive Hiring</span>
      </h5>
      <form id="editPositionForm">
        @csrf
        <input type="hidden" name="id" id="editPositionId">
        <input type="hidden" id="editMassGroupId" name="mass_group_id">
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


<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"> 
    <div class="modal-content text-center">
      <div class="modal-body">
        Are you sure you want to delete this position?
      </div>
      <div class="modal-footer justify-content-center"> <!-- Center buttons -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>


@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



<script>
$(document).ready(function() {

  // Open Add Position modal
  $('#openModalBtn').click(function() {
    $('#positionForm')[0].reset();
    new bootstrap.Modal(document.getElementById('positionModal')).show();
  });

  // Edit Position AJAX
$(document).on('submit', '#editPositionForm', function(e) {
    e.preventDefault();

    let form = $(this);
    let id = $('#editPositionId').val();
    let submitBtn = form.find('button[type="submit"]');
    submitBtn.prop('disabled', true).text('Saving...');

    // Add _method for PUT
    let formData = form.serialize() + '&_method=PUT';

    $.ajax({
        url: `/planning/position/${id}`,
        type: 'POST', // Laravel will recognize PUT via _method
        data: formData,
        success: function(response) {
            toastr.success('Position updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('editPositionModal')).hide();

            // Update the DataTable row
            let row = $(`#positionTable tr[data-id="${id}"]`);

            // Update table columns
            row.find('td:eq(1)').text($('#edit_item_no').val());
            row.find('td:eq(2)').text($('#edit_position_name').val().toUpperCase());
            row.find('td:eq(3)').text($('#edit_abbreviation').val().toUpperCase());
            
            // Status (if you have a status input in your edit form)
            row.find('td:eq(4)').text($('#edit_status').val() ?? 'Active');
            
            // Massive hiring
            let massiveText = $('#is_mass_hiring_edit').is(':checked') 
                ? 'YES/' + ($('#editPositionsCount').val() || 1) 
                : 'NO';
            row.find('td:eq(5)').text(massiveText);

            // Date of publication
            let dateVal = $('#edit_date_of_publication').val();
            row.find('td:eq(6)').text(dateVal 
                ? new Date(dateVal).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) 
                : '-');
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorMsg = '';
                $.each(errors, function(key, val) {
                    errorMsg += val[0] + '<br>';
                });
                toastr.error(errorMsg);
            } else {
                toastr.error('An error occurred. Please try again.');
            }
        },
        complete: function() {
            submitBtn.prop('disabled', false).text('Save Changes');
        }
    });
});

  // Submit Add Position form
  $('#positionForm').submit(function(e) {
    e.preventDefault();

    let form = $(this);
    let submitBtn = form.find('button[type="submit"]');
    submitBtn.prop('disabled', true).text('Adding...');

    $.ajax({
      url: "{{ route('position.store') }}", // ← Blade route helper must render here
      type: 'POST',
      data: form.serialize(),
      success: function(response) {
        toastr.success('Position added successfully!');
        bootstrap.Modal.getInstance(document.getElementById('positionModal')).hide();
        setTimeout(() => location.reload(), 500);
      },
      error: function(xhr) {
        if (xhr.status === 422) {
          let errors = xhr.responseJSON.errors;
          let errorMsg = '';
          $.each(errors, function(key, val) {
            errorMsg += val[0] + '<br>';
          });
          toastr.error(errorMsg);
        } else {
          toastr.error('An error occurred. Please try again.');
        }
      },
      complete: function() {
        submitBtn.prop('disabled', false).text('Add');
      }
    });
  });

});
$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');

    $.get(`/planning/position/${id}`, function (data) {

        // Fill hidden fields
        $('#editPositionId').val(data.id);
        $('#editMassGroupId').val(data.mass_group_id || '');

        // Simple inputs
        $('#edit_item_no').val(data.item_no);
        $('#edit_program').val(data.program);
        $('#edit_created_at').val(data.created_at);
        $('#edit_position_name').val(data.position_name);
        $('#edit_abbreviation').val(data.abbreviation);
        $('#edit_parenthetical_title').val(data.parenthetical_title);
        $('#edit_designation').val(data.designation);
        $('#edit_special_order').val(data.special_order);
        $('#edit_obsu').val(data.obsu);
        $('#edit_fund_source').val(data.fund_source);
        $('#edit_date_of_publication').val(data.date_of_publication);

        // Select dropdowns
        $('#edit_office_location_id').val(data.office_location_id).trigger('change');
        $('#edit_division_id').val(data.division_id).trigger('change');
        $('#edit_position_level_id').val(data.position_level_id).trigger('change');
        $('#edit_employment_status_id').val(data.employment_status_id).trigger('change');
        $('#edit_type_of_request').val(data.type_of_request).trigger('change');

        // ===== DIVISION → SECTION =====
        if (data.division_id) {
            $.get(`/division/${data.division_id}/sections`, function (sections) {
                let options = '<option value="">Select Section</option>';
                sections.forEach(s => {
                    options += `<option value="${s.id}">${s.name}</option>`;
                });
                $('#edit_section_id').html(options).val(data.section_id).trigger('change');
            });
        } else {
            $('#edit_section_id').html('<option value="">Select Section</option>');
        }

        // ===== Salary Tranche → Grade → Step → Monthly Rate =====
        if (data.salary_tranche_id) {
            $('#edit_salary_tranche').val(data.salary_tranche_id).trigger('change');

            $.get(`/planning/get-salary-grades/${data.salary_tranche_id}`, function (grades) {
                let gradeOptions = '<option value="">Select Salary Grade</option>';
                grades.forEach(g => {
                    gradeOptions += `<option value="${g.id}">SG ${g.salary_grade}</option>`;
                });
                $('#edit_salary_grade_id').html(gradeOptions).val(data.salary_grade_id).trigger('change');

                if (data.salary_grade_id) {
                    $.get(`/planning/get-salary-steps/${data.salary_grade_id}`, function (steps) {
                        let stepOptions = '<option value="">Select Step</option>';
                        steps.forEach(s => {
                            stepOptions += `<option value="${s.id}" data-rate="${s.monthly_rate}">Step ${s.step}</option>`;
                        });
                        $('#edit_salary_step_id').html(stepOptions).val(data.salary_step_id).trigger('change');

                        $('#edit_monthly_rate').val(data.monthly_rate);
                    });
                }
            });
        } else {
            $('#edit_salary_grade_id, #edit_salary_step_id').html('<option value="">Select</option>');
            $('#edit_monthly_rate').val('');
        }

        // ===== Show Massive Hiring Indicator =====
        if (data.is_mass_hiring && data.mass_group_id) {
            $('#massiveLabel').removeClass('d-none');
            $('#positionsCountWrapperEdit').removeClass('d-none');
            
            // Count positions in this mass group
            $.get(`/planning/positions/count/${data.mass_group_id}`, function(countData){
                $('#editPositionsCount').val(countData.count);
            });
        } else {
            $('#massiveLabel').addClass('d-none');
            $('#positionsCountWrapperEdit').addClass('d-none');
        }

        // Show modal
        new bootstrap.Modal(document.getElementById('editPositionModal')).show();
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
        type: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            toastr.success('Deleted successfully!');
            bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: function(xhr) {
            console.log(xhr);
            toastr.error('Failed to delete the position.');
        }
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
$(document).ready(function () {

  // ===== DIVISION → SECTION =====
  $('#division_id').off('change').on('change', function () {
    const divisionId = $(this).val();
    const $section = $('#section_id');

    $section.html('<option value="">Select Section</option>');

    if (!divisionId) return;

    $.get(`/division/${divisionId}/sections`, function (sections) {
      let options = '<option value="">Select Section</option>';
      sections.forEach(s => {
        options += `<option value="${s.id}">${s.name}</option>`;
      });
      $section.html(options);
    });
  });


  // ===== TRANCHE → GRADE =====
  $('#salary_tranche').off('change').on('change', function () {

    const trancheId = $(this).val();

    const $grade = $('#salary_grade_id');
    const $step  = $('#salary_step_id');

    $grade.html('<option value="">Select Salary Grade</option>');
    $step.html('<option value="">Select Step</option>');
    $('#monthly_rate').val('');

    if (!trancheId) return;

    $.get(`/planning/get-salary-grades/${trancheId}`, function (grades) {
      let options = '<option value="">Select Salary Grade</option>';
      grades.forEach(g => {
        options += `<option value="${g.id}">SG ${g.salary_grade}</option>`;
      });
      $grade.html(options);
    });
  });


  // ===== GRADE → STEP =====
  $('#salary_grade_id').off('change').on('change', function () {

    const gradeId = $(this).val();
    const $step = $('#salary_step_id');

    $step.html('<option value="">Select Step</option>');
    $('#monthly_rate').val('');

    if (!gradeId) return;

        $.get(`/planning/get-salary-steps/${gradeId}`, function (steps) {
          let options = '<option value="">Select Step</option>';
          steps.forEach(s => {
            options += `
              <option value="${s.id}" data-rate="${s.monthly_rate}">
                Step ${s.step}
              </option>`;
          });
          $step.html(options);
        });
      });


  // ===== STEP → RATE =====
  $('#salary_step_id').off('change').on('change', function () {
    const rate = $(this).find(':selected').data('rate') || '';  
    $('#monthly_rate').val(rate);
  });

});

// Show/hide number of positions when massive hiring is checked
$('#is_mass_hiring').on('change', function() {
    if ($(this).is(':checked')) {
        $('#positionsCountWrapper').removeClass('d-none');
    } else {
        $('#positionsCountWrapper').addClass('d-none');
    }
});

</script>




@endpush