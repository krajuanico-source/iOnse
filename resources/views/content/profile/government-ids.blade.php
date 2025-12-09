@extends('layouts/contentNavbarLayout')
@section('title', 'Government IDs')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="card-body">
    <h4 style="color: #1d4bb2;">Government Identification Cards</h4>

    <form id="govIdForm" class="row g-3 mt-3">
      @csrf
      <input type="hidden" name="id" value="{{ $governmentIds->first()?->id ?? '' }}">

      <div class="col-md-6">
        <label class="form-label">SSS ID</label>
        <input type="text" name="sss_id" class="form-control" value="{{ $governmentIds->first()?->sss_id ?? '' }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">GSIS ID</label>
        <input type="text" name="gsis_id" class="form-control" value="{{ $governmentIds->first()?->gsis_id ?? '' }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">PAG-IBIG ID</label>
        <input type="text" name="pagibig_id" class="form-control" value="{{ $governmentIds->first()?->pagibig_id ?? '' }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">PHILHEALTH ID</label>
        <input type="text" name="philhealth_id" class="form-control" value="{{ $governmentIds->first()?->philhealth_id ?? '' }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">TIN</label>
        <input type="text" name="tin" class="form-control" value="{{ $governmentIds->first()?->tin ?? '' }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">PHILSYS</label>
        <input type="text" name="philsys" class="form-control" value="{{ $governmentIds->first()?->philsys ?? '' }}">
      </div>

      <div class="col-12 card p-6">
        <div class="row g-3">
        <h5 style="color: #353434ff;">For PDS Purposes</h5>
          <div class="col-md-6">
            <label class="form-label">GOVERNMENT ISSUED ID</label>
            <input type="text" name="gov_issued_id" class="form-control" value="{{ $governmentIds->first()?->gov_issued_id ?? '' }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">ID / LICENSE / PASSPORT NO.</label>
            <input type="text" name="id_number" class="form-control" value="{{ $governmentIds->first()?->id_number ?? '' }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">DATE ISSUANCE</label>
            <input type="date" name="date_issuance" class="form-control" value="{{ $governmentIds->first()?->date_issuance ?? '' }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">PLACE ISSUANCE</label>
            <input type="text" name="place_issuance" class="form-control" value="{{ $governmentIds->first()?->place_issuance ?? '' }}">
          </div>

        </div>
      </div>

      <div class="col-12 mt-3 text-end">
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
      
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(function(){

  $('#govIdForm').submit(function(e){
    e.preventDefault();

    const id = $('input[name="id"]').val();
    const data = $(this).serialize() + '&_method=PUT';

    $.ajax({
      url: `/profile/government-ids/${id}`,
      method: 'POST',
      data: data,
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: () => toastr.success('Government ID updated successfully!'),
      error: () => toastr.error('Failed to update Government ID.')
    });
  });

});
</script>
@endpush
