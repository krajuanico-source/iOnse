@csrf
<input type="hidden" name="id" id="editPositionId">

<!-- Item Number -->
<div class="mb-3">
    <label for="edit_item_no" class="form-label">Item Number</label>
    <input type="text" name="item_no" id="edit_item_no" class="form-control">
</div>

<!-- Official Station -->
<div class="mb-3">
    <label for="edit_office_location_id" class="form-label">Official Station</label>
    <select name="office_location_id" id="edit_office_location_id" class="form-select select2" required>
        <option value="">Select Official Station</option>
        @foreach($officeLocations as $location)
            <option value="{{ $location->id }}">{{ $location->name }}</option>
        @endforeach
    </select>
</div>

<!-- Division -->
<div class="mb-3">
    <label for="edit_division_id" class="form-label">Division</label>
    <select name="division_id" id="edit_division_id" class="form-select select2" required>
        <option value="">Select Division</option>
        @foreach($divisions as $division)
            <option value="{{ $division->id }}">{{ $division->name }}</option>
        @endforeach
    </select>
</div>

<!-- Section -->
<div class="mb-3">
    <label for="edit_section_id" class="form-label">Section</label>
    <select name="section_id" id="edit_section_id" class="form-select select2" required>
        <option value="">Select Section</option>
    </select>
</div>

<!-- Program -->
<div class="mb-3">
    <label for="edit_program" class="form-label">Program</label>
    <input type="text" name="program" id="edit_program" class="form-control">
</div>

<!-- Date Created -->
<div class="mb-3">
    <label for="edit_created_at" class="form-label">Date Created</label>
    <input type="date" name="created_at" id="edit_created_at" class="form-control">
</div>

<!-- Position Name -->
<div class="mb-3">
    <label for="edit_position_name" class="form-label">Position Name</label>
    <input type="text" name="position_name" id="edit_position_name" class="form-control text-uppercase" required>
</div>

<!-- Abbreviation -->
<div class="mb-3">
    <label for="edit_abbreviation" class="form-label">Abbreviation</label>
    <input type="text" name="abbreviation" id="edit_abbreviation" class="form-control text-uppercase" required>
</div>

<!-- Parenthetical Title -->
<div class="mb-3">
    <label for="edit_parenthetical_title" class="form-label">Parenthetical Title</label>
    <input type="text" name="parenthetical_title" id="edit_parenthetical_title" class="form-control">
</div>


<!-- Position Level -->
<div class="mb-3">
    <label for="edit_position_level_id" class="form-label">Position Level</label>
    <select name="position_level_id" id="edit_position_level_id" class="form-select select2" required>
        <option value="">Select Position Level</option>
        @foreach($positionLevels as $level)
            <option value="{{ $level->id }}">{{ $level->level_name }}</option>
        @endforeach
    </select>
</div>

<!-- Salary Tranche -->
<div class="mb-3">
    <label for="edit_salary_tranche" class="form-label">Tranche</label>
    <select name="salary_tranche_id" id="edit_salary_tranche" class="form-select">
        <option value="">Select Salary Tranche</option>
        @foreach($salaryTranches as $tranche)
            <option value="{{ $tranche->id }}">{{ $tranche->tranche_name }}</option>
        @endforeach
    </select>
</div>

<!-- Salary Grade -->
<div class="mb-3">
    <label for="edit_salary_grade_id" class="form-label">Salary Grade</label>
    <select name="salary_grade_id" id="edit_salary_grade_id" class="form-select">
        <option value="">Select Salary Grade</option>
    </select>
</div>

<!-- Salary Step -->
<div class="mb-3">
    <label for="edit_salary_step_id" class="form-label">Salary Step Increment</label>
    <select name="salary_step_id" id="edit_salary_step_id" class="form-select">
        <option value="">Select Step</option>
    </select>
</div>

<!-- Monthly Rate -->
<div class="mb-3">
    <label for="edit_monthly_rate" class="form-label">Monthly Rate</label>
    <input type="number" step="0.01" name="monthly_rate" id="edit_monthly_rate" class="form-control" readonly>
</div>

<!-- Designation -->
<div class="mb-3">
    <label for="edit_designation" class="form-label">Designation</label>
    <input type="text" name="designation" id="edit_designation" class="form-control">
</div>

<!-- Special Order -->
<div class="mb-3">
    <label for="edit_special_order" class="form-label">Special Order</label>
    <input type="text" name="special_order" id="edit_special_order" class="form-control">
</div>

<!-- OBSU -->
<div class="mb-3">
    <label for="edit_obsu" class="form-label">OBSU</label>
    <input type="text" name="obsu" id="edit_obsu" class="form-control">
</div>

<!-- Fund Source -->
<div class="mb-3">
    <label for="edit_fund_source" class="form-label">Fund Source</label>
    <input type="text" name="fund_source" id="edit_fund_source" class="form-control">
</div>

<!-- Employment Status -->
<div class="mb-3">
    <label for="edit_employment_status_id" class="form-label">Employment Status</label>
    <select name="employment_status_id" id="edit_employment_status_id" class="form-select select2">
        <option value="">Select Status</option>
        @foreach($employmentStatuses as $status)
            <option value="{{ $status->id }}">{{ $status->name }}</option>
        @endforeach
    </select>
</div>

<!-- Type of Request -->
<div class="mb-3">
    <label for="edit_type_of_request" class="form-label">Type of Request</label>
    <select name="type_of_request" id="edit_type_of_request" class="form-select select2">
        <option value="">Select Type</option>
        <option value="Direct Release">Direct Release</option>
        <option value="CMF">CMF</option>
    </select>
</div>

<!-- Date of Publication -->
<div class="mb-3">
    <label for="edit_date_of_publication" class="form-label">Date of Publication</label>
    <input type="date" name="date_of_publication" id="edit_date_of_publication" class="form-control">
</div>
