<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\RiIcons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\learning\LearningDev;
use App\Http\Controllers\learning\Trainings;
use App\Http\Controllers\learning\CourseController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\learning\CalendarController;
use App\Http\Controllers\learning\EventsController;
use App\Http\Controllers\learning\ScholarshipController;


//Profile
use App\Http\Controllers\Profile\BasicInformationController;
use App\Http\Controllers\Profile\FamilyBackgroundController;
use App\Http\Controllers\Profile\EducationController;
use App\Http\Controllers\Profile\CsEligibilityController;
use App\Http\Controllers\Profile\WorkExperienceController;
use App\Http\Controllers\Profile\VoluntaryWorkController;
use App\Http\Controllers\Profile\LearningAndDevelopmentController;
use App\Http\Controllers\Profile\ReferenceController;
use App\Http\Controllers\Profile\GovernmentIdController;
use App\Http\Controllers\Profile\NonAcademicController;
use App\Http\Controllers\Profile\OrganizationController;
use App\Http\Controllers\Profile\SkillController;
use App\Http\Controllers\Profile\OtherInformationController;
use App\Http\Controllers\Profile\PdsController;
use App\Http\Controllers\RequestsController;

//Planning
use App\Http\Controllers\planning\DashboardController;
use App\Http\Controllers\planning\ListofEmployee;
use App\Http\Controllers\Planning\ApplicantController;
use App\Http\Controllers\planning\RegistrationForm;
use App\Http\Controllers\planning\ListofPosition;
use App\Http\Controllers\planning\VacantPositionController;
use App\Http\Controllers\planning\OfficeLocation;
use App\Http\Controllers\planning\DivisionController;
use App\Http\Controllers\planning\UnitController;
use App\Http\Controllers\planning\SectionController;
use App\Http\Controllers\Planning\EmploymentStatusController;
use App\Http\Controllers\Planning\OfficeLocationController;
use App\Http\Controllers\Planning\QualificationController;
use App\Http\Controllers\Planning\SalaryGradeController;
use App\Http\Controllers\Planning\PositionLevelController;
use App\Http\Controllers\Planning\ParentheticalTitleController;
use App\Http\Controllers\Planning\ReportController;
use App\Http\Controllers\Planning\JoRequestController;
use App\Http\Controllers\GadResponseController;
use App\Http\Controllers\EthnicityController;
use App\Http\Controllers\MedicalController;
use App\Http\Controllers\SoloParentController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\SpecialController;
use App\Http\Controllers\RequestFormController;
use App\Http\Controllers\CprController;
use App\Http\Controllers\CprEmployeeController;



//PAS
use App\Http\Controllers\pas\FundSourceController;
use App\Http\Controllers\pas\PayrollController;
use App\Http\Controllers\pas\TaxController;
use App\Http\Controllers\pas\EmployeesListController;

use App\Http\Controllers\pas\ImportPayrollController;
use App\Http\Controllers\pas\LeaveCreditsController;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\Planning\PositionController;
use App\Http\Controllers\ItemNumberController;
use App\Http\Controllers\UnfilledPositionsController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\OutSlipController;
use App\Http\Controllers\LeaveController;


// Login Page

//Welfare
use App\Http\Controllers\Welfare\MemorandumController;




// Redirect root URL to login page
Route::get('/', function () {
  return redirect()->route('auth-login-basic');
});

Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');

// Dashboard (you can protect this later with auth middleware)
Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');

// Dashboard (you can protect this later with auth middleware)
Route::get('/dashboard/dashboards-analytics', [Analytics::class, 'index'])->name('dashboards-analytics');

//-------------------------------------------------------START OF PROFILE-----------------------------------------------------------

Route::get('/regions', [AddressController::class, 'getRegions']);
Route::get('/provinces/{region_psgc}', [AddressController::class, 'getProvinces']);
Route::get('/cities/{province_psgc}', [AddressController::class, 'getCities']);
Route::get('/barangays/{city_psgc}', [AddressController::class, 'getBarangays']);


Route::prefix('profile')->group(function () {
  Route::get('basic-information', [BasicInformationController::class, 'index'])
    ->name('profile.basic-info.index');

  Route::post('basic-information/update', [BasicInformationController::class, 'update'])
    ->name('profile.basic-info.update');
});

Route::middleware(['auth'])->group(function () {
  Route::get('/profile/family-background', [FamilyBackgroundController::class, 'edit'])->name('profile.family-background.edit');
  Route::post('/profile/family-background', [FamilyBackgroundController::class, 'update'])->name('profile.family-background.update');
});

