<!-- Add Applicant Modal -->
<div class="modal fade" id="addApplicantModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="bi bi-person-plus me-2"></i> Add New Applicant
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('unfilled_positions.applicants.store', $item->id) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <!-- First Name -->
            <div class="col-md-6">
              <label class="form-label">First Name <span class="text-danger">*</span></label>
              <input type="text" name="first_name" class="form-control" required>
            </div>

            <!-- Middle Name -->
            <div class="col-md-6">
              <label class="form-label">Middle Name</label>
              <input type="text" name="middle_name" class="form-control">
            </div>

            <!-- Last Name -->
            <div class="col-md-6">
              <label class="form-label">Last Name <span class="text-danger">*</span></label>
              <input type="text" name="last_name" class="form-control" required>
            </div>

            <!-- Extension Name -->
            <div class="col-md-6">
              <label class="form-label">Extension Name</label>
              <select name="extension_name" class="form-select">
                <option value="">-- None --</option>
                <option value="Jr.">Jr.</option>
                <option value="Sr.">Sr.</option>
                <option value="II">II</option>
                <option value="III">III</option>
                <option value="IV">IV</option>
                <option value="V">V</option>
              </select>
            </div>

            <!-- Sex -->
            <div class="col-md-6">
              <label class="form-label">Sex <span class="text-danger">*</span></label>
              <select name="sex" class="form-select" required>
                <option value="">-- Select --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <!-- Date of Birth -->
            <div class="col-md-6">
              <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
              <input type="date" name="date_of_birth" class="form-control" required>
            </div>

            <!-- Date Applied -->
            <div class="col-md-6">
              <label class="form-label">Date Applied <span class="text-danger">*</span></label>
              <input type="date" name="date_applied" class="form-control" required>
            </div>

            <!-- Contact Number -->
            <div class="col-md-6">
              <label class="form-label">Contact Number</label>
              <input type="text" name="contact_number" class="form-control" placeholder="e.g. 09171234567">
            </div>

            <!-- Email -->
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="example@email.com">
            </div>

            <!-- Remarks -->
            <div class="col-md-12">
              <label class="form-label">Remarks</label>
              <textarea name="remarks" class="form-control" rows="2" placeholder="Enter any remarks (optional)"></textarea>
            </div>

            <!-- Status (Hidden, default to Pending) -->
            <input type="hidden" name="status" value="Pending">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-save me-1"></i> Save Applicant
          </button>
        </div>
      </form>
    </div>
  </div>
</div>