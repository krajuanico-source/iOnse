@extends('layouts/contentNavbarLayout')

@section('title', 'Basic Information')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>


<div class="card shadow-sm">
  <div class="card-body">
    <form id="employeeForm" method="POST" action="{{ route('profile.basic-info.update') }}" enctype="multipart/form-data">
      @csrf

      <div class="row">
        {{-- Left Column --}}
        <div class="col-md-9 border-end">
          <h5 class="mb-4 fw-bold">Basic Information</h5>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-bold">First Name</label>
              <input type="text" name="first_name" class="form-control" value="{{ $employee->first_name }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Middle Name</label>
              <input type="text" name="middle_name" class="form-control mt-1" value="{{ $employee->middle_name }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Last Name</label>
              <input type="text" name="last_name" class="form-control mt-1" value="{{ $employee->last_name }}">
            </div>
            <div class="col-md-4">
              <label>Extension Name</label>
              <select class="form-select" name="extension_name">
                <option value="">-- Extension name --</option>
                @foreach(['JR','SR','II','III','IV'] as $ext)
                <option value="{{ $ext }}"
                  {{ old('extension_name', $employee->extension_name) == $ext ? 'selected' : '' }}>
                  {{ $ext }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Employee ID</label>
              <input type="text" name="employee_id" class="form-control" value="{{ $employee->employee_id }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Username</label>
              <input type="text" name="username" class="form-control" value="{{ $employee->username }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Leave blank if you don't want to change your password">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Birthday</label>
              <input type="date" name="birthday" class="form-control" value="{{ $employee->birthday }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Place of Birth</label>
              <input type="text" name="place_of_birth" class="form-control" value="{{ $employee->place_of_birth }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Gender</label>
              <select name="gender" class="form-select">
                <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>Female</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-bold">Civil Status</label>
              <select name="civil_status" class="form-select">
                <option value="" {{ empty($employee->civil_status) ? 'selected' : '' }}>-- Select Civil Status --</option>
                <option value="Single" {{ $employee->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                <option value="Married" {{ $employee->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                <option value="Widowed" {{ $employee->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                <option value="Separated" {{ $employee->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-bold">Height(M)</label>
              <input type="number" step="0.01" name="height" class="form-control" value="{{ $employee->height }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Weight(KG)</label>
              <input type="number" step="0.1" name="weight" class="form-control" value="{{ $employee->weight }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Blood Type</label>
              <select name="blood_type" class="form-select">
                <option value="" {{ empty($employee->blood_type) ? 'selected' : '' }}>-- Select Blood Type --</option>
                <option value="A+" {{ $employee->blood_type == 'A+' ? 'selected' : '' }}>A+</option>
                <option value="A-" {{ $employee->blood_type == 'A-' ? 'selected' : '' }}>A-</option>
                <option value="B+" {{ $employee->blood_type == 'B+' ? 'selected' : '' }}>B+</option>
                <option value="B-" {{ $employee->blood_type == 'B-' ? 'selected' : '' }}>B-</option>
                <option value="AB+" {{ $employee->blood_type == 'AB+' ? 'selected' : '' }}>AB+</option>
                <option value="AB-" {{ $employee->blood_type == 'AB-' ? 'selected' : '' }}>AB-</option>
                <option value="O+" {{ $employee->blood_type == 'O+' ? 'selected' : '' }}>O+</option>
                <option value="O-" {{ $employee->blood_type == 'O-' ? 'selected' : '' }}>O-</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Tel No.</label>
              <input type="text" name="tel_no" class="form-control" value="{{ $employee->tel_no }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Mobile Number</label>
              <input type="text" name="mobile_no" class="form-control" value="{{ $employee->mobile_no }}">
            </div>
          </div>

          <hr class="my-4">
          <div class="row">
            {{-- Permanent Address --}}
            <div class="col-md-6">
              <h6 class="form-label fw-bold fs-6">Permanent Address</h6>
              {{-- Checkbox --}}
              <div class="form-check mb-3">
                <br>
              </div>

              <label class="form-label fw-bold">Region</label>
              <select name="perm_region" id="perm_region" class="form-select mb-3">
                <option value="">-- Select Region --</option>
              </select>

              <label class="form-label fw-bold">Province</label>
              <select name="perm_province" id="perm_province" class="form-select mb-3">
                <option value="">-- Select Province --</option>
              </select>

              <label class="form-label fw-bold">City/Municipality</label>
              <select name="perm_city" id="perm_city" class="form-select mb-3">
                <option value="">-- Select City --</option>
              </select>

              <label class="form-label fw-bold">Barangay</label>
              <select name="perm_barangay" id="perm_barangay" class="form-select mb-3">
                <option value="">-- Select Barangay --</option>
              </select>

              <label class="form-label fw-bold">Street</label>
              <input type="text" name="perm_street" id="perm_street" class="form-control mb-3" value="{{ $employee->perm_street ?? '' }}">

              <label class="form-label fw-bold">House No.</label>
              <input type="text" name="perm_house_no" id="perm_house_no" class="form-control mb-3" value="{{ $employee->perm_house_no ?? '' }}">

              <label class="form-label fw-bold">ZIP</label>
              <input type="text" name="perm_zipcode" id="perm_zipcode" class="form-control" value="{{ $employee->perm_zipcode ?? '' }}">
            </div>

            {{-- Residence Address --}}
            <div class="col-md-6">
              <h6 class="form-label fw-bold fs-6">Residence Address</h6>

              {{-- Checkbox --}}
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="sameAddress">
                <label class="form-check-label fw-bold" for="sameAddress">
                  Same as Permanent Address
                </label>
              </div>

              <label class="form-label fw-bold">Region</label>
              <select name="res_region" id="res_region" class="form-select mb-3">
                <option value="">-- Select Region --</option>
              </select>

              <label class="form-label fw-bold">Province</label>
              <select name="res_province" id="res_province" class="form-select mb-3">
                <option value="">-- Select Province --</option>
              </select>

              <label class="form-label fw-bold">City/Municipality</label>
              <select name="res_city" id="res_city" class="form-select mb-3">
                <option value="">-- Select City --</option>
              </select>

              <label class="form-label fw-bold">Barangay</label>
              <select name="res_barangay" id="res_barangay" class="form-select mb-3">
                <option value="">-- Select Barangay --</option>
              </select>

              <label class="form-label fw-bold">Street</label>
              <input type="text" name="res_street" id="res_street" class="form-control mb-3" value="{{ $employee->res_street ?? '' }}">

              <label class="form-label fw-bold">House No.</label>
              <input type="text" name="res_house_no" id="res_house_no" class="form-control mb-3" value="{{ $employee->res_house_no ?? '' }}">

              <label class="form-label fw-bold">ZIP</label>
              <input type="text" name="res_zipcode" id="res_zipcode" class="form-control" value="{{ $employee->res_zipcode ?? '' }}">
            </div>
          </div>

          {{-- jQuery Script --}}
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          <script>
            $(document).ready(function() {
              // ======== GET DATABASE VALUES =========
              let permRegion = "{{ $employee->perm_region ?? '' }}";
              let permProvince = "{{ $employee->perm_province ?? '' }}";
              let permCity = "{{ $employee->perm_city ?? '' }}";
              let permBarangay = "{{ $employee->perm_barangay ?? '' }}";

              let resRegion = "{{ $employee->res_region ?? '' }}";
              let resProvince = "{{ $employee->res_province ?? '' }}";
              let resCity = "{{ $employee->res_city ?? '' }}";
              let resBarangay = "{{ $employee->res_barangay ?? '' }}";

              // ======== LOAD ALL REGIONS =========
              $.get('/regions', function(data) {
                data.forEach(function(region) {
                  // Permanent
                  let permSelected = (region.psgc === permRegion) ? 'selected' : '';
                  $('#perm_region').append('<option value="' + region.psgc + '" ' + permSelected + '>' + region.name + '</option>');

                  // Residence
                  let resSelected = (region.psgc === resRegion) ? 'selected' : '';
                  $('#res_region').append('<option value="' + region.psgc + '" ' + resSelected + '>' + region.name + '</option>');
                });

                // Trigger province load for pre-filled data
                if (permRegion) $('#perm_region').trigger('change');
                if (resRegion) $('#res_region').trigger('change');
              });

              // ======== PERMANENT ADDRESS =========

              $('#perm_region').on('change', function() {
                let region_psgc = $(this).val();
                $('#perm_province').html('<option value="">-- Select Province --</option>');
                $('#perm_city').html('<option value="">-- Select City --</option>');
                $('#perm_barangay').html('<option value="">-- Select Barangay --</option>');

                if (region_psgc) {
                  $.get('/provinces/' + region_psgc, function(data) {
                    data.forEach(function(province) {
                      let selected = (province.psgc === permProvince) ? 'selected' : '';
                      $('#perm_province').append('<option value="' + province.psgc + '" ' + selected + '>' + province.name + '</option>');
                    });
                    if (permProvince) $('#perm_province').trigger('change');
                  });
                }
              });

              $('#perm_province').on('change', function() {
                let province_psgc = $(this).val();
                $('#perm_city').html('<option value="">-- Select City --</option>');
                $('#perm_barangay').html('<option value="">-- Select Barangay --</option>');

                if (province_psgc) {
                  $.get('/cities/' + province_psgc, function(data) {
                    data.forEach(function(city) {
                      let selected = (city.psgc === permCity) ? 'selected' : '';
                      $('#perm_city').append('<option value="' + city.psgc + '" ' + selected + '>' + city.name + '</option>');
                    });
                    if (permCity) $('#perm_city').trigger('change');
                  });
                }
              });

              $('#perm_city').on('change', function() {
                let city_psgc = $(this).val();
                $('#perm_barangay').html('<option value="">-- Select Barangay --</option>');

                if (city_psgc) {
                  $.get('/barangays/' + city_psgc, function(data) {
                    data.forEach(function(barangay) {
                      let selected = (barangay.psgc === permBarangay) ? 'selected' : '';
                      $('#perm_barangay').append('<option value="' + barangay.psgc + '" ' + selected + '>' + barangay.name + '</option>');
                    });
                  });
                }
              });

              // ======== RESIDENCE ADDRESS =========

              $('#res_region').on('change', function() {
                let region_psgc = $(this).val();
                $('#res_province').html('<option value="">-- Select Province --</option>');
                $('#res_city').html('<option value="">-- Select City --</option>');
                $('#res_barangay').html('<option value="">-- Select Barangay --</option>');

                if (region_psgc) {
                  $.get('/provinces/' + region_psgc, function(data) {
                    data.forEach(function(province) {
                      let selected = (province.psgc === resProvince) ? 'selected' : '';
                      $('#res_province').append('<option value="' + province.psgc + '" ' + selected + '>' + province.name + '</option>');
                    });
                    if (resProvince) $('#res_province').trigger('change');
                  });
                }
              });

              $('#res_province').on('change', function() {
                let province_psgc = $(this).val();
                $('#res_city').html('<option value="">-- Select City --</option>');
                $('#res_barangay').html('<option value="">-- Select Barangay --</option>');

                if (province_psgc) {
                  $.get('/cities/' + province_psgc, function(data) {
                    data.forEach(function(city) {
                      let selected = (city.psgc === resCity) ? 'selected' : '';
                      $('#res_city').append('<option value="' + city.psgc + '" ' + selected + '>' + city.name + '</option>');
                    });
                    if (resCity) $('#res_city').trigger('change');
                  });
                }
              });

              $('#res_city').on('change', function() {
                let city_psgc = $(this).val();
                $('#res_barangay').html('<option value="">-- Select Barangay --</option>');

                if (city_psgc) {
                  $.get('/barangays/' + city_psgc, function(data) {
                    data.forEach(function(barangay) {
                      let selected = (barangay.psgc === resBarangay) ? 'selected' : '';
                      $('#res_barangay').append('<option value="' + barangay.psgc + '" ' + selected + '>' + barangay.name + '</option>');
                    });
                  });
                }
              });

              // ======== SAME AS PERMANENT CHECKBOX =========
              $('#sameAddress').on('change', function() {
                if ($(this).is(':checked')) {
                  // Copy text fields
                  $('#res_street').val($('#perm_street').val());
                  $('#res_house_no').val($('#perm_house_no').val());
                  $('#res_zip').val($('#perm_zip').val());

                  // Copy select values (trigger for dependent fields)
                  $('#res_region').val($('#perm_region').val()).trigger('change');

                  // Cascade population with delay to ensure AJAX loads finish
                  setTimeout(() => {
                    $('#res_province').val($('#perm_province').val()).trigger('change');
                    setTimeout(() => {
                      $('#res_city').val($('#perm_city').val()).trigger('change');
                      setTimeout(() => {
                        $('#res_barangay').val($('#perm_barangay').val());
                      }, 700);
                    }, 700);
                  }, 700);
                } else {
                  // Clear residence fields
                  $('#res_region, #res_province, #res_city, #res_barangay').val('');
                  $('#res_street, #res_house_no, #res_zip').val('');
                }
              });
            });
          </script>


        </div>

        {{-- Right Column: Profile Picture --}}
        <div class="col-md-3 text-center d-flex flex-column align-items-center justify-content-start mt-4">
          <div class="position-relative mb-3" style="width: 180px; height: 180px;">
            <img id="preview-image"
              src="{{ $employee->profile_image ? asset('storage/' . $employee->profile_image) : asset('default-user.png') }}"
              class="rounded-circle border border-3 border-secondary shadow-sm"
              style="width: 100%; height: 100%; object-fit: cover;">
          </div>

          {{-- Upload Button --}}
          <label for="profile_image" class="btn btn-outline-primary btn-sm">Change Photo</label>
          <input type="file" name="profile_image" id="profile_image" class="d-none" accept="image/*">

          <div class="fw-bold mt-2">{{ $employee->first_name }} {{ $employee->last_name }}</div>
          <small class="text-muted">Employee</small>
        </div>

        {{-- Add this JS script below your form --}}
        @push('scripts')
        <script>
          document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
              const reader = new FileReader();
              reader.onload = function(event) {
                document.getElementById('preview-image').src = event.target.result;
              }
              reader.readAsDataURL(file);
            }
          });
        </script>
        @endpush

      </div>



      <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>
@endsection
@push('scripts')
<script>
  $(document).ready(function() {
    // Load all Regions
    $.get('/get-regions', function(regions) {
      regions.forEach(region => {
        $('#perm_region').append(`<option value="${region.psgc}">${region.name}</option>`);
        $('#res_region').append(`<option value="${region.psgc}">${region.name}</option>`);
      });

      // Preselect saved values (if any)
      if ($('#perm_region_psgc').val()) {
        $('#perm_region').val($('#perm_region_psgc').val()).trigger('change');
      }
      if ($('#res_region_psgc').val()) {
        $('#res_region').val($('#res_region_psgc').val()).trigger('change');
      }
    });


    function loadDropdowns(regionSelect, provinceSelect, citySelect, barangaySelect, regionCode, provinceCode, cityCode, barangayCode) {
      // When Region changes
      regionSelect.on('change', function() {
        const code = $(this).val();
        provinceSelect.html('<option value="">-- Select Province --</option>');
        citySelect.html('<option value="">-- Select City --</option>');
        barangaySelect.html('<option value="">-- Select Barangay --</option>');
        if (code) {
          $.get(`/get-provinces/${code}`, function(provinces) {
            provinces.forEach(p => provinceSelect.append(`<option value="${p.psgc}">${p.name}</option>`));
            if (provinceCode) provinceSelect.val(provinceCode).trigger('change');
          });
        }
      });

      // Province to City
      provinceSelect.on('change', function() {
        const code = $(this).val();
        citySelect.html('<option value="">-- Select City --</option>');
        barangaySelect.html('<option value="">-- Select Barangay --</option>');
        if (code) {
          $.get(`/get-municipalities/${code}`, function(cities) {
            cities.forEach(c => citySelect.append(`<option value="${c.psgc}">${c.name}</option>`));
            if (cityCode) citySelect.val(cityCode).trigger('change');
          });
        }
      });

      // City to Barangay
      citySelect.on('change', function() {
        const code = $(this).val();
        barangaySelect.html('<option value="">-- Select Barangay --</option>');
        if (code) {
          $.get(`/get-barangays/${code}`, function(barangays) {
            barangays.forEach(b => barangaySelect.append(`<option value="${b.psgc}">${b.name}</option>`));
            if (barangayCode) barangaySelect.val(barangayCode);
          });
        }
      });
    }


    let permRegionCode = $('#perm_region_psgc').val();
    let permProvinceCode = $('#perm_province_psgc').val();
    let permCityCode = $('#perm_city_psgc').val();
    let permBarangayCode = $('#perm_barangay_psgc').val();

    loadDropdowns($('#perm_region'), $('#perm_province'), $('#perm_city'), $('#perm_barangay'),
      permRegionCode, permProvinceCode, permCityCode, permBarangayCode);

    let resRegionCode = $('#res_region_psgc').val();
    let resProvinceCode = $('#res_province_psgc').val();
    let resCityCode = $('#res_city_psgc').val();
    let resBarangayCode = $('#res_barangay_psgc').val();

    loadDropdowns($('#res_region'), $('#res_province'), $('#res_city'), $('#res_barangay'),
      resRegionCode, resProvinceCode, resCityCode, resBarangayCode);
  });
</script>
@endpush