Route::middleware(['auth'])->group(function () {
  Route::get('profile/education', [EducationController::class, 'index'])->name('profile.education.index');
  Route::post('profile/education/save', [EducationController::class, 'save'])->name('profile.education.save');
  Route::get('profile/education/{id}', [EducationController::class, 'show']);
  Route::put('profile/education/{id}/update', [EducationController::class, 'update'])->name('profile.education.update');
  Route::delete('profile/education/delete/{id}', [EducationController::class, 'delete']);
});

Route::middleware(['auth'])->group(function () {
  Route::get('profile/cs-eligibility', [CsEligibilityController::class, 'index'])->name('profile.cs-eligibility.index');
  Route::post('profile/cs-eligibility/store', [CsEligibilityController::class, 'store'])->name('profile.cs-eligibility.store');
  Route::put('profile/cs-eligibility/{id}', [CsEligibilityController::class, 'update'])->name('profile.cs-eligibility.update');
  Route::delete('profile/cs-eligibility/{id}', [CsEligibilityController::class, 'destroy'])->name('profile.cs-eligibility.destroy');
});

Route::middleware(['auth'])->group(function () {
  Route::get('profile/work-experience', [WorkExperienceController::class, 'index'])->name('profile.work-experience.index');
  Route::post('profile/work-experience/store', [WorkExperienceController::class, 'store'])->name('profile.work-experience.store');
  Route::put('profile/work-experience/{id}', [WorkExperienceController::class, 'update'])->name('profile.work-experience.update');
  Route::delete('profile/work-experience/{id}', [WorkExperienceController::class, 'destroy'])->name('profile.work-experience.destroy');
});


Route::middleware(['auth'])->group(function () {
  Route::get('profile/voluntary-work', [VoluntaryWorkController::class, 'index'])->name('profile.voluntary-work.index');
  Route::post('profile/voluntary-work/store', [VoluntaryWorkController::class, 'store'])->name('profile.voluntary-work.store');
  Route::put('profile/voluntary-work/{id}', [VoluntaryWorkController::class, 'update'])->name('profile.voluntary-work.update');
  Route::delete('profile/voluntary-work/{id}', [VoluntaryWorkController::class, 'destroy'])->name('profile.voluntary-work.destroy');
});

Route::middleware(['auth'])->group(function () {
  Route::get('profile/learning-development', [LearningAndDevelopmentController::class, 'index'])->name('profile.ld.index');
  Route::post('profile/learning-development/store', [LearningAndDevelopmentController::class, 'store'])->name('profile.ld.store');
  Route::put('profile/learning-development/{id}', [LearningAndDevelopmentController::class, 'update'])->name('profile.ld.update');
  Route::delete('profile/learning-development/{id}', [LearningAndDevelopmentController::class, 'destroy'])->name('profile.ld.destroy');
});

Route::middleware(['auth'])->group(function () {
  Route::get('profile/references', [ReferenceController::class, 'index'])->name('profile.references.index');
  Route::post('profile/references/store', [ReferenceController::class, 'store'])->name('profile.references.store');
  Route::put('profile/references/{id}', [ReferenceController::class, 'update'])->name('profile.references.update');
  Route::delete('profile/references/{id}', [ReferenceController::class, 'destroy'])->name('profile.references.destroy');
});

Route::middleware(['auth'])->group(function () {
  Route::get('profile/government-ids', [GovernmentIdController::class, 'index'])->name('profile.government-ids.index');
  Route::post('profile/government-ids/store', [GovernmentIdController::class, 'store'])->name('profile.government-ids.store');
  Route::put('profile/government-ids/{id}', [GovernmentIdController::class, 'update'])->name('profile.government-ids.update');
  Route::delete('profile/government-ids/{id}', [GovernmentIdController::class, 'destroy'])->name('profile.government-ids.destroy');
});

Route::middleware(['auth'])->group(function () {
  Route::get('profile/non-academic', [NonAcademicController::class, 'index'])->name('profile.non-academic.index');
  Route::post('profile/non-academic', [NonAcademicController::class, 'store'])->name('profile.non-academic.store');
  Route::get('profile/non-academic/{id}', [NonAcademicController::class, 'show'])->name('profile.non-academic.show');  // 👈 for edit modal
  Route::put('profile/non-academic/{id}', [NonAcademicController::class, 'update'])->name('profile.non-academic.update');
  Route::delete('profile/non-academic/{id}', [NonAcademicController::class, 'destroy'])->name('profile.non-academic.destroy');
});

Route::middleware(['auth'])->group(function () {
  Route::get('profile/organization', [OrganizationController::class, 'index'])->name('profile.organization.index');
  Route::post('profile/organization', [OrganizationController::class, 'store'])->name('profile.organization.store');
  Route::get('profile/organization/{id}', [OrganizationController::class, 'show'])->name('profile.organization.show');
  Route::put('profile/organization/{id}', [OrganizationController::class, 'update'])->name('profile.organization.update');
  Route::delete('profile/organization/{id}', [OrganizationController::class, 'destroy'])->name('profile.organization.destroy');
});

