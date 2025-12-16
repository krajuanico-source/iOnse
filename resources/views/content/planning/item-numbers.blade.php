@extends('layouts/contentNavbarLayout')

@section('title', 'Item Numbers')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Item Numbers</h4>
      <button id="openModalBtn" class="btn btn-success">Add New Item Number</button>
    </div>

    <div class="table-responsive">
      <table id="itemNumberTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Item Number</th>
            <th>Position</th>
            <th>Fund Source</th>
            <th>Stature</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($itemNumbers as $index => $item)
          <tr data-id="{{ $item->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->item_number }}</td>
            <td>{{ $item->position->position_name ?? '-' }}</td>
            <td>
              {{ $item->fundSource ? ucfirst($item->fundSource->fund_source) : 'N/A' }}
            </td>

            <td>{{ ucfirst($item->stature) }}</td>
            <td class="text-nowrap">
              <div class="d-inline-flex gap-1">
                {{-- Edit button --}}
                <button class="btn btn-sm btn-primary edit-btn"
                  data-id="{{ $item->id }}"
                  data-item_number="{{ $item->item_number }}"
                  data-position_id="{{ $item->position_id }}"
                  data-status="{{ $item->status }}">
                  Edit
                </button>
                {{-- Delete button --}}
                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->id }}">
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
<div class="modal fade" id="itemNumberModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add New Item Number</h5>
      <form id="itemNumberForm">
        <div class="modal-body">
          <!-- Employment Status -->
          <div class="mb-3">
            <label>Employment Status</label>
            <select id="employmentStatusId" name="employment_status_id" class="form-control">
              <option value="">-- Select Employment Status --</option>
              @foreach($employmentStatuses as $status)
              <option value="{{ $status->id }}" data-abbreviation="{{ $status->abbreviation }}">
                {{ $status->name }}
              </option>
              @endforeach
            </select>

          </div>

          <!-- Position -->
          <div class="mb-3">
            <label>Position</label>
            <select name="position_id" class="form-control" required>
              <option value="">-- Select Position --</option>
              @foreach($positions as $pos)
              <option value="{{ $pos->id }}">{{ $pos->position_name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Salary Grade -->
          <div class="mb-3">
            <label>Salary Grade</label>
            <select name="salary_grade_id" class="form-control" required>
              <option value="">-- Select Salary Grade --</option>
              @foreach($salaryGrades as $grade)
              <option value="{{ $grade->id }}">{{ $grade->sg_num }}</option>
              @endforeach
            </select>
          </div>


          <!-- Fund Source -->
          <div class="mb-3">
            <label>Fund Source</label>
            <select name="fund_source_id" class="form-control" required>
              <option value="">-- Select Fund Source --</option>
              @foreach($fundSources as $source)
              <option value="{{ $source->id }}">{{ $source->fund_source }}</option>
              @endforeach
            </select>
          </div>


          <!-- Date of Posting -->
          <div class="mb-3">
            <label>Date of Posting</label>
            <input type="date" name="date_posting" class="form-control" value="{{ old('date_posting') ?? $item->date_posting ?? '' }}" required>
          </div>

          <!-- Date End of Submission -->
          <div class="mb-3">
            <label>Date End of Submission</label>
            <input type="date" name="date_end_submission" class="form-control" value="{{ old('date_end_submission') ?? $item->date_end_submission ?? '' }}" required>
          </div>


          <div class="mb-3">
            <label>Item Number</label>
            <input type="text" name="item_number" id="itemNumber" class="form-control" readonly required>
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
<div class="modal fade" id="editItemNumberModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title">Edit Item Number</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Form -->
      <form id="editItemNumberForm">
        <input type="hidden" name="id" id="editItemNumberId">

        <div class="modal-body">

          <!-- Employment Status -->
          <div class="mb-3">
            <label class="form-label">Employment Status</label>
            <select name="employment_status_id" id="editEmploymentStatusId" class="form-control" required>
              <option value="">-- Select Status --</option>
              @foreach($employmentStatuses as $status)
              <option value="{{ $status->id }}">{{ $status->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Position -->
          <div class="mb-3">
            <label class="form-label">Position</label>
            <select name="position_id" id="editPositionId" class="form-control" required>
              <option value="">-- Select Position --</option>
              @foreach($positions as $pos)
              <option value="{{ $pos->id }}">{{ $pos->position_name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Salary Grade -->
          <div class="mb-3">
            <label class="form-label">Salary Grade</label>
            <select name="salary_grade_id" id="editSalaryGradeId" class="form-control" required>
              <option value="">-- Select Salary Grade --</option>
              @foreach($salaryGrades as $sg)
              <option value="{{ $sg->id }}">{{ $sg->sg_num }}</option>
              @endforeach
            </select>
          </div>

          <!-- Fund Source -->
          <div class="mb-3">
            <label class="form-label">Fund Source</label>
            <select name="fund_source_id" id="editFundSourceId" class="form-control" required>
              <option value="">-- Select Fund Source --</option>
              @foreach($fundSources as $source)
              <option value="{{ $source->id }}"
                {{ $item->fund_source_id == $source->id ? 'selected' : '' }}>
                {{ $source->fund_source }}
              </option>
              @endforeach
            </select>
          </div>

          <!-- Date of Posting -->
          <div class="mb-3">
            <label class="form-label">Date of Posting</label>
            <input type="date" name="date_posting" id="editDatePosting" class="form-control"
              value="{{ $item->date_posting ? \Carbon\Carbon::parse($item->date_posting)->format('Y-m-d') : '' }}" required>
          </div>

          <!-- Date End of Submission -->
          <div class="mb-3">
            <label class="form-label">Date End of Submission</label>
            <input type="date" name="date_end_submission" id="editDateEndSubmission" class="form-control"
              value="{{ $item->date_end_submission ? \Carbon\Carbon::parse($item->date_end_submission)->format('Y-m-d') : '' }}" required>
          </div>

          <!-- Item Number -->
          <div class="mb-3">
            <label class="form-label">Item Number</label>
            <input type="text" name="item_number" id="editItemNumber" class="form-control" readonly required>
          </div>

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save Changes</button>
        </div>

      </form>
    </div>
  </div>
</div>


@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  $(document).ready(function() {
    function updateItemNumber() {
      let statusId = $('#employmentStatusId').val();
      let abbr = $('#employmentStatusId option:selected').data('abbreviation'); // ✅ use abbreviation
      let positionId = $('select[name="position_id"]').val();
      let positionText = $('select[name="position_id"] option:selected').text();

      if (!statusId) {
        $('#itemNumber').val('');
        return;
      }

      // Build preview first: ABBR-POSITION
      let preview = abbr ? abbr : '';
      if (positionId) {
        preview += '-' + positionText;
      }
      $('#itemNumber').val(preview);

      // If both are selected, request next number
      if (statusId && positionId) {
        $.get(`/planning/item-numbers/next/${statusId}/${positionId}`, function(data) {
          // Example backend returns { item_number: "FO XI-CASUAL-ADMINISTRATIVE AIDE IV-000002" }
          $('#itemNumber').val(data.item_number);
        });
      }
    }

    // Triggers
    $('#employmentStatusId').change(updateItemNumber);
    $('select[name="position_id"]').change(updateItemNumber);
  });
</script>



<script>
  $(function() {


    // ✅ Bootstrap Modals
    const addModal = new bootstrap.Modal('#itemNumberModal');
    const editModal = new bootstrap.Modal('#editItemNumberModal');

    // ✅ Reusable AJAX helper
    function ajaxRequest(url, method, data, onSuccess) {
      $.ajax({
        url,
        method,
        data,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
          toastr.success(res.message || "Success");
          if (onSuccess) onSuccess(res);
        },
        error: function(xhr) {
          if (xhr.responseJSON?.errors) {
            Object.values(xhr.responseJSON.errors).forEach(msg => toastr.error(msg));
          } else {
            toastr.error(xhr.responseJSON?.message || "An error occurred");
          }
        }
      });
    }

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#itemNumberForm').on('submit', function(e) {
      e.preventDefault();

      $.ajax({
        url: '/planning/item-numbers',
        method: 'POST',
        data: $(this).serialize(),
        success: function(res) {
          toastr.success(res.message);


          // Reset form
          $('#itemNumberForm')[0].reset();

          // Close modal
          addModal.hide();
          // 🔄 Refresh DataTable WITHOUT full page reload
          location.reload();
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            $.each(xhr.responseJSON.errors, function(field, messages) {
              toastr.error(messages[0]);
            });
          } else {
            toastr.error("Something went wrong.");
          }
          console.log(xhr.responseJSON);
        }
      });
    });



    // ✅ Open Add Modal
    $('#openModalBtn').on('click', function() {
      $('#itemNumberForm')[0].reset();
      addModal.show();
    });
  });
  $('#itemNumberTable').DataTable();
</script>
<script>
  $(document).on('click', '.edit-btn', function() {
    $('#editItemNumberId').val($(this).data('id'));
    $('#editItemNumber').val($(this).data('item_number'));
    $('#editEmploymentStatusId').val($(this).data('id'));
    $('#editPositionId').val($(this).data('position_id'));
    $('#editSalaryGradeId').val($(this).data('id'));
    $('#editStatus').val($(this).data('status'));

    $('#editItemNumberModal').modal('show');
  });


  $('#editEmploymentStatusId, #editPositionId').on('change', function() {
    let statusId = $('#editEmploymentStatusId').val();
    let positionId = $('#editPositionId').val();

    if (statusId && positionId) {
      $.get('/planning/item-numbers/next-number', {
        employment_status_id: statusId,
        position_id: positionId
      }, function(res) {
        $('#editItemNumber').val(res.item_number);
      });
    }
  });

  $('#editItemNumberForm').on('submit', function(e) {
    e.preventDefault();

    let id = $('#editItemNumberId').val();

    $.ajax({
      url: '/planning/item-numbers/' + id,
      method: 'PUT',
      data: $(this).serialize(),
      success: function(res) {
        toastr.success(res.message);
        $('#editItemNumberModal').modal('hide');
        $('#itemNumberTable').DataTable().ajax.reload(null, false);
      },
      error: function(xhr) {
        if (xhr.status === 422) {
          toastr.error("Validation failed.");
          console.log(xhr.responseJSON.errors);
        } else {
          toastr.error("Something went wrong.");
        }
      }
    });
  });
</script>

@endpush
