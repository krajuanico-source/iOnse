@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Request')

@section('content')
<div class="card p-4">

  <h4 class="mb-4">Edit Request</h4>

  <form action="{{ route('forms.request_forms.update', $requestForm->req_num) }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <table class="table table-bordered">

      <!-- Date Request -->
      <tr>
        <th style="width: 25%">Date Request</th>
        <td>
          <input type="date" name="req_date" class="form-control"
            value="{{ $requestForm->req_date }}" required>
        </td>
      </tr>

      <!-- Type of Request -->
      <tr>
        <th>Type of Request</th>
        <td>
          <select name="req_doc" class="form-select" required>
            <option value="">Select…</option>
            @php
            $docs = [
            'Service Record',
            'Certificate of Employment',
            'Certificate of Leave without Pay',
            'Certificate of Leave Credits',
            'Certificate of Employment Separated',
            'Duly Accomplished Office Clearance Certificate Form',
            'Payslip'
            ];
            @endphp
            @foreach ($docs as $doc)
            <option value="{{ $doc }}" {{ $requestForm->req_doc == $doc ? 'selected' : '' }}>
              {{ $doc }}
            </option>
            @endforeach
          </select>
        </td>
      </tr>

      <!-- Additional Info -->
      <tr>
        <th>Additional Info (if requested)</th>
        <td>
          @php
          $specifies = [
          'Salary / Cost Service',
          'Service / Contract Gaps (if any)',
          'Others (please specify)'
          ];
          @endphp
          <select name="req_specify" id="req_specify" class="form-select">
            <option value="">Select…</option>
            @foreach ($specifies as $spec)
            <option value="{{ $spec }}" {{ $requestForm->req_specify == $spec ? 'selected' : '' }}>
              {{ $spec }}
            </option>
            @endforeach
          </select>

          <input type="text" name="req_specify_other" id="req_specify_other"
            class="form-control mt-2 {{ $requestForm->req_specify == 'Others (please specify)' ? '' : 'd-none' }}"
            placeholder="Please specify…"
            value="{{ $requestForm->req_specify_other }}">
        </td>
      </tr>

      <!-- Purpose -->
      <tr>
        <th>Purpose</th>
        <td>
          <input type="text" name="req_purpose" class="form-control"
            value="{{ $requestForm->req_purpose }}" required>
        </td>
      </tr>

      <!-- Mode of Receipt -->
      <tr>
        <th>Mode of Receipt</th>
        <td>
          <select name="req_mode" class="form-select" required>
            <option value="">Select…</option>
            <option value="Email" {{ $requestForm->req_mode == 'Email' ? 'selected' : '' }}>Email</option>
            <option value="Pick Up" {{ $requestForm->req_mode == 'Pick Up' ? 'selected' : '' }}>Pick Up</option>
          </select>
        </td>
      </tr>

      <!-- Upload New File -->
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

      <!-- Submit Buttons -->
      <tr>
        <td colspan="2" class="text-center">
          <button type="submit" class="btn btn-primary px-4">Update</button>
          <a href="{{ route('forms.request_forms.index') }}" class="btn btn-secondary px-4">Cancel</a>
        </td>
      </tr>

    </table>

  </form>
</div>
@endsection

@section('scripts')
<script>
  const reqSelect = document.getElementById('req_specify');
  const reqOther = document.getElementById('req_specify_other');

  reqSelect.addEventListener('change', function() {
    reqOther.classList.toggle('d-none', this.value !== 'Others (please specify)');
    if (this.value !== 'Others (please specify)') reqOther.value = '';
  });
</script>
@endsection