Route::middleware(['auth'])->group(function () {
  Route::get('profile/skills', [SkillController::class, 'index'])->name('profile.skills.index');
  Route::post('profile/skills', [SkillController::class, 'store'])->name('profile.skills.store');
  Route::get('profile/skills/{id}', [SkillController::class, 'show'])->name('profile.skills.show');
  Route::put('profile/skills/{id}', [SkillController::class, 'update'])->name('profile.skills.update');
  Route::delete('profile/skills/{id}', [SkillController::class, 'destroy'])->name('profile.skills.destroy');
});

Route::middleware(['auth'])->group(function () {
  Route::get('profile/other-information', [OtherInformationController::class, 'index'])->name('profile.other-information.index');
  Route::post('profile/other-information', [OtherInformationController::class, 'store'])->name('profile.other-information.store');
});

Route::get('/pds/print', [PdsController::class, 'generate'])->name('pds.print');

//Address
//-------------------------------------------------------START OF PLANNING-----------------------------------------------------------
Route::prefix('planning/applicants')->name('applicants.')->group(function () {
  Route::get('/', [ApplicantController::class, 'index'])->name('index');
  Route::post('/store', [ApplicantController::class, 'store'])->name('store');
  Route::post('/{id}/update', [ApplicantController::class, 'update'])->name('update');
  Route::post('/{id}/archive', [ApplicantController::class, 'archive'])
    ->name('archive');
  Route::get('/next-number', [ApplicantController::class, 'nextApplicantNumber']);
});

Route::prefix('planning')->group(function () {
  Route::get('/list-of-employee', [UserController::class, 'index'])->name('planning.list-of-employee');
  Route::get('/import-form', [UserController::class, 'showImportForm'])->name('planning.import-form');
  Route::post('/import', [UserController::class, 'importEmployees'])->name('planning.import');
});


