@extends('layouts/contentNavbarLayout')

@section('title', 'New Request')

@section('content')
<div class="card p-4">

  <h4 class="mb-4">Submit New Request</h4>

  <form action="{{ route('forms.request_forms.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <table class="table table-bordered">
      <tr>
        <th style="width: 25%">Date Request</th>
        <td>
          <input type="date" name="req_date" class="form-control" required>
        </td>
      </tr>

      <tr>
        <th>Type of Request</th>
        <td>
          <select name="req_doc" class="form-select" required>
            <option value="">Select…</option>
            <option>Service Record</option>
            <option>Certificate of Employment</option>
            <option>Certificate of Leave without Pay</option>
            <option>Certificate of Leave Credits</option>
            <option>Certificate of Employment Separated</option>
            <option>Duly Accomplished Office Clearance Certificate Form</option>
            <option>Payslip</option>
          </select>
        </td>
      </tr>

      <tr>
        <th>Additional Info (if requested)</th>
        <td>
          <select name="req_specify" class="form-select">
            <option value="">Select…</option>
            <option>Salary / Cost Service</option>
            <option>Service / Contract Gaps (if any)</option>
            <option>Others (please specify)</option>
          </select>

          <!-- Optional text field appears if "Others" selected -->
          <input type="text" name="req_specify_other" id="otherInput"
            class="form-control mt-2 d-none"
            placeholder="Please specify…">
        </td>
      </tr>

      <tr>
        <th>Purpose</th>
        <td>
          <input type="text" name="req_purpose" class="form-control" required>
        </td>
      </tr>

      <tr>
        <th>Mode of Receipt</th>
        <td>
          <select name="req_mode" class="form-select" required>
            <option value="">Select…</option>
            <option>Email</option>
            <option>Pick Up</option>
          </select>
        </td>
      </tr>

      <tr>
        <td colspan="2" class="text-center">
          <button type="submit" class="btn btn-primary px-4">Apply</button>
        </td>
      </tr>

    </table>

  </form>

</div>

<script>
  const select = document.querySelector('select[name="req_specify"]');
  const otherInput = document.getElementById('otherInput');

  select.addEventListener('change', function() {
    if (this.value === 'Others (please specify)') {
      otherInput.classList.remove('d-none');
    } else {
      otherInput.classList.add('d-none');
      otherInput.value = '';
    }
  });
</script>

@endsection
