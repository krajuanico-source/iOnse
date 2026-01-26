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

  <table id="cprRequestTable" class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Requested By</th>
        <th>Selections</th>
        <th>Status</th>
        <th>Requested At</th>
        <th width="420">Action</th>
      </tr>
    </thead>

    <tbody>
      @foreach($requests as $req)
      <tr>
        <td>{{ $req->id }}</td>

        <td>
          {{ $req->user->first_name }} {{ $req->user->last_name }}
        </td>

        {{-- SELECTIONS (FIXED) --}}
        <td>
          @php
          $selections = [];

          foreach ($req->cprs as $cpr) {
              $selections[] = [
                  'cpr_id' => $cpr->id,
                  'rating' => $cpr->pivot->rating,
              ];
          }
          $ids = array_map(fn($s) => $s['cpr_id'] ?? null, $selections);
          $ratings = \App\Models\Cpr::whereIn('id', array_filter($ids))->get();
          @endphp

      @forelse($selections as $selection)
          CPR ID: {{ $selection['cpr_id'] ?? 'N/A' }} | Rating: {{ $selection['rating'] ?? 'N/A' }}<br>
      @empty
          <span class="text-muted">No selections</span>
      @endforelse
        </td>

        {{-- STATUS --}}
        <td>
          <span @class([
            'badge',
            'bg-warning text-dark' => $req->status === 'Pending',
            'bg-info text-dark'    => $req->status === 'For Signature',
            'bg-success'           => $req->status === 'Approved',
            'bg-danger'            => $req->status === 'Rejected',
          ])>
            {{ $req->status }}
          </span>
        </td>

        <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>

        {{-- ACTIONS --}}
        <td class="d-flex flex-wrap gap-1">

          {{-- UPDATE STATUS --}}
          <form action="{{ route('forms.cprrequest.updateStatus', $req) }}"
                method="POST"
                class="d-flex gap-1">
            @csrf

            <select name="status" class="form-select form-select-sm" required>
              <option value="Pending" @selected($req->status === 'Pending')>
                Pending
              </option>

              <option value="For Signature" @selected($req->status === 'For Signature')>
                For Signature
              </option>

              <option value="Rejected" @selected($req->status === 'Rejected')>
                Rejected
              </option>
            </select>

            <button class="btn btn-sm btn-primary">
              Update
            </button>
          </form>

          {{-- SIGNATURE BUTTON (ONLY WHEN READY) --}}
          @if($req->status === 'For Signature')
            <button
              type="button"
              class="btn btn-sm btn-outline-secondary"
              data-bs-toggle="modal"
              data-bs-target="#signatureTypeModal_{{ $req->id }}">
              Add Signature
            </button>
          @endif

          {{-- DOWNLOAD SIGNED PDF --}}
          @if($req->status === 'Approved' && $req->signed_pdf_path)
            <a href="{{ route('forms.cprrequest.download', $req->id) }}"
               class="btn btn-sm btn-success">
              Download Signed PDF
            </a>
          @endif

        </td>

        {{-- MODALS --}}
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
  $(document).ready(function () {
    $('#cprRequestTable').DataTable({
      paging: true,
      searching: true,
      info: true,
      ordering: true
    });
  });
</script>
@endsection
