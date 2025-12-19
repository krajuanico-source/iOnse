@extends('layouts/contentNavbarLayout')

@section('title', 'CPR Requests')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<div class="card p-4">
  <div class="d-flex justify-content-between mb-3">
    <h4 class="fw-bold">CPR Authentic Copy Requests</h4>
  </div>

  <table id="cprRequestTable" class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Requested By</th>
        <th>Selections</th>
        <th>Status</th>
        <th>Requested At</th>
        <th width="200">Action</th>
      </tr>
    </thead>

    <tbody>
      @foreach($requests as $req)
      <tr>
        <td>{{ $req->id }}</td>
        <td>{{ $req->user->first_name }} {{ $req->user->last_name }}</td>

        <!-- Selections -->
        <td>
          @foreach($req->selections as $sel)
          CPR ID: {{ $sel['cpr_id'] ?? 'N/A' }} | Rating: {{ $sel['rating'] ?? 'N/A' }}<br>
          @endforeach
        </td>

        <!-- Status -->
        <td>
          <span class="badge
            {{ $req->status === 'Approved' ? 'bg-success' : ($req->status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
            {{ $req->status }}
          </span>
        </td>

        <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>

        <!-- Action: Update Status -->
        <td class="d-flex gap-1">
          <!-- Update Status -->
          <form action="{{ route('forms.cprrequest.updateStatus', $req) }}" method="POST" class="d-flex gap-1">
            @csrf
            <select name="status" class="form-select form-select-sm">
              <option value="Pending" @selected($req->status === 'Pending')>Pending</option>
              <option value="Approved" @selected($req->status === 'Approved')>Approved</option>
              <option value="Rejected" @selected($req->status === 'Rejected')>Rejected</option>
            </select>
            <button class="btn btn-sm btn-primary">Update</button>
          </form>

          <!-- Signature Button -->
          @if($req->status === 'Approved')
          <button
            type="button"
            class="btn btn-sm btn-outline-secondary"
            data-bs-toggle="modal"
            data-bs-target="#signatureTypeModal_{{ $req->id }}">
            Add Signature
          </button>
          @endif
        </td>

        <x-modals.signature-type-cpr :ref="$req->id" />
        <x-modals.digital-signature-cpr :ref="$req->id" />
        <x-modals.electronic-signature-cpr :ref="$req->id" />
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#cprRequestTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>
@endsection