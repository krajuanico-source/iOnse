@csrf
<!-- Massive Hiring -->
<div class="mb-3">
    <div class="form-check">
        <input type="hidden" name="is_mass_hiring" value="0">
        <input class="form-check-input" type="checkbox" name="is_mass_hiring" value="1" id="is_mass_hiring">
        <label class="form-check-label" for="is_mass_hiring">
            Massive Hiring
        </label>
    </div>
</div>

<div class="mb-3 d-none" id="positionsCountWrapper">
    <label for="positions_count" class="form-label">Number of Positions</label>
    <input type="number"
           name="positions_count"
           id="positions_count"
           class="form-control"
           min="1"
           value="1">
</div>

<!-- Item Number -->
<div class="mb-3">
    <label for="item_no" class="form-label">Item Number</label>
    <input type="text" name="item_no" id="item_no" class="form-control">
</div>

<!-- Official Station -->
<div class="mb-3">
    <label for="office_location_id" class="form-label">Official Station</label>
    <select name="office_location_id" id="office_location_id" class="form-select select2" required>
        <option value="">Select Official Station</option>
        @foreach($officeLocations as $location)
            <option value="{{ $location->id }}">{{ $location->name }}</option>
        @endforeach
    </select>
</div>

<!-- Division -->
<div class="mb-3">
    <label for="division_id" class="form-label">Division</label>
    <select name="division_id" id="division_id" class="form-select select2" required>
        <option value="">Select Division</option>
        @foreach($divisions as $division)
            <option value="{{ $division->id }}">{{ $division->name }}</option>
        @endforeach
    </select>
</div>

<!-- Section (dependent on Division) -->
<div class="mb-3">
    <label for="section_id" class="form-label">Section</label>
    <select name="section_id" id="section_id" class="form-select select2" required>
        <option value="">Select Section</option>
    </select>
</div>

<!-- Program -->
<div class="mb-3">
    <label for="program" class="form-label">Program</label>
    <input type="text" name="program" id="program" class="form-control" required>
</div>

<!-- Date Created -->
<div class="mb-3">
    <label for="created_at" class="form-label">Date Created</label>
    <input type="date" name="created_at" id="created_at" class="form-control" value="{{ date('Y-m-d') }}" required>
</div>

<!-- Position Name -->
<div class="mb-3">
    <label for="position_name" class="form-label">Position Name</label>
    <input type="text" name="position_name" id="position_name" class="form-control" required>
</div>

<!-- Abbreviation -->
<div class="mb-3">
    <label for="abbreviation" class="form-label">Abbreviation</label>
    <input type="text" name="abbreviation" id="abbreviation" class="form-control" required>
</div>

<!-- Parenthetical Title -->
<div class="mb-3">
    <label for="parenthetical_title" class="form-label">Parenthetical Title</label>
    <input type="text" name="parenthetical_title" id="parenthetical_title" class="form-control" required>
</div>

<!-- Position Level -->
<div class="mb-3">
    <label for="position_level_id" class="form-label">Position Level</label>
    <select name="position_level_id" id="position_level_id" class="form-select select2" required>
        <option value="">Select Position Level</option>
        @foreach($positionLevels as $level)
            <option value="{{ $level->id }}">{{ $level->level_name }}</option>
        @endforeach
    </select>
</div>

<!-- Salary Tranche -->
<div class="mb-3">
    <label for="salary_tranche" class="form-label">Tranche</label>
   <select name="salary_tranche_id" id="salary_tranche" class="form-select" required>
        <option value="">Select Salary Tranche</option>
        @foreach($salaryTranches as $tranche)
            <option value="{{ $tranche->id }}">{{ $tranche->tranche_name }}</option>
        @endforeach
    </select>
</div>

<!-- Salary Grade -->
<div class="mb-3">
    <label for="salary_grade_id" class="form-label">Salary Grade</label>
    <select name="salary_grade_id" id="salary_grade_id" class="form-select" required>
        <option value="">Select Salary Grade</option>
    </select>
</div>

<!-- Salary Step -->
<div class="mb-3">
    <label for="salary_step_id" class="form-label">Salary Step Increment</label>
    <select name="salary_step_id" id="salary_step_id" class="form-select" required>
        <option value="">Select Step</option>
    </select>
</div>

<!-- Monthly Rate (auto) -->
<div class="mb-3">
    <label for="monthly_rate" class="form-label">Monthly Rate</label>
    <input type="number" step="0.01" name="monthly_rate" id="monthly_rate" class="form-control" readonly required>
</div>

<!-- Designation -->
<div class="mb-3">
    <label for="designation" class="form-label">Designation</label>
    <input type="text" name="designation" id="designation" class="form-control" required>
</div>

<!-- Special Order -->
<div class="mb-3">
    <label for="special_order" class="form-label">Special Order</label>
    <input type="text" name="special_order" id="special_order" class="form-control" required>
</div>

<!-- OBSU -->
<div class="mb-3">
    <label for="obsu" class="form-label">OBSU</label>
    <input type="text" name="obsu" id="obsu" class="form-control" required>
</div>

<!-- Fund Source -->
<div class="mb-3">
    <label for="fund_source_id" class="form-label">Fund Source</label>
    <select name="fund_source_id" id="fund_source_id" class="form-select select2" required>
        <option value="">Select Fund Source</option>
        @foreach($fundSources as $fsource)
            <option value="{{ $fsource->id }}">{{ $fsource->fund_source }}</option>
        @endforeach
    </select>
</div>

<!-- Employment Status -->
<div class="mb-3">
    <label for="employment_status_id" class="form-label">Employment Status</label>
    <select name="employment_status_id" id="employment_status_id" class="form-select select2" required>
        <option value="">Select Status</option>
        @foreach($employmentStatuses as $status)
            <option value="{{ $status->id }}">{{ $status->name }}</option>
        @endforeach
    </select>
</div>

<!-- Type of Request -->
<div class="mb-3">
    <label for="type_of_request" class="form-label">Type of Request</label>
    <select name="type_of_request" id="type_of_request" class="form-select select2" required>
        <option value="">Select Type</option>
        <option value="Direct Release">Direct Release</option>
        <option value="CMF">CMF</option>
    </select>
</div>
<div class="mb-3">
    <label for="date_of_publication" class="form-label">Date of Publication</label>
    <input type="date"
           name="date_of_publication"
           id="date_of_publication"
           class="form-control">
</div>
