@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Request')

@section('content')
<div class="card p-4">

  <h4 class="mb-4">Edit Request</h4>

  <form action="{{ route('forms.request_forms.update', $requestForm->req_num) }}"
    method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <table class="table table-bordered">

      <tr>
        <th style="width: 25%">Date Request</th>
        <td>
          <input type="date" name="req_date"
            class="form-control"
            value="{{ $requestForm->req_date }}"
            required>
        </td>
      </tr>

      <tr>
        <th>Type of Request</th>
        <td>
          <select name="req_doc" class="form-select" required>
            <option value="">Select…</option>
            <option {{ $requestForm->req_doc == 'Service Record' ? 'selected' : '' }}>Service Record</option>
            <option {{ $requestForm->req_doc == 'Certificate of Employment' ? 'selected' : '' }}>Certificate of Employment</option>
            <option {{ $requestForm->req_doc == 'Certificate of Leave without Pay' ? 'selected' : '' }}>Certificate of Leave without Pay</option>
            <option {{ $requestForm->req_doc == 'Certificate of Leave Credits' ? 'selected' : '' }}>Certificate of Leave Credits</option>
            <option {{ $requestForm->req_doc == 'Certificate of Employment Separated' ? 'selected' : '' }}>Certificate of Employment Separated</option>
            <option {{ $requestForm->req_doc == 'Duly Accomplished Office Clearance Certificate Form' ? 'selected' : '' }}>Duly Accomplished Office Clearance Certificate Form</option>
            <option {{ $requestForm->req_doc == 'Payslip' ? 'selected' : '' }}>Payslip</option>
          </select>
        </td>
      </tr>

      <tr>
        <th>Additional Info (if requested)</th>
        <td>
          <select name="req_specify" id="reqSpecSelect" class="form-select">
            <option value="">Select…</option>
            <option {{ $requestForm->req_specify == 'Salary / Cost Service' ? 'selected' : '' }}>Salary / Cost Service</option>
            <option {{ $requestForm->req_specify == 'Service / Contract Gaps (if any)' ? 'selected' : '' }}>Service / Contract Gaps (if any)</option>
            <option {{ $requestForm->req_specify == 'Others (please specify)' ? 'selected' : '' }}>Others (please specify)</option>
          </select>

          <!-- Optional text field for "Others" -->
          <input type="text"
            name="req_specify_other"
            id="otherInput"
            class="form-control mt-2 {{ $requestForm->req_specify == 'Others (please specify)' ? '' : 'd-none' }}"
            placeholder="Please specify…"
            value="{{ $requestForm->req_specify_other }}">
        </td>
      </tr>

      <tr>
        <th>Purpose</th>
        <td>
          <input type="text" name="req_purpose" class="form-control"
            value="{{ $requestForm->req_purpose }}" required>
        </td>
      </tr>

      <tr>
        <th>Mode of Receipt</th>
        <td>
          <select name="req_mode" class="form-select" required>
            <option value="">Select…</option>
            <option {{ $requestForm->req_mode == 'Email' ? 'selected' : '' }}>Email</option>
            <option {{ $requestForm->req_mode == 'Pick Up' ? 'selected' : '' }}>Pick Up</option>
          </select>
        </td>
      </tr>

      <tr>
        <th>Upload New File (optional)</th>
        <td>
          <input type="file" name="scan_file" class="form-control">

          @if ($requestForm->scan_file)
          <small class="text-muted">
            Current file:
            <a href="{{ asset('storage/' . $requestForm->scan_file) }}" target="_blank">
              View Uploaded File
            </a>
          </small>
          @endif
        </td>
      </tr>

      <tr>
        <td colspan="2" class="text-center">
          <button type="submit" class="btn btn-primary px-4">Update</button>

          <a href="{{ route('forms.request_forms.index') }}"
            class="btn btn-secondary px-4">
            Cancel
          </a>
        </td>
      </tr>

    </table>

  </form>

</div>

<script>
  const reqSpecSelect = document.getElementById('reqSpecSelect');
  const otherInput = document.getElementById('otherInput');

  reqSpecSelect.addEventListener('change', function() {
    if (this.value === 'Others (please specify)') {
      otherInput.classList.remove('d-none');
    } else {
      otherInput.classList.add('d-none');
      otherInput.value = '';
    }
  });
</script>

@endsection