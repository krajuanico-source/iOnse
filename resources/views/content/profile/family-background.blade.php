@extends('layouts/contentNavbarLayout')
@section('title', 'Family Background')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
 <!-- Family Background -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<div class="card shadow-sm">
  <div class="card-body">
    <form id="familyForm" method="POST" action="{{ route('profile.family-background.update') }}">
      @csrf

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">Family Background</h4>
    </div>
      <h5 class="mb-4 fw-bold">Spouse Information</h5>
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label fw-bold">Surname</label>
          <input type="text" name="spouse_surname" class="form-control" value="{{ $family->spouse_surname ?? '' }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">First Name</label>
          <input type="text" name="spouse_first_name" class="form-control" value="{{ $family->spouse_first_name ?? '' }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Middle Name</label>
          <input type="text" name="spouse_middle_name" class="form-control" value="{{ $family->spouse_middle_name ?? '' }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Extension Name</label>
          <select class="form-select" name="spouse_extension_name">
            <option value="">-- Extension --</option>
            @foreach(['JR','SR','II','III','IV'] as $ext)
              <option value="{{ $ext }}" {{ ($family->spouse_extension_name ?? '') == $ext ? 'selected' : '' }}>
                {{ $ext }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Occupation</label>
          <input type="text" name="spouse_occupation" class="form-control" value="{{ $family->spouse_occupation ?? '' }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Employer/Business Name</label>
          <input type="text" name="spouse_employer" class="form-control" value="{{ $family->spouse_employer ?? '' }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Employer/Business Address</label>
          <input type="text" name="spouse_employer_address" class="form-control" value="{{ $family->spouse_employer_address ?? '' }}">
        </div>
                <div class="col-md-3">
          <label class="form-label fw-bold">Telephone</label>
          <input type="text" name="spouse_employer_telephone" class="form-control" value="{{ $family->spouse_employer_telephone ?? '' }}">
        </div>
      </div>

      <hr class="my-4">
      <h5 class="mb-4 fw-bold">Father Information</h5>
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label fw-bold">Surname</label>
          <input type="text" name="father_surname" class="form-control" value="{{ $family->father_surname ?? '' }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">First Name</label>
          <input type="text" name="father_first_name" class="form-control" value="{{ $family->father_first_name ?? '' }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Middle Name</label>
          <input type="text" name="father_middle_name" class="form-control" value="{{ $family->father_middle_name ?? '' }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Extension Name</label>
          <select class="form-select" name="father_extension_name">
            <option value="">-- Extension --</option>
            @foreach(['JR','SR','II','III','IV'] as $ext)
              <option value="{{ $ext }}" {{ ($family->father_extension_name ?? '') == $ext ? 'selected' : '' }}>
                {{ $ext }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <hr class="my-4">
      <h5 class="mb-4 fw-bold">Mother Information</h5>
      <div class="col-md-12">
      <label class="form-label fw-bold">Mother's Maiden Name</label>
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label fw-bold">Surname</label>
          <input type="text" name="mother_surname" class="form-control" value="{{ $family->mother_surname ?? '' }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">First Name</label>
          <input type="text" name="mother_first_name" class="form-control" value="{{ $family->mother_first_name ?? '' }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Middle Name</label>
          <input type="text" name="mother_middle_name" class="form-control" value="{{ $family->mother_middle_name ?? '' }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Extension Name</label>
          <select class="form-select" name="mother_extension_name">
            <option value="">-- Extension --</option>
            @foreach(['JR','SR','II','III','IV'] as $ext)
              <option value="{{ $ext }}" {{ ($family->mother_extension_name ?? '') == $ext ? 'selected' : '' }}>
                {{ $ext }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

                <hr class="my-4">
                <h6 class="mb-4 fw-bold">Children Information</h6>

                <div id="children-wrapper">
                    @if(!empty($family->children))
                        @foreach($family->children as $index => $child)
                            <div class="col-10 child-row mb-3">
                                <div class="row g-3 align-items-center">
                                    <input type="hidden" name="children[{{ $index }}][id]" value="{{ $child->id }}">

                                    <div class="col-md-3">
                                        <input type="text" name="children[{{ $index }}][first_name]" class="form-control" placeholder="First Name" value="{{ $child->first_name }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="children[{{ $index }}][middle_name]" class="form-control" placeholder="Middle Name" value="{{ $child->middle_name }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="children[{{ $index }}][last_name]" class="form-control" placeholder="Last Name" value="{{ $child->last_name }}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="children[{{ $index }}][birthday]" class="form-control" value="{{ $child->birthday }}">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger remove-child">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <input type="hidden" name="deleted_children" id="deleted-children" value="">

                <div class="mb-3">
                    <button type="button" class="btn btn-success" id="add-child">Add Child</button>


                                          <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                      </div>

                    </form>
                  </div>
                </div>
                </div>


                @push('scripts')
                <script>
                $(document).ready(function() {
                    let newIndex = 0;

                    // Add new child row
                    $('#add-child').click(function() {
                        let row = `
                            <div class="col-10 child-row mb-3">
                              <div class="row g-3 align-items-center">
                                <div class="col-md-3">
                                  <input type="text" name="children[new_${newIndex}][first_name]" class="form-control" placeholder="First Name">
                                </div>
                                <div class="col-md-3">
                                  <input type="text" name="children[new_${newIndex}][middle_name]" class="form-control" placeholder="Middle Name">
                                </div>
                                <div class="col-md-3">
                                  <input type="text" name="children[new_${newIndex}][last_name]" class="form-control" placeholder="Last Name">
                                </div>
                                <div class="col-md-2">
                                  <input type="date" name="children[new_${newIndex}][birthday]" class="form-control">
                                </div>
                                <div class="col-md-1">
                                  <button type="button" class="btn btn-danger remove-child">Remove</button>
                                </div>
                              </div>
                            </div>
                        `;
                        $('#children-wrapper').append(row);
                        newIndex++;
                    });
                    @push('scripts')
                    <script>
                    $(document).ready(function () {
                      $('#familyForm').on('submit', function (e) {
                        e.preventDefault(); 

                        // Optional: confirmation prompt before saving
                        if (!confirm('Are you sure you want to save these changes?')) {
                          return; // cancel if user presses "Cancel"
                        }

                        let formData = new FormData(this);

                        $.ajax({
                          url: '{{ route("profile.family-background.update") }}',
                          type: 'POST',
                          data: formData,
                          contentType: false,
                          processData: false,
                          headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                          },
                          success: function (response) {
                            toastr.success('Family Background updated successfully!');
                            setTimeout(() => location.reload(), 500);
                          },
                          error: function (xhr) {
                            toastr.error('Failed to update Family Background.');
                            console.log(xhr.responseText);
                          }
                        });
                      });
                    });
                    </script>
                    @endpush


                    // Remove child row
                    $('#children-wrapper').on('click', '.remove-child', function() {
                        const row = $(this).closest('.child-row');
                        const childId = row.find('input[name*="[id]"]').val();
                        if (childId) {
                            let deleted = $('#deleted-children');
                            let current = deleted.val();
                            deleted.val(current ? current + ',' + childId : childId);
                        }
                        row.remove();
                    });
                });
                </script>
                @endpush






                @endsection
