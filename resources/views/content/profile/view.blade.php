@extends('layouts/contentNavbarLayout')

@section('title', 'Employee Profile Viewer')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<div class="card shadow-sm p-3">
    <h4 class="mb-3">Select Employee</h4>
    <select id="employeeSelect" class="form-select mb-4">
        <option value="">-- Select Employee --</option>
        @foreach($employees as $emp)
            <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
        @endforeach
    </select>

    <div id="profileTabs" style="display:none;">
        {{-- Submenu Tabs --}}
        <ul class="nav nav-tabs" id="profileTab" role="tablist">
            @php
            $submenu = [
                ["slug" => "basic", "name" => "Basic Information"],
                ["slug" => "family-background", "name" => "Family Background"],
                ["slug" => "education", "name" => "Educational Background"],
                ["slug" => "cs-eligibility", "name" => "CS Eligibility"],
                ["slug" => "work-experience", "name" => "Work Experience"],
                ["slug" => "voluntary-work", "name" => "Voluntary Work"],
                ["slug" => "landd", "name" => "Learning and Development"],
                ["slug" => "references", "name" => "References"],
                ["slug" => "id", "name" => "Government ID"],
                ["slug" => "non-academic", "name" => "Non-academic"],
                ["slug" => "organization", "name" => "Organization"],
                ["slug" => "skills", "name" => "Skills"],
                ["slug" => "other-information", "name" => "Other Information"],
            ];
            @endphp

            @foreach($submenu as $index => $tab)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $index==0 ? 'active' : '' }}" 
                        id="{{ $tab['slug'] }}-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#{{ $tab['slug'] }}" 
                        type="button" role="tab">
                        {{ $tab['name'] }}
                </button>
            </li>
            @endforeach
        </ul>

        <div class="tab-content mt-3" id="profileTabContent">
            @foreach($submenu as $index => $tab)
            <div class="tab-pane fade {{ $index==0 ? 'show active' : '' }}" id="{{ $tab['slug'] }}" role="tabpanel">
                <div id="{{ $tab['slug'] }}Content">Select an employee to load data.</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#employeeSelect').change(function() {
        let empId = $(this).val();
        if(!empId) {
            $('#profileTabs').hide();
            return;
        }

        $.get(`/profile/user/${empId}`, function(data) {
            $('#profileTabs').show();

            const resAddr = [
                data.res_house_no,
                data.res_street,
                data.res_barangay?.name,
                data.res_city?.name,
                data.res_province?.name,
                data.res_region?.name,
                data.res_zipcode
            ].filter(Boolean).join(', ').toUpperCase();

            const permAddr = [
                data.perm_house_no,
                data.perm_street,
                data.perm_barangay?.name,
                data.perm_city?.name,
                data.perm_province?.name,
                data.perm_region?.name,
                data.perm_zipcode
            ].filter(Boolean).join(', ').toUpperCase();
                
            $('#basicContent').html(`
                <div class="row">
                    <div class="col-md-6">
                        <p><b>Full Name:</b> ${data.first_name} ${data.middle_name ?? ''} ${data.last_name}</p>
                        <p><b>Gender:</b> ${data.gender?.toUpperCase() ?? ''}</p>
                        <p><b>Birthday:</b> ${data.birthday ?? ''}</p>
                        <p><b>Civil Status:</b> ${data.civil_status?.toUpperCase() ?? ''}</p>
                        <p><b>Blood Type:</b> ${data.blood_type ?? ''}</p>
                        <p><b>Height:</b> ${data.height ?? ''}</p>
                        <p><b>Weight:</b> ${data.weight ?? ''}</p>
                        <p><b>Citizenship:</b> ${data.citizenship?.toUpperCase() ?? ''}</p>
                    </div>
                    <div class="col-md-6">
                        <p><b>Email:</b> ${data.email ?? ''}</p>
                        <p><b>Username:</b> ${data.username ?? ''}</p>
                        <p><b>Employee ID:</b> ${data.employee_id ?? ''}</p>
                        <p><b>Mobile Number:</b> ${data.mobile_no ?? ''}</p>
                        <p><b>Telephone Number:</b> ${data.tel_no ?? ''}</p>
                        <p><b>Place of Birth:</b> ${data.place_of_birth ?? ''}</p>
                        <p><b>Residential Address:</b> ${resAddr}</p>
                        <p><b>Permanent Address:</b> ${permAddr}</p>
                        <p><b>Country:</b> ${data.perm_country?.toUpperCase() ?? ''}</p>
                    </div>
                </div>

            `);
           let family = data.familyBackgrounds?.[0]; // first (and only) family record

let familyHtml = '<div class="mb-3 p-2 border rounded">';

if(family){
    const fatherFullName = [
        family.father_first_name,
        family.father_middle_name,
        family.father_surname,
        family.father_extension_name
    ].filter(Boolean).join(' ');

    familyHtml += `<p><b>Father's Name:</b> ${fatherFullName || 'N/A'}</p>`;

    const motherFullName = [
        family.mother_first_name,
        family.mother_middle_name,
        family.mother_surname,
        family.mother_extension_name
    ].filter(Boolean).join(' ');

    familyHtml += `<p><b>Mother's Name:</b> ${motherFullName || 'N/A'}</p>`;
}

familyHtml += '</div>';

$('#family-backgroundContent').html(familyHtml);


            // Education
            let educationHtml = data.educations?.length
                ? '<ul>' + data.educations.map(e => `<li>${e.degree} - ${e.school}</li>`).join('') + '</ul>'
                : 'No records.';
            $('#educationContent').html(educationHtml);

            // CS Eligibility
            let csHtml = data.csEligibilities?.length
                ? '<ul>' + data.csEligibilities.map(c => `<li>${c.eligibility} - ${c.rating}</li>`).join('') + '</ul>'
                : 'No records.';
            $('#cs-eligibilityContent').html(csHtml);

            // Work Experience
            $('#work-experienceContent').html(
                data.workExperiences?.length
                    ? data.workExperiences.map(w => `<p>${w.position} at ${w.company}</p>`).join('')
                    : 'No records.'
            );

            // Voluntary Work
            $('#voluntary-workContent').html(
                data.voluntaryWorks?.length
                    ? data.voluntaryWorks.map(v => `<p>${v.organization} - ${v.role}</p>`).join('')
                    : 'No records.'
            );

            // Learning and Development
            $('#landdContent').html(
                data.learningAndDevelopments?.length
                    ? data.learningAndDevelopments.map(ld => `<p>${ld.program} - ${ld.hours} hours</p>`).join('')
                    : 'No records.'
            );

            // References
            $('#referencesContent').html(
                data.references?.length
                    ? data.references.map(r => `<p>${r.name} - ${r.contact}</p>`).join('')
                    : 'No records.'
            );

            // Government IDs
            $('#idContent').html(
                data.governmentIds?.length
                    ? data.governmentIds.map(g => `<p>${g.type} - ${g.number}</p>`).join('')
                    : 'No records.'
            );

            // Non-academic
            $('#non-academicContent').html(
                data.nonAcademics?.length
                    ? data.nonAcademics.map(n => `<p>${n.activity} - ${n.role}</p>`).join('')
                    : 'No records.'
            );

            // Organization
            $('#organizationContent').html(
                data.organizations?.length
                    ? data.organizations.map(o => `<p>${o.name} - ${o.position}</p>`).join('')
                    : 'No records.'
            );

            // Skills
            $('#skillsContent').html(
                data.Skills?.length
                    ? data.Skills.map(s => `<p>${s.skill} (${s.level})</p>`).join('')
                    : 'No records.'
            );

            // Other Information
            $('#other-informationContent').html(
                data.otherInformations?.length  
                    ? data.otherInformations.map(o => `<p>${o.info}</p>`).join('')
                    : 'No records.'
            );

        }).fail(function() {
            alert('Failed to fetch employee data.');
        });
    });
});
</script>

@endsection
