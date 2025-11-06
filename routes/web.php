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

//Planning
use App\Http\Controllers\planning\DashboardController;
use App\Http\Controllers\planning\ListofEmployee;
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
use App\Http\Controllers\ApplicantController;


use App\Http\Controllers\AddressController;


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

Route::middleware(['auth'])->group(function() {
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

Route::prefix('planning')->group(function () {
  Route::get('/list-of-employee', [UserController::class, 'index'])->name('planning.list-of-employee');
  Route::get('/import-form', [UserController::class, 'showImportForm'])->name('planning.import-form');
  Route::post('/import', [UserController::class, 'importEmployees'])->name('planning.import');
});

Route::get('/planning/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// // Registration Form Routes
// Route::prefix('planning')->group(function () {
//   Route::get('/registration-form', [RegistrationForm::class, 'index'])->name('registration-form.index');
//   Route::post('/list-of-employee', [UserController::class, 'store'])->name('employee.store');
// });

// // Show Registration Form (from API\UserController)
// Route::get('/planning/registration-form', [\App\Http\Controllers\Api\UserController::class, 'create'])->name('employee.registration-form');
// Route::get('/planning/section', [UserController::class, 'getSections'])->name('planning.section');

// Employee List & Profile Routes
Route::get('/planning/list-of-employee', [UserController::class, 'bladeIndex'])->name('employee.view-blade');
// Edit employee form
Route::get('/planning/list-of-employee/{id}/edit', [UserController::class, 'edit'])
  ->name('employee.edit');
// Delete employee
Route::delete('/planning/list-of-employee/{id}', [UserController::class, 'destroy'])
  ->name('employee.delete');
Route::get('/planning/list-of-employee/{id}/view', [UserController::class, 'show'])->name('employee.show-view');
Route::get('/planning/list-of-employee/{id}', [UserController::class, 'show'])->name('employee.view');

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
  // List of all unfilled positions
  Route::get('/', [UnfilledPositionsController::class, 'index'])->name('unfilled_positions.index');

  // Show specific position details
  Route::get('/{id}', [UnfilledPositionsController::class, 'show'])->name('unfilled_positions.show');

  // 🆕 Dedicated page for applicants per position
  Route::get('/{id}/applicants', [UnfilledPositionsController::class, 'applicants'])
    ->name('unfilled_positions.applicants');

  // POST to add a new applicant
  Route::post('/{id}/applicants', [UnfilledPositionsController::class, 'storeApplicant'])
    ->name('unfilled_positions.applicants.store');
});

// Update applicant status
Route::put('/planning/applicants/{id}/update-status', [ApplicantController::class, 'updateStatus'])
  ->name('applicants.updateStatus');

Route::get('/item-numbers/{id}/print', [ItemNumberController::class, 'print'])->name('itemNumbers.print');


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
