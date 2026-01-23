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
        let user_id = $(this).val();
        if(!user_id) {
            $('#profileTabs').hide();
            return;
        }

        $.get(`/profile/user/${user_id}`, function(data) {
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
            let family = data.family_backgrounds?.[0];

            let familyHtml = '<div class="mb-3 p-2 rounded">';

            if(family){
                // Father
                const fatherFullName = [
                    family.father_first_name,
                    family.father_middle_name,
                    family.father_surname,
                    family.father_extension_name
                ].filter(Boolean).join(' ');
                familyHtml += `<p><b>Father's Name:</b> ${fatherFullName || 'N/A'}</p>`;

                // Mother
                const motherFullName = [
                    family.mother_first_name,
                    family.mother_middle_name,
                    family.mother_surname,
                    family.mother_extension_name
                ].filter(Boolean).join(' ');
                familyHtml += `<p><b>Mother's Name:</b> ${motherFullName || 'N/A'}</p>`;

                // Spouse
                const spouseFullName = [
                    family.spouse_first_name,
                    family.spouse_extension_name,
                    family.spouse_middle_name,
                    family.spouse_surname
                ].filter(Boolean).join(' ');

                let spouseBirthday = '';
                if(family.spouse_birthday){
                    const parts = family.spouse_birthday.split('-');
                    if(parts.length === 3){
                        spouseBirthday = ` (Born: ${parts[2]}/${parts[1]}/${parts[0]})`;
                    }
                }

                familyHtml += `<p><b>Spouse:</b> ${spouseFullName || 'N/A'}${spouseBirthday}</p>`;

                // Children
                if(family.children && family.children.length > 0){
                    familyHtml += `<p><b>Children:</b></p><ul>`;
                    family.children.forEach(child => {
                        const childFullName = [
                            child.first_name,
                            child.middle_name,
                            child.last_name
                        ].filter(Boolean).join(' ');

                        let childBirthday = '';
                        if(child.birthday){
                            const parts = child.birthday.split('-');
                            if(parts.length === 3){
                                childBirthday = ` (Born: ${parts[2]}/${parts[1]}/${parts[0]})`;
                            }
                        }

                        familyHtml += `<li>${childFullName || 'N/A'}${childBirthday}</li>`;
                    });
                    familyHtml += `</ul>`;
                } else {
                    familyHtml += `<p><b>Children:</b> N/A</p>`;
                }

            } else {
                familyHtml += `<p>No family background found.</p>`;
            }

            familyHtml += '</div>';

            $('#family-backgroundContent').html(familyHtml);

     

        // Education
        let educationHtml = '';

        if (data.educations && data.educations.length > 0) {
            data.educations.forEach(e => {
                const title = e.title || ''; // title of the education
                const level = e.level_of_education || 'N/A';
                const degree = e.degree_course || 'N/A';
                const school = e.school_name || 'N/A';

                let startYear = e.from ? e.from.split('-')[0] : 'N/A';
                let endYear = e.to ? e.to.split('-')[0] : 'N/A';

                let dateRange = startYear || endYear ? ` (${startYear} - ${endYear})` : '';

                
                educationHtml += `<p>
                        <b>${level}</b><br>
                        ${school}<br>
                        ${degree}<br>
                        ${dateRange}
                </p>
                `;
            
            });
        } else {
            educationHtml = '<p>No records.</p>';
        }

        $('#educationContent').html(educationHtml);


        // CS Eligibility
        let csHtml = '';

        if (data.cs_eligibilities && data.cs_eligibilities.length > 0) {
            data.cs_eligibilities.forEach(c => {
                const eligibility = c.eligibility || 'N/A';
                const rating = c.rating !== null ? c.rating : 'N/A';
                const examPlace = c.exam_place || 'N/A';
                const licenseNumber = c.license_number || 'N/A';

                // Full dates
                const examDate = c.exam_date ? new Date(c.exam_date).toLocaleDateString('en-GB') : 'N/A';
                const licenseValidity = c.license_validity ? new Date(c.license_validity).toLocaleDateString('en-GB') : 'N/A';

                csHtml += `
                        <b>${eligibility}</b><br>
                        Rating: ${rating}<br>
                        Exam: ${examDate} at ${examPlace}<br>
                        License: ${licenseNumber}<br>
                        (Valid: ${licenseValidity})

                `;
            });
            csHtml += '</ul>';
        } else {
            csHtml = 'No records.';
        }

        $('#cs-eligibilityContent').html(csHtml);

            // Work Experience
            let workHtml = '';
            if (data.work_experiences && data.work_experiences.length > 0) {
                workHtml = '<ul>';
                data.work_experiences.forEach(w => {
                    const position = w.position_title || 'N/A';
                    const company = w.department_agency || 'N/A';
                    const startYear = w.date_from ? w.date_from.split('-')[0] : 'N/A';
                    const endYear = w.date_to ? w.date_to.split('-')[0] : 'N/A';
                   
                    workHtml += `<p>
                        <b>${position}</b><br>
                        ${company}<br>
                        (${startYear} - ${endYear})<br>
                        </p>
                `;
                });
                workHtml += '</ul>';
            } else {
                workHtml = 'No records.';
            }
            $('#work-experienceContent').html(workHtml);

            // Voluntary Work
            let voluntaryHtml = '';
            if (data.voluntary_works && data.voluntary_works.length > 0) {
                voluntaryHtml = '<ul>';
                data.voluntary_works.forEach(v => {
                    const organization = v.organization_name || 'N/A';
                    const role = v.position_nature_of_work || 'N/A';
                    const startYear = v.date_from ? v.date_from.split('-')[0] : 'N/A';
                    const endYear = v.date_to ? v.date_to.split('-')[0] : 'N/A';
                    const hours = v.number_of_hours || 'N/A';
                    
                    voluntaryHtml += `<li>${organization} - ${role} (${startYear} - ${endYear}, ${hours} hours)</li>`;
                });
                voluntaryHtml += '</ul>';
            } else {
                voluntaryHtml = 'No records.';
            }
            $('#voluntary-workContent').html(voluntaryHtml);

           // Learning and Development
            let landdHtml = '';
            if (data.learning_and_developments && data.learning_and_developments.length > 0) {
                landdHtml = '<ul>';
                data.learning_and_developments.forEach(ld => {
                    const title = ld.title || 'N/A';
                    const hours = ld.number_of_hours || 'N/A';
                    const startYear = ld.inclusive_date_from ? ld.inclusive_date_from.split('-')[0] : 'N/A';
                    const endYear = ld.inclusive_date_to ? ld.inclusive_date_to.split('-')[0] : 'N/A';
                    const type = ld.type_of_ld || '';
                    const conductedBy = ld.conducted_by || '';
                    
                    let extraInfo = [];
                    if(type) extraInfo.push(type);
                    if(conductedBy) extraInfo.push(conductedBy);

                    landdHtml += `<li>${title} - ${hours} hours (${startYear} - ${endYear}${extraInfo.length ? ', ' + extraInfo.join(', ') : ''})</li>`;
                });
                landdHtml += '</ul>';
            } else {
                landdHtml = 'No records.';
            }
            $('#landdContent').html(landdHtml);

            // References
            let referencesHtml = '';
            if (data.references && data.references.length > 0) {
                referencesHtml = '<ul>';
                data.references.forEach(r => {
                    const name = r.name || 'N/A';
                    const contact = r.contact_number || 'N/A';
                    const address = r.ref_address || '';
                    const position = r.position || '';
                    
                    let extraInfo = [];
                    if(address) extraInfo.push(address);
                    if(position) extraInfo.push(position);

                    referencesHtml += `<li>${name}${extraInfo.length ? ' - ' + extraInfo.join(', ') : ''} (${contact})</li>`;
                });
                referencesHtml += '</ul>';
            } else {
                referencesHtml = 'No records.';
            }
            $('#referencesContent').html(referencesHtml);


            // Government IDs
            let idsHtml = '';
            if (data.government_ids && data.government_ids.length > 0) {
                idsHtml = '<ul>';
                data.government_ids.forEach(g => {
                    // We'll list all possible IDs
                    if(g.sss_id) idsHtml += `<li>SSS: ${g.sss_id}</li>`;
                    if(g.gsis_id) idsHtml += `<li>GSIS: ${g.gsis_id}</li>`;
                    if(g.pagibig_id) idsHtml += `<li>PAG-IBIG: ${g.pagibig_id}</li>`;
                    if(g.philhealth_id) idsHtml += `<li>PhilHealth: ${g.philhealth_id}</li>`;
                    if(g.tin) idsHtml += `<li>TIN: ${g.tin}</li>`;
                    if(g.philsys) idsHtml += `<li>PHILSYS: ${g.philsys}</li>`;
                    if(g.gov_issued_id && g.id_number) {
                        let extra = [];
                        if(g.date_issuance) extra.push(`Issued: ${g.date_issuance}`);
                        if(g.place_issuance) extra.push(`Place: ${g.place_issuance}`);
                        idsHtml += `<li>${g.gov_issued_id}: ${g.id_number}${extra.length ? ' (' + extra.join(', ') + ')' : ''}</li>`;
                    }
                });
                idsHtml += '</ul>';
            } else {
                idsHtml = 'No records.';
            }
            $('#idContent').html(idsHtml);

           // Non-academic
            let nonAcadHtml = '';
            if (data.non_academics && data.non_academics.length > 0) {
                nonAcadHtml = '<ul>';
                data.non_academics.forEach(n => {
                    const recognition = n.recognition || 'N/A';
                    nonAcadHtml += `<li>${recognition}</li>`;
                });
                nonAcadHtml += '</ul>';
            } else {
                nonAcadHtml = 'No records.';
            }
            $('#non-academicContent').html(nonAcadHtml);


           // Organization
            let orgHtml = '';
            if (data.organizations && data.organizations.length > 0) {
                orgHtml = '<ul>';
                data.organizations.forEach(o => {
                    const name = o.organization_name || 'N/A';
                    orgHtml += `<li>${name}</li>`;
                });
                orgHtml += '</ul>';
            } else {
                orgHtml = 'No records.';
            }
            $('#organizationContent').html(orgHtml);


           // Skills
            let skillsHtml = '';
            if (data.skills && data.skills.length > 0) {
                skillsHtml = '<ul>';
                data.skills.forEach(s => {
                    const skill = s.skill_name || 'N/A';
                    skillsHtml += `<li>${skill}</li>`;
                });
                skillsHtml += '</ul>';
            } else {
                skillsHtml = 'No records.';
            }
            $('#skillsContent').html(skillsHtml);


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
