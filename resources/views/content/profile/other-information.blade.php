@extends('layouts/contentNavbarLayout')

@section('title', 'Other Information')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Toastr --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<div class="card shadow-sm p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 style="color: #1d4bb2;">Other Information</h4>
  </div>

  <form id="otherInformationForm" method="POST" action="{{ route('profile.other-information.store') }}">
    @csrf

    {{-- Question 34 --}}
    <div class="mb-4">
      <label class="form-label fw-bold">
        Are you related by consanguinity or affinity to the appointing or recommending authority, or to the 
        chief of bureau or office or to the person who has immediate supervision over you in the Office, 
        Bureau or Department where you will be appointed,
      </label>

      <div class="mb-2">
        <label class="form-label fw-bold">A. within the third degree?</label><br>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="related_within_third_degree" value="Yes"
                 data-target="#related_within_third_degree_details"
                 {{ old('related_within_third_degree', $other->related_within_third_degree ?? '') == 'Yes' ? 'checked' : '' }}>
          <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="related_within_third_degree" value="No"
                 data-target="#related_within_third_degree_details"
                 {{ old('related_within_third_degree', $other->related_within_third_degree ?? 'No') == 'No' ? 'checked' : '' }}>
          <label class="form-check-label">No</label>
        </div>
        <textarea id="related_within_third_degree_details" class="form-control mt-2"
                  name="related_within_third_degree_details" placeholder="If YES, give details"
                  style="display:none;">{{ $other->related_within_third_degree_details ?? '' }}</textarea>
      </div>

      <div>
        <label class="form-label fw-bold">B. Within the fourth degree (for Local Government Unit - Career Employees)?</label><br>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="related_within_fourth_degree" value="Yes"
                 data-target="#related_within_fourth_degree_details"
                 {{ old('related_within_fourth_degree', $other->related_within_fourth_degree ?? '') == 'Yes' ? 'checked' : '' }}>
          <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="related_within_fourth_degree" value="No"
                 data-target="#related_within_fourth_degree_details"
                 {{ old('related_within_fourth_degree', $other->related_within_fourth_degree ?? 'No') == 'No' ? 'checked' : '' }}>
          <label class="form-check-label">No</label>
        </div>
        <textarea id="related_within_fourth_degree_details" class="form-control mt-2"
                  name="related_within_fourth_degree_details" placeholder="If YES, give details"
                  style="display:none;">{{ $other->related_within_fourth_degree_details ?? '' }}</textarea>
      </div>
    </div>

    {{-- Question 35 --}}
    <div class="mb-4">
      <label class="form-label fw-bold">A. Have you ever been found guilty of any administrative offense?</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="found_guilty_admin_offense" value="Yes"
               data-target="#administrative_offense_details"
               {{ old('found_guilty_admin_offense', $other->found_guilty_admin_offense ?? '') == 'Yes' ? 'checked' : '' }}>
        <label class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="found_guilty_admin_offense" value="No"
               data-target="#administrative_offense_details"
               {{ old('found_guilty_admin_offense', $other->found_guilty_admin_offense ?? 'No') == 'No' ? 'checked' : '' }}>
        <label class="form-check-label">No</label>
      </div>
      <textarea id="administrative_offense_details" class="form-control mt-2"
                name="administrative_offense_details" placeholder="If YES, give details"
                style="display:none;">{{ $other->administrative_offense_details ?? '' }}</textarea>

      <br><label class="form-label mt-2 fw-bold">B. Have you been criminally charged before any court?</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="criminally_charged" value="Yes"
               data-target=".criminal_fields"
               {{ old('criminally_charged', $other->criminally_charged ?? '') == 'Yes' ? 'checked' : '' }}>
        <label class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="criminally_charged" value="No"
               data-target=".criminal_fields"
               {{ old('criminally_charged', $other->criminally_charged ?? 'No') == 'No' ? 'checked' : '' }}>
        <label class="form-check-label">No</label>
      </div>
      <div class="criminal_fields" style="display:none;">
        <label for="criminal_date_filed fw-bold">Date Filed</label>
        <input type="date" class="form-control mt-2" id="criminal_date_filed" name="criminal_date_filed"
               value="{{ $other->criminal_date_filed ?? '' }}">
        <textarea class="form-control mt-2" name="criminal_status" placeholder="Status">{{ $other->criminal_status ?? '' }}</textarea>
      </div>
    </div>

    {{-- Question 36 --}}
    <div class="mb-4">
      <label class="form-label fw-bold">Have you ever been convicted of any crime or violation of any law?</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="convicted_of_crime" value="Yes"
               data-target="#crime_details"
               {{ old('convicted_of_crime', $other->convicted_of_crime ?? '') == 'Yes' ? 'checked' : '' }}>
        <label class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="convicted_of_crime" value="No"
               data-target="#crime_details"
               {{ old('convicted_of_crime', $other->convicted_of_crime ?? 'No') == 'No' ? 'checked' : '' }}>
        <label class="form-check-label">No</label>
      </div>
      <textarea id="crime_details" class="form-control mt-2"
                name="crime_details" placeholder="If YES, give details"
                style="display:none;">{{ $other->crime_details ?? '' }}</textarea>
    </div>

    {{-- Question 37 --}}
    <div class="mb-4">
      <label class="form-label fw-bold">Have you ever been separated from service?</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="separated_from_service" value="Yes"
               data-target="#service_separation_details"
               {{ old('separated_from_service', $other->separated_from_service ?? '') == 'Yes' ? 'checked' : '' }}>
        <label class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="separated_from_service" value="No"
               data-target="#service_separation_details"
               {{ old('separated_from_service', $other->separated_from_service ?? 'No') == 'No' ? 'checked' : '' }}>
        <label class="form-check-label">No</label>
      </div>
      <textarea id="service_separation_details" class="form-control mt-2"
                name="service_separation_details" placeholder="If YES, give details (resignation, dismissal, etc.)"
                style="display:none;">{{ $other->service_separation_details ?? '' }}</textarea>
    </div>

    {{-- Question 38 --}}
    <div class="mb-4">
      <label class="form-label fw-bold">A. Have you ever been a candidate in a national or local election?</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="candidate_in_election" value="Yes"
               data-target="#candidate_in_election_details"
               {{ old('candidate_in_election', $other->candidate_in_election ?? '') == 'Yes' ? 'checked' : '' }}>
        <label class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="candidate_in_election" value="No"
               data-target="#candidate_in_election_details"
               {{ old('candidate_in_election', $other->candidate_in_election ?? 'No') == 'No' ? 'checked' : '' }}>
        <label class="form-check-label">No</label>
      </div>
      <textarea id="candidate_in_election_details" class="form-control mt-2"
                name="candidate_in_election_details" placeholder="If YES, give details"
                style="display:none;">{{ $other->candidate_in_election_details ?? '' }}</textarea>

      <br><label class="form-label mt-2 fw-bold">B. Have you resigned from government service during the 3-month period before the election?</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="resigned_before_election" value="Yes"
               data-target="#resigned_before_election_details"
               {{ old('resigned_before_election', $other->resigned_before_election ?? '') == 'Yes' ? 'checked' : '' }}>
        <label class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="resigned_before_election" value="No"
               data-target="#resigned_before_election_details"
               {{ old('resigned_before_election', $other->resigned_before_election ?? 'No') == 'No' ? 'checked' : '' }}>
        <label class="form-check-label">No</label>
      </div>
      <textarea id="resigned_before_election_details" class="form-control mt-2"
                name="resigned_before_election_details" placeholder="If YES, give details"
                style="display:none;">{{ $other->resigned_before_election_details ?? '' }}</textarea>
    </div>

    {{-- Question 39 --}}
    <div class="mb-4">
      <label class="form-label fw-bold">Have you acquired the status of an immigrant or permanent resident of another country?</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="immigrant_status" value="Yes"
               data-target="#immigrant_country"
               {{ old('immigrant_status', $other->immigrant_status ?? '') == 'Yes' ? 'checked' : '' }}>
        <label class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="immigrant_status" value="No"
               data-target="#immigrant_country"
               {{ old('immigrant_status', $other->immigrant_status ?? 'No') == 'No' ? 'checked' : '' }}>
        <label class="form-check-label">No</label>
      </div>
      <input id="immigrant_country" type="text" class="form-control mt-2"
             name="immigrant_country" placeholder="If YES, give details (country)"
             value="{{ $other->immigrant_country ?? '' }}" style="display:none;">
    </div>

    {{-- Question 40 --}}
    <div class="mb-4">
      <label class="form-label fw-bold">
        Pursuant to (a) Indigenous People's Act, (b) Magna Carta for Disabled Persons, and (c) Solo Parents Welfare Act
      </label>

      <div class="mb-2">
        <label class="form-label fw-bold">Are you a member of any indigenous group?</label><br>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="member_of_indigenous_group" value="Yes"
                 data-target="#indigenous_group_details"
                 {{ old('member_of_indigenous_group', $other->member_of_indigenous_group ?? '') == 'Yes' ? 'checked' : '' }}>
          <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="member_of_indigenous_group" value="No"
                 data-target="#indigenous_group_details"
                 {{ old('member_of_indigenous_group', $other->member_of_indigenous_group ?? 'No') == 'No' ? 'checked' : '' }}>
          <label class="form-check-label">No</label>
        </div>
        <input id="indigenous_group_details" type="text" class="form-control mt-2"
               name="indigenous_group_details" placeholder="If YES, please specify"
               value="{{ $other->indigenous_group_details ?? '' }}" style="display:none;">
      </div>

      <div class="mb-2">
        <label class="form-label fw-bold">Are you a person with a disability?</label><br>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="person_with_disability" value="Yes"
                 data-target="#disability_details"
                 {{ old('person_with_disability', $other->person_with_disability ?? '') == 'Yes' ? 'checked' : '' }}>
          <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="person_with_disability" value="No"
                 data-target="#disability_details"
                 {{ old('person_with_disability', $other->person_with_disability ?? 'No') == 'No' ? 'checked' : '' }}>
          <label class="form-check-label">No</label>
        </div>
        <input id="disability_details" type="text" class="form-control mt-2"
               name="disability_details" placeholder="If YES, please specify ID No."
               value="{{ $other->disability_details ?? '' }}" style="display:none;">
      </div>
    
      <div>
        <label class="form-label fw-bold">Are you a solo parent?</label><br>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="solo_parent" value="Yes"
                 data-target="#solo_parent_details"
                 {{ old('solo_parent', $other->solo_parent ?? '') == 'Yes' ? 'checked' : '' }}>
          <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="solo_parent" value="No"
                 data-target="#solo_parent_details"
                 {{ old('solo_parent', $other->solo_parent ?? 'No') == 'No' ? 'checked' : '' }}>
          <label class="form-check-label">No</label>
        </div>
        <input id="solo_parent_details" type="text" class="form-control mt-2"
               name="solo_parent_details" placeholder="If YES, please specify ID No."
               value="{{ $other->solo_parent_details ?? '' }}" style="display:none;">
      </div>
    </div>

    <div class="text-end mt-4">
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
  </form>
</div>

{{-- jQuery & Toastr --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@push('scripts')
<script>
$(document).ready(function () {

  // 🔹 Function to toggle show/hide fields
  function toggleFields() {
    $('input[type=radio][data-target]').each(function () {
      const target = $(this).data('target');
      const val = $(`input[name="${$(this).attr('name')}"]:checked`).val();

      if (val === 'Yes') {
        $(target).show();
      } else {
        $(target).hide();
      }
    });
  }

  // Run once on load
  toggleFields();

  // Change handler
  $('input[type=radio][data-target]').on('change', function () {
    toggleFields();
  });

  // AJAX submission
  $('#otherInformationForm').on('submit', function (e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.ajax({
      url: "{{ route('profile.other-information.store') }}",
      type: "POST",
      data: formData,
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: function (response) {
        toastr.success(response.message || 'Other Information saved successfully!');
        setTimeout(() => location.reload(), 1500);
      },
      error: function (xhr) {
        let message = 'Failed to save information.';
        if (xhr.status === 422 && xhr.responseJSON.errors) {
          message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
          message = xhr.responseJSON.message;
        }
        toastr.error(message);
      }
    });
  });
});
</script>
@endpush
@endsection