Route::get('/planning/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Registration Form Routes
Route::prefix('planning')->group(function () {
  Route::get('/registration-form', [RegistrationForm::class, 'index'])->name('registration-form.index');
  Route::post('/planning/list-of-employee', [UserController::class, 'store'])->name('employee.store');
});
Route::get('/planning/registration-form', [\App\Http\Controllers\Api\UserController::class, 'create'])->name('employee.registration-form');


Route::get('/planning/list-of-employee', [UserController::class, 'bladeIndex'])->name('employee.view-blade');
// Edit employee form
Route::get('/planning/list-of-employee/{id}/edit', [UserController::class, 'edit'])
  ->name('employee.edit');
// Delete employee
Route::delete('/planning/list-of-employee/{id}', [UserController::class, 'destroy'])
  ->name('employee.delete');
Route::get('/planning/list-of-employee/{id}/view', [UserController::class, 'show'])->name('employee.show-view');
Route::get('/planning/list-of-employee/{id}', [UserController::class, 'show'])->name('employee.view');
Route::get('planning/list-of-employee/{id}/edit', [UserController::class, 'edit'])->name('employees.edit');
Route::put('planning/list-of-employee/{id}', [UserController::class, 'update'])->name('employees.update');

Route::prefix('forms')->name('forms.')->group(function () {
  Route::get('/gad_profile', [GadResponseController::class, 'index'])->name('gad_profile.forms');
  Route::get('/gad_profile/create', [GadResponseController::class, 'create'])->name('gad_profile.create');
  Route::post('/gad_profile', [GadResponseController::class, 'store'])->name('gad_profile.store');
  Route::get('/gad_profile/{id}', [GadResponseController::class, 'show'])->name('gad_profile.show');
  Route::get('/gad_profile/{id}/edit', [GadResponseController::class, 'edit'])->name('gad_profile.edit');
  Route::put('/gad_profile/{id}', [GadResponseController::class, 'update'])->name('gad_profile.update');
  Route::delete('/gad_profile/{id}', [GadResponseController::class, 'destroy'])->name('gad_profile.destroy');
  Route::get('/gad_profile/{id}/print', [GadResponseController::class, 'print'])->name('gad_profile.print');
});

Route::prefix('forms')->name('forms.')->group(function () {
  Route::get('/ethnicity/create', [EthnicityController::class, 'create'])->name('ethnicity.create');
  Route::post('/ethnicity', [EthnicityController::class, 'store'])->name('ethnicity.store');
  Route::post('/forms/ethnicity', [EthnicityController::class, 'store'])->name('forms.ethnicity.store');
});

Route::prefix('forms')->group(function () {
  Route::get('/medical/create', [MedicalController::class, 'create'])->name('forms.medical.create');
  Route::post('/medical', [MedicalController::class, 'store'])->name('forms.medical.store');
  Route::post('/forms/medical', [MedicalController::class, 'store'])->name('forms.medical.store');
});

Route::prefix('forms')->group(function () {
  Route::get('/solo_parent/create', [SoloParentController::class, 'create'])->name('forms.solo_parent.create');
  Route::post('/solo_parent', [SoloParentController::class, 'store'])->name('forms.solo_parent.store');
  Route::post('/forms/solo_parent', [SoloParentController::class, 'store'])->name('forms.solo_parent.store');
});

Route::prefix('forms/travel')->name('forms.travel.')->group(function () {

  // List all travel orders
  Route::get('/', [TravelController::class, 'index'])->name('index');

  // Create / Store
  Route::get('/create', [TravelController::class, 'create'])->name('create');
  Route::post('/store', [TravelController::class, 'store'])->name('store');

  // Edit / Update
  Route::get('/{id}/edit', [TravelController::class, 'edit'])->name('edit');
  Route::put('/{id}', [TravelController::class, 'update'])->name('update');

  // Delete
  Route::delete('/{id}', [TravelController::class, 'destroy'])->name('destroy');

  // Print travel order
  Route::get('/{ref}/print', [TravelController::class, 'print'])->name('print');

  // Electronic signature
  Route::post('/{ref}/electronic-sign', [TravelController::class, 'electronicSignImage'])->name('electronicSignImage');

  // Digital signature
  Route::post('/{ref}/digitalSignImage', [TravelController::class, 'digitalSignImage'])->name('digitalSignImage');

  // Prepare signature (optional helper)
  Route::get('/{ref}/prepare-sign', [TravelController::class, 'prepareSign'])->name('prepareSign');

  // Download signed PDF
  Route::get('/{ref}/download', [TravelController::class, 'downloadPdf'])->name('download');
});

Route::post('/forms/outslip/{id}/digital-sign', [OutSlipController::class, 'digitalSignImage'])
  ->name('forms.outslip.digitalSignImage')
  ->middleware(['auth']);

Route::post('/forms/outslip/{id}/electronic-sign', [OutSlipController::class, 'electronicSignImage'])
  ->name('forms.outslip.electronicSignImage')
  ->middleware(['auth']);

Route::post('/forms/leaves/{leave_no}/digital-sign', [LeaveController::class, 'digitalSignImage'])
  ->name('forms.leaves.digitalSignImage')
  ->middleware(['auth']);

Route::post('/forms/leaves/{leave_no}/electronic-sign', [LeaveController::class, 'electronicSignImage'])
  ->name('forms.leaves.electronicSignImage')
  ->middleware(['auth']);



Route::prefix('forms/special')->name('forms.special.')->group(function () {

  // List all travel orders
  Route::get('/', [SpecialController::class, 'index'])->name('index');

  // Create / Store
  Route::get('/create', [SpecialController::class, 'create'])->name('create');
  Route::post('/store', [SpecialController::class, 'store'])->name('store');

  // Edit / Update
  Route::get('/{id}/edit', [SpecialController::class, 'edit'])->name('edit');
  Route::put('/{id}', [SpecialController::class, 'update'])->name('update');

  // Delete
  Route::delete('/{id}', [SpecialController::class, 'destroy'])->name('destroy');

  // Print travel order
  Route::get('/{ref}/print', [SpecialController::class, 'print'])->name('print');

  // Electronic signature
  Route::post('/{ref}/electronic-sign', [SpecialController::class, 'electronicSignImage'])->name('electronicSignImage');

  // Digital signature
  Route::post('/{ref}/digitalSignImage', [SpecialController::class, 'digitalSignImage'])->name('digitalSignImage');

  // Prepare signature (optional helper)
  Route::get('/{ref}/prepare-sign', [SpecialController::class, 'prepareSign'])->name('prepareSign');

  // Download signed PDF
  Route::get('/{ref}/download', [SpecialController::class, 'downloadPdf'])->name('download');
});

Route::prefix('forms/request_forms')->name('forms.request_forms.')->group(function () {

  // List
  Route::get('/', [RequestFormController::class, 'index'])->name('index');

  // Create
  Route::get('/create', [RequestFormController::class, 'create'])->name('create');
  Route::post('/store', [RequestFormController::class, 'store'])->name('store');

  // Show
  Route::get('/{req_num}', [RequestFormController::class, 'show'])->name('show');

  // Edit + Update
  Route::get('/{req_num}/edit', [RequestFormController::class, 'edit'])->name('edit');
  Route::put('/{req_num}', [RequestFormController::class, 'update'])->name('update');

  // Delete
  Route::delete('/{req_num}', [RequestFormController::class, 'destroy'])->name('destroy');

  // Print PDF
  Route::get('/{req_num}/print', [RequestFormController::class, 'print'])->name('print');
});

Route::prefix('requests')->middleware(['auth'])->group(function () {
  Route::get('leave', [RequestsController::class, 'leave'])->name('requests.leave');
  Route::get('outslip', [RequestsController::class, 'outslip'])->name('requests.outslip');
  Route::get('payslip', [RequestsController::class, 'payslip'])->name('requests.payslip');
  Route::get('request_forms', [RequestsController::class, 'requestForms'])->name('requests.request_forms');
});

// Filtered Employee Lists
Route::get('/planning/active-employees', [\App\Http\Controllers\Api\UserController::class, 'active'])->name('employee.active');
Route::get('/planning/retired-employees', [\App\Http\Controllers\Api\UserController::class, 'retired'])->name('employee.retired');
Route::get('/planning/resigned-employees', [\App\Http\Controllers\Api\UserController::class, 'resigned'])->name('employee.resigned');
Route::put('/employee/{id}/assign-role', [UserController::class, 'assignRole'])->name('employee.assignRole');


// //import
// Route::prefix('planning')->group(function () {
Route::get('/import-form', [\App\Http\Controllers\Api\UserController::class, 'showImportForm'])->name('planning.import-form');
//   Route::post('/import', [\App\Http\Controllers\Api\UserController::class, 'importEmployees'])->name('planning.import');
// });

Route::prefix('/planning/division')->group(function () {
  Route::get('/', [DivisionController::class, 'index'])->name('division.index');
  Route::post('/store', [DivisionController::class, 'store'])->name('division.store');
  Route::post('/{id}/update', [DivisionController::class, 'update'])->name('division.update');
  Route::post('/{id}/delete', [DivisionController::class, 'destroy'])->name('division.delete');
});

//Section Management
Route::prefix('/planning/section')->group(function () {
  Route::get('/', [SectionController::class, 'index'])->name('section.index');
  Route::post('/store', [SectionController::class, 'store'])->name('section.store');
  Route::post('/{id}/update', [SectionController::class, 'update'])->name('section.update');
  Route::post('/{id}/delete', [SectionController::class, 'destroy'])->name('section.delete');
});


//Unit Management
Route::prefix('planning/unit')->name('unit.')->group(function () {
  Route::get('/', [UnitController::class, 'index'])->name('index');
  Route::post('/', [UnitController::class, 'store'])->name('store');
  Route::put('/{id}', [UnitController::class, 'update'])->name('update');
  Route::delete('/{id}', [UnitController::class, 'destroy'])->name('destroy');
  Route::get('/sections/by-division/{id}', [UnitController::class, 'getSectionsByDivision']);
});

//Employment Status
Route::prefix('/planning/employment-status')->group(function () {
  Route::get('/', [EmploymentStatusController::class, 'index'])->name('employment-status.index');
  Route::post('/store', [EmploymentStatusController::class, 'store'])->name('employment-status.store');
  Route::post('/{id}/update', [EmploymentStatusController::class, 'update'])->name('employment-status.update');
  Route::post('/{id}/delete', [EmploymentStatusController::class, 'destroy'])->name('employment-status.delete');
});

//Office Location
Route::prefix('/planning/office-location')->group(function () {
  Route::get('/', [OfficeLocationController::class, 'index'])->name('office-location.index');
  Route::post('/store', [OfficeLocationController::class, 'store'])->name('office-location.store');
  Route::post('/{id}/update', [OfficeLocationController::class, 'update'])->name('office-location.update');
  Route::post('/{id}/delete', [OfficeLocationController::class, 'destroy'])->name('office-location.delete');
});

//Qualification
Route::prefix('planning/qualification')->name('qualifications.')->group(function () {
  Route::get('/', [QualificationController::class, 'index'])->name('index');
  Route::post('/', [QualificationController::class, 'store'])->name('store');
  Route::put('/{id}', [QualificationController::class, 'update'])->name('update');
  Route::delete('/{id}', [QualificationController::class, 'destroy'])->name('destroy');
});

Route::prefix('/planning/salary-grade')->group(function () {
  Route::get('/', [SalaryGradeController::class, 'index'])->name('salary-grade.index');
  Route::post('/store', [SalaryGradeController::class, 'store'])->name('salary-grade.store');
  Route::post('/{id}/update', [SalaryGradeController::class, 'update'])->name('salary-grade.update');
  Route::post('/{id}/delete', [SalaryGradeController::class, 'destroy'])->name('salary-grade.delete');
});

// Route::get('/employee/{id}/edit', [UserController::class, 'edit'])->name('employee.edit');
// Route::put('/employee/{id}/update', [UserController::class, 'update'])->name('employee.update');
// Route::get('/employee/sections', [UserController::class, 'getSections'])->name('employee.sections');


Route::prefix('planning/position')->group(function () {
  Route::get('/', [App\Http\Controllers\Planning\PositionController::class, 'index'])->name('position.index');
  Route::post('/store', [App\Http\Controllers\Planning\PositionController::class, 'store'])->name('position.store');
  Route::post('/{id}/update', [App\Http\Controllers\Planning\PositionController::class, 'update'])->name('position.update');
  Route::post('/{id}/delete', [App\Http\Controllers\Planning\PositionController::class, 'destroy'])->name('position.delete');
});


Route::prefix('/planning/position-level')->group(function () {
  Route::get('/', [PositionLevelController::class, 'index'])->name('position-level.index');
  Route::post('/store', [PositionLevelController::class, 'store'])->name('position-level.store');
  Route::post('/{id}/update', [PositionLevelController::class, 'update'])->name('position-level.update');
  Route::post('/{id}/delete', [PositionLevelController::class, 'destroy'])->name('position-level.delete');
});

//Parenthetical Title
Route::prefix('/planning/parenthetical-title')->group(function () {
  Route::get('/', [ParentheticalTitleController::class, 'index'])->name('parenthetical-title.index');
  Route::post('/store', [ParentheticalTitleController::class, 'store'])->name('parenthetical-title.store');
  Route::post('/{id}/update', [ParentheticalTitleController::class, 'update'])->name('parenthetical-title.update');
  Route::post('/{id}/delete', [ParentheticalTitleController::class, 'destroy'])->name('parenthetical-title.delete');
});

//vacant position
Route::get('/planning/vacant-position', [VacantPositionController::class, 'index'])->name('vacant.position');
Route::post('/planning/vacant-positions/store', [VacantPositionController::class, 'store'])->name('vacant.positions.store');


// Division Sections (used for dynamic dropdowns etc.)
Route::get('/division/{id}/sections', [DivisionController::class, 'getSections']);

// // Employee Edit/Update (can be grouped separately if needed)
// Route::get('/employee/{id}/edit', [UserController::class, 'edit'])->name('employee.edit');
// Route::prefix('/planning/list-of-employee')->name('employee.')->group(function () {
//   Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
// });

// Report Generation
Route::prefix('planning')->group(function () {
  Route::get('/reports', [ReportController::class, 'index'])->name('planning.reports');
  Route::get('/reports/export', [ReportController::class, 'export'])->name('planning.reports.export');
});

//Jo Requests
Route::prefix('planning')->name('planning.')->group(function () {
  Route::get('jo-requests', [JoRequestController::class, 'index'])->name('jo-requests.index');
  Route::post('jo-requests', [JoRequestController::class, 'store'])->name('jo-requests.store');
  Route::get('jo-requests/{joRequest}/edit', [JoRequestController::class, 'edit'])->name('jo-requests.edit');
  Route::patch('jo-requests/{joRequest}', [JoRequestController::class, 'update'])->name('jo-requests.update');
  Route::patch('jo-requests/{joRequest}/approve', [JoRequestController::class, 'approve'])->name('jo-requests.approve');
  Route::patch('jo-requests/{joRequest}/disapprove', [JoRequestController::class, 'disapprove'])->name('jo-requests.disapprove');
  Route::get('jo-requests/{joRequest}/print', [JoRequestController::class, 'print'])->name('jo-requests.print');
});



// Positions
Route::prefix('planning')->group(function () {
  Route::resource('positions', PositionController::class);
});

// Requirements
Route::prefix('requirements')->group(function () {
  Route::get('/position/{positionId}', [RequirementController::class, 'getByPosition']);
  Route::post('/store/{positionId}', [RequirementController::class, 'store']);
  Route::put('/{id}', [RequirementController::class, 'update']);
  Route::delete('/{id}', [RequirementController::class, 'destroy']);
});

Route::prefix('planning')->group(function () {

  Route::resource('item-numbers', ItemNumberController::class);
  Route::get('/item-numbers', [ItemNumberController::class, 'index']);
  Route::post('/item-numbers', [ItemNumberController::class, 'store']);
  Route::put('/item-numbers/{id}', [ItemNumberController::class, 'update']);
  Route::delete('/item-numbers/{id}', [ItemNumberController::class, 'destroy']);
});
Route::get('/planning/item-numbers/next/{statusId}/{positionId}', [ItemNumberController::class, 'getNextNumber']);
Route::get('/planning/item-numbers/data', [ItemNumberController::class, 'getData'])->name('item-numbers.data');
Route::post('/planning/item-numbers/store', [ItemNumberController::class, 'store'])->name('item-numbers.store');
Route::get('/planning/item-numbers/next-number', [ItemNumberController::class, 'getNextNumber']);

Route::prefix('planning/unfilled-positions')->group(function () {
  Route::get('/', [UnfilledPositionsController::class, 'index'])->name('unfilled_positions.index');
  Route::get('/{id}', [UnfilledPositionsController::class, 'show'])->name('unfilled_positions.show');
  Route::get('/{id}/applicants', [UnfilledPositionsController::class, 'applicants'])->name('unfilled_positions.applicants');
  Route::post('/{id}/applicants', [UnfilledPositionsController::class, 'storeApplicant'])->name('unfilled_positions.applicants.store');
});

Route::put('/positions/{id}/update-status', [PositionController::class, 'updateStatus'])
  ->name('positions.updateStatus');

Route::prefix('planning')->group(function () {
  Route::put(
    '/applicants/{id}/update-status',
    [ApplicantController::class, 'updateStatus']
  )->name('applicants.updateStatus');
});

Route::put(
  '/itemnumbers/{id}/update-status',
  [ItemNumberController::class, 'updateStatus']
)->name('itemnumbers.updateStatus');


Route::get('/item-numbers/{id}/print', [ItemNumberController::class, 'print'])->name('itemNumbers.print');


// Routes for CPR forms
Route::prefix('forms')->group(function () {

  // Standard CRUD routes for CPR
  Route::resource('cpr', CprController::class);

  // Optional: if you want a separate explicit route for create
  // Route::get('cpr/create', [CprController::class, 'create'])->name('forms.cpr.create');

  // Optional: route for printing a CPR record
  Route::get('cpr/{cpr}/print', [CprController::class, 'print'])->name('forms.cpr.print');

  // Optional: route for viewing CPR details
  Route::get('cpr/{cpr}', [CprController::class, 'show'])->name('forms.cpr.show');


  // Optional: route for editing a CPR record
  // This is already included in resource routes as 'cpr/{cpr}/edit'
});

Route::prefix('forms')->name('forms.')->group(function () {
  Route::resource('cpr', CprController::class);
});

Route::prefix('forms/cpr/employee')->name('employee.')->group(function () {
  Route::get('/', [CprEmployeeController::class, 'index'])->name('index');
  Route::post('/', [CprEmployeeController::class, 'store'])->name('store');
  Route::delete('/{id}', [CprEmployeeController::class, 'destroy'])->name('destroy');
});

//PAS
Route::prefix('/pas/fundsource')->group(function () {
  Route::get('/', [FundSourceController::class, 'index'])->name('fundsource.index');
  Route::post('/store', [FundSourceController::class, 'store'])->name('fundsource.store');
  Route::post('/{id}/update', [FundSourceController::class, 'update'])->name('fundsource.update');
  Route::post('/{id}/delete', [FundSourceController::class, 'destroy'])->name('fundsource.delete');
});

Route::prefix('/pas/tax')->group(function () {
  Route::get('/', [TaxController::class, 'index'])->name('tax.index');
  Route::post('/store', [TaxController::class, 'store'])->name('tax.store');
  Route::post('/{id}/update', [TaxController::class, 'update'])->name('tax.update');
  Route::post('/{id}/delete', [TaxController::class, 'destroy'])->name('tax.delete');
});

Route::resource('/pas/payroll', PayrollController::class);
Route::resource('/pas/employeeslist', EmployeesListController::class);
// Route::resource('/pas/leavecredits', LeaveCreditsController::class);

// Route::get('pas/leavecredits', [LeaveCreditsController::class, 'index'])->name('leavecredits.index');
// Route::get('pas/leavecredits/auto-generate', [LeaveCreditsController::class, 'autoGenerate'])->name('leavecredits.auto-generate');
Route::prefix('/pas/leavecredits')->group(function () {
  Route::get('/', [LeaveCreditsController::class, 'index'])->name('leavecredits.index');
  Route::get('/leavecredits/auto-generate', [LeaveCreditsController::class, 'autoGenerate'])->name('leavecredits.auto-generate');
});



// Route::get('pas/importpayroll', [ImportPayroll::class, 'importpayroll'])->name('importpayroll');


Route::resource('/pas/importpayroll', ImportPayrollController::class);


Route::get('/employee/sections', [SectionController::class, 'getByDivision'])
  ->name('employee.sections');

// Out Slip list/dashboard
Route::get('/forms/outslips', [OutSlipController::class, 'index'])->name('outslips.index');
// Out Slip form page
Route::get('/forms/outslip/form', [OutSlipController::class, 'create'])->name('outslips.create');
// Store Out Slip submission
Route::post('/forms/outslips', [OutSlipController::class, 'store'])->name('outslips.store');
// Approve / Reject actions
Route::post('/forms/outslips/{id}/approve', [OutSlipController::class, 'approve'])->name('outslips.approve');
Route::post('/forms/outslips/{id}/reject', [OutSlipController::class, 'reject'])->name('outslips.reject');
Route::get('/forms/outslips/{id}/print', [OutSlipController::class, 'print'])->name('outslips.print');


// Leaveform page
Route::get('/forms/leaves/form', [LeaveController::class, 'create'])->name('leaves.create');
// Store Leave submission
Route::post('/forms/leaves', [LeaveController::class, 'store'])->name('leaves.store');
// Approve / Reject actions
Route::post('/forms/leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
Route::post('/forms/leaves/{id}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
Route::get('/forms/leaves/{id}/print', [LeaveController::class, 'print'])->name('leaves.print');

Route::get('/forms/leaves/{id}', [LeaveController::class, 'show'])->name('leaves.show');
// Edit leave form
Route::get('/forms/leaves/{id}/edit', [LeaveController::class, 'edit'])->name('leaves.edit');
// Delete a leave
Route::delete('/forms/leaves/{id}', [LeaveController::class, 'destroy'])->name('leaves.destroy'); // Update leave
Route::put('/forms/leaves/{id}', [LeaveController::class, 'update'])->name('leaves.update');
Route::get('/forms/leaves', [LeaveController::class, 'index'])->name('forms.leaves.index');
// Route to print leave slip
Route::get('/leaves/print/{id}', [App\Http\Controllers\LeaveController::class, 'print'])->name('leaves.print');




// pages
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// authentication
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-forgot-password-basic');
Route::post('/auth/register-basic', [RegisterBasic::class, 'store'])->name('register.store');
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::post('/auth/login-basic', [LoginBasic::class, 'store'])->name('login.store');
Route::get('/auth/otp', [LoginBasic::class, 'showOtpForm'])->name('otp.form');
Route::post('/auth/otp', [LoginBasic::class, 'verifyOtp'])->name('otp.verify');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth'])->group(function () {
  Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');
});

Route::get('/portfolio', [Analytics::class, 'index'])->name('portfolio');

// cards
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');


// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons
Route::get('/icons/icons-ri', [RiIcons::class, 'index'])->name('icons-ri');

// form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// learnings
Route::get('/learning/listofTrainings', [LearningDev::class, 'index'])->name('listofTrainings');
Route::get('/learning/trainings', [Trainings::class, 'index'])->name('trainings');
Route::get('/learning/calendar', [CalendarController::class, 'index'])->name('calendar'); // for calendar view
Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events'); // JSON for FullCalendar
Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('learning.calendar.events');
Route::get('/learning/scholarship', [ScholarshipController::class, 'index'])->name('scholarship.index');
Route::post('/learning/scholarship', [ScholarshipController::class, 'store'])->name('scholarships.store');
Route::post('/scholarships/{id}/status', [ScholarshipController::class, 'updateStatus'])->name('scholarships.status');
Route::get('/learning/events', [EventsController::class, 'index'])->name('events');       // for events list page

Route::post('/events', [EventsController::class, 'store'])->name('events.store');
Route::post('/events/{id}/status', [EventsController::class, 'updateStatus'])->name('events.updateStatus');
Route::get('/learning/trainings', [CourseController::class, 'index']);
Route::post('/courses/store', [CourseController::class, 'store'])->name('courses.store');
Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');


//PAS

// Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
// Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');

// Route::get('/pas/import_payroll', [ImportPayroll::class, 'index'])->name('import_payroll');
// Route::get('/pas/summary_of_lates', [SummaryofLates::class, 'index'])->name('summary_of_lates');
// Route::get('/pas/payroll', [Payroll::class, 'index'])->name('payroll');
// Route::get('/pas/tax', [Tax::class, 'index'])->name('tax');
// Route::get('/pas/deductions', [Deductions::class, 'index'])->name('deductions');
// Route::get('/pas/leavecredits', [LeaveCredits::class, 'index'])->name('leavecredits');
// Route::get('/pas/reports', [Reports::class, 'index'])->name('reports');

Route::prefix('pas')->group(function () {
  Route::resource('fundsource', FundSourceController::class);
});




//HR FRANS WELFARE
Route::prefix('welfare')->group(function () {
  Route::view('/dashboardwelfare', 'content.welfare.dashboardwelfare')->name('welfare.dashboard');

  // Memorandum routes
  Route::get('/memorandum', [MemorandumController::class, 'index'])->name('welfare.memorandum');
  Route::get('/memorandum/{id}', [MemorandumController::class, 'show'])->name('memorandums.show');
  Route::put('/memorandum/{id}', [MemorandumController::class, 'update'])->name('memorandum.update');
  Route::post('/memorandum', [MemorandumController::class, 'store'])->name('memorandums.store');
  Route::delete('/memorandum/{id}', [MemorandumController::class, 'destroy'])->name('memorandums.destroy');

  // Other pages
  Route::view('/awardees', 'content.welfare.awardees')->name('welfare.awardees');
  Route::view('/overview', 'content.welfare.overview')->name('welfare.overview');
  Route::view('/character', 'content.welfare.character')->name('welfare.character');
  Route::view('/monitoring', 'content.welfare.monitoring')->name('welfare.monitoring');
});
