@extends('layouts/contentNavbarLayout')

@section('title', 'View Request')

@section('content')
<div class="card p-4">

  <h4 class="mb-4">Request Details</h4>

  <table class="table table-bordered">

    <tr>
      <th style="width: 25%">Request Number</th>
      <td>{{ $requestForm->req_num }}</td>
    </tr>

    <tr>
      <th>Date Requested</th>
      <td>{{ $requestForm->req_date }}</td>
    </tr>

    <tr>
      <th>Type of Request</th>
      <td>{{ $requestForm->req_doc }}</td>
    </tr>

    <tr>
      <th>Additional Info</th>
      <td>
        @if ($requestForm->req_specify === 'Others (please specify)')
        {{ $requestForm->req_specify }} — <strong>{{ $requestForm->req_specify_other }}</strong>
        @else
        {{ $requestForm->req_specify ?? 'N/A' }}
        @endif
      </td>
    </tr>

    <tr>
      <th>Purpose</th>
      <td>{{ $requestForm->req_purpose }}</td>
    </tr>

    <tr>
      <th>Mode of Receipt</th>
      <td>{{ $requestForm->req_mode }}</td>
    </tr>

    <tr>
      <th>Status</th>
      <td>{{ $requestForm->req_status ?? 'Pending' }}</td>
    </tr>

    <tr>
      <th>Date Released</th>
      <td>{{ $requestForm->req_date_released ?? '—' }}</td>
    </tr>

    <tr>
      <th>In-Charge</th>
      <td>{{ $requestForm->req_incharge ?? '—' }}</td>
    </tr>

    <tr>
      <th>Date Received</th>
      <td>{{ $requestForm->req_date_recieved ?? '—' }}</td>
    </tr>

    <tr>
      <th>Released By</th>
      <td>{{ $requestForm->req_released_by ?? '—' }}</td>
    </tr>

    @if ($requestForm->scan_file)
    <tr>
      <th>Uploaded File</th>
      <td>
        <a href="{{ asset('storage/' . $requestForm->scan_file) }}" target="_blank" class="btn btn-sm btn-info">
          View File
        </a>
      </td>
    </tr>
    @endif

    <tr>
      <td colspan="2" class="text-center">

        <a href="{{ route('forms.request_forms.edit', $requestForm->req_num) }}"
          class="btn btn-warning px-4">
          Edit
        </a>

        <a href="{{ route('forms.request_forms.index') }}"
          class="btn btn-secondary px-4">
          Back
        </a>

      </td>
    </tr>

  </table>

</div>
@endsection
