<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal{{ $applicant->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('applicants.updateStatus', $applicant->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Update Status for {{ $applicant->first_name }} {{ $applicant->last_name }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <!-- Status Dropdown -->
          <div class="mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select status-select" data-id="{{ $applicant->id }}" required>
              <option value="">-- Select Status --</option>
              @foreach(['Technical Examination','Deliberation','Hired','Rejected','Submission of Requirements','On-Boarding','DMS','MS','IQT','Appointment Letter'] as $status)
              <option value="{{ $status }}" {{ $applicant->status === $status ? 'selected' : '' }}>
                {{ $status }}
              </option>
              @endforeach
            </select>
          </div>

          <!-- Remarks -->
          <div class="mb-3 remarks-group">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="2">{{ $applicant->remarks }}</textarea>
          </div>

          <!-- Hired Fields (Division, Section, Date of Assumption) -->
          <div class="hired-fields d-none">
            <div class="mb-3">
              <label class="form-label">Division</label>
              <select name="division_id" class="form-select division-select" data-applicant="{{ $applicant->id }}">
                <option value="">-- Select Division --</option>
                @foreach ($divisions as $division)
                <option value="{{ $division->id }}" {{ $applicant->division_id == $division->id ? 'selected' : '' }}>
                  {{ $division->name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Section</label>
              <select name="section_id" class="form-select section-select-{{ $applicant->id }}" data-current="{{ $applicant->section_id ?? '' }}">
                <option value="">-- Select Section --</option>
                @if($applicant->section)
                <option value="{{ $applicant->section->id }}" selected>{{ $applicant->section->name }}</option>
                @endif
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Date of Assumption</label>
              <input type="date" name="date_of_assumption" class="form-control" value="{{ $applicant->date_of_assumption ?? '' }}">
            </div>
          </div>

          <!-- Appointment Letter File Upload -->
          <div class="mb-3 appointment-letter-group d-none">
            <label class="form-label">Upload Appointment Letter</label>
            <input type="file" name="appointment_letter" class="form-control" accept=".pdf,.doc,.docx">
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  $(document).ready(function() {

    // Function to load sections via AJAX
    function loadSections(applicantId, divisionId, selectedSection = null) {
      const sectionSelect = $(`.section-select-${applicantId}`);
      sectionSelect.html('<option>Loading...</option>');

      if (!divisionId) {
        sectionSelect.html('<option value="">-- Select Section --</option>');
        return;
      }

      $.ajax({
        url: '{{ route("employee.sections") }}',
        type: 'GET',
        data: {
          division_id: divisionId
        },
        success: function(data) {
          sectionSelect.html('<option value="">-- Select Section --</option>');
          if (data.length === 0) {
            sectionSelect.append('<option value="">No sections available</option>');
          } else {
            $.each(data, function(index, section) {
              const selected = selectedSection == section.id ? 'selected' : '';
              sectionSelect.append(`<option value="${section.id}" ${selected}>${section.name}</option>`);
            });
          }
        },
        error: function() {
          sectionSelect.html('<option value="">Error loading sections</option>');
        }
      });
    }

    // Show/hide Hired fields and Appointment Letter upload based on status
    $('.status-select').each(function() {
      const applicantId = $(this).data('id');
      const hiredFields = $(`#updateStatusModal${applicantId} .hired-fields`);
      const appointmentGroup = $(`#updateStatusModal${applicantId} .appointment-letter-group`);

      function toggleFields() {
        const status = $(`#updateStatusModal${applicantId} .status-select`).val();
        // Hired fields
        if (status === 'Hired') {
          hiredFields.removeClass('d-none');
        } else {
          hiredFields.addClass('d-none');
        }
        // Appointment Letter
        if (status === 'Appointment Letter') {
          appointmentGroup.removeClass('d-none');
        } else {
          appointmentGroup.addClass('d-none');
        }
      }

      toggleFields();
      $(this).on('change', toggleFields);
    });

    // Preload sections for each division select
    $('.division-select').each(function() {
      const applicantId = $(this).data('applicant');
      const selectedSection = $(`.section-select-${applicantId}`).data('current');
      const divisionId = $(this).val();

      if (divisionId) {
        loadSections(applicantId, divisionId, selectedSection);
      }

      $(this).on('change', function() {
        loadSections(applicantId, $(this).val());
      });
    });

  });
</script>
@endpush
