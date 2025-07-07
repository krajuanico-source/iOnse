<?php

//New Routes
// use App\Http\Controllers\planning\Division;
// use App\Http\Controllers\layouts\SummaryofLates;
// use App\Http\Controllers\layouts\Payroll;
// use App\Http\Controllers\layouts\Tax;
// use App\Http\Controllers\layouts\Deductions;
// use App\Http\Controllers\layouts\LeaveCredits;
use App\Http\Controllers\layouts\Reports;
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
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
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
use App\Http\Controllers\Planning\QualificationController ;
use App\Http\Controllers\Planning\SalaryGradeController;
use App\Http\Controllers\Planning\PositionLevelController;
use App\Http\Controllers\Planning\ParentheticalTitleController;
use App\Http\Controllers\Planning\ReportController;
use App\Http\Controllers\Planning\JoRequestController;


//PAS
use App\Http\Controllers\pas\FundSourceController;
use App\Http\Controllers\pas\PayrollController;
use App\Http\Controllers\pas\TaxController;

use App\Http\Controllers\Api\UserController;
use App\Models\Section;
// Login Page
// Redirect root URL to login page
Route::get('/', function () {
  return redirect()->route('auth-login-basic');
});

Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');

// Dashboard (you can protect this later with auth middleware)
Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');

// Dashboard (you can protect this later with auth middleware)
Route::get('/dashboard/dashboards-analytics', [Analytics::class, 'index'])->name('dashboards-analytics');

//-------------------------------------------------------START OF PLANNING-----------------------------------------------------------

Route::prefix('planning')->group(function () {
    Route::get('/list-of-employee', [UserController::class, 'index'])->name('planning.list-of-employee');
    Route::get('/import-form', [UserController::class, 'showImportForm'])->name('planning.import-form');
    Route::post('/import', [UserController::class, 'importEmployees'])->name('planning.import');});

Route::get('/planning/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Registration Form Routes
Route::prefix('planning')->group(function () {
    Route::get('/registration-form', [RegistrationForm::class, 'index'])->name('registration-form.index');
    Route::post('/list-of-employee', [UserController::class, 'store'])->name('employee.store');
});

// Show Registration Form (from API\UserController)
Route::get('/planning/registration-form', [\App\Http\Controllers\Api\UserController::class, 'create'])->name('employee.registration-form');
    
// Employee List & Profile Routes
Route::get('/planning/list-of-employee', [UserController::class, 'bladeIndex'])->name('employee.view-blade');
Route::get('/planning/list-of-employee/{id}/view', [UserController::class, 'showEmployeeView'])->name('employee.show-view');
Route::get('/planning/list-of-employee/{id}', [UserController::class, 'show'])->name('employee.view');

// Filtered Employee Lists
Route::get('/planning/active-employees', [\App\Http\Controllers\Planning\UserController::class, 'active'])->name('employee.active');
Route::get('/planning/retired-employees', [\App\Http\Controllers\Planning\UserController::class, 'retired'])->name('employee.retired');
Route::get('/planning/resigned-employees', [\App\Http\Controllers\Planning\UserController::class, 'resigned'])->name('employee.resigned');

// Import Employees
Route::prefix('planning')->name('planning.')->group(function () {
    Route::get('/import-form', [\App\Http\Controllers\Api\UserController::class, 'showImportForm'])->name('import-form');
    Route::post('/import', [\App\Http\Controllers\Api\UserController::class, 'importEmployees'])->name('import');});


// Division Management
Route::prefix('planning/division')->name('division.')->group(function () {
    Route::get('/', [DivisionController::class, 'index'])->name('index');
    Route::post('/store', [DivisionController::class, 'store'])->name('store');
    Route::post('/{id}/update', [DivisionController::class, 'update'])->name('update');
    Route::post('/{id}/delete', [DivisionController::class, 'destroy'])->name('delete');});

//Section Management
Route::prefix('/planning/section')->group(function () {
  Route::get('/', [SectionController::class, 'index'])->name('section.index');
  Route::post('/store', [SectionController::class, 'store'])->name('section.store');
  Route::post('/{id}/update', [SectionController::class, 'update'])->name('section.update');
  Route::post('/{id}/delete', [SectionController::class, 'destroy'])->name('section.delete');});

//Unit Management
Route::prefix('planning/unit')->name('unit.')->group(function () {
    Route::get('/', [UnitController::class, 'index'])->name('index');
    Route::post('/', [UnitController::class, 'store'])->name('store');
    Route::put('/{id}', [UnitController::class, 'update'])->name('update');
    Route::delete('/{id}', [UnitController::class, 'destroy'])->name('destroy');
    Route::get('/sections/by-division/{id}', [UnitController::class, 'getSectionsByDivision']);});

//Employment Status
Route::prefix('/planning/employment-status')->group(function () {
  Route::get('/', [EmploymentStatusController::class, 'index'])->name('employment-status.index');
  Route::post('/store', [EmploymentStatusController::class, 'store'])->name('employment-status.store');
  Route::post('/{id}/update', [EmploymentStatusController::class, 'update'])->name('employment-status.update');
  Route::post('/{id}/delete', [EmploymentStatusController::class, 'destroy'])->name('employment-status.delete');});

//Office Location  
Route::prefix('/planning/office-location')->group(function () {
  Route::get('/', [OfficeLocationController::class, 'index'])->name('office-location.index');
  Route::post('/store', [OfficeLocationController::class, 'store'])->name('office-location.store');
  Route::post('/{id}/update', [OfficeLocationController::class, 'update'])->name('office-location.update');
  Route::post('/{id}/delete', [OfficeLocationController::class, 'destroy'])->name('office-location.delete');});

//Qualification  
Route::prefix('planning/qualification')->name('qualifications.')->group(function () {
    Route::get('/', [QualificationController::class, 'index'])->name('index');
    Route::post('/', [QualificationController::class, 'store'])->name('store');
    Route::put('/{id}', [QualificationController::class, 'update'])->name('update');
    Route::delete('/{id}', [QualificationController::class, 'destroy'])->name('destroy');});

//Salary Grade
Route::prefix('/planning/salary-grade')->group(function () {
  Route::get('/', [SalaryGradeController::class, 'index'])->name('salary-grade.index');
  Route::post('/store', [SalaryGradeController::class, 'store'])->name('salary-grade.store');
  Route::post('/{id}/update', [SalaryGradeController::class, 'update'])->name('salary-grade.update');
  Route::post('/{id}/delete', [SalaryGradeController::class, 'destroy'])->name('salary-grade.delete');});

// Employee Management
Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('{id}/update', [UserController::class, 'update'])->name('update');
    Route::get('sections', [UserController::class, 'getSections'])->name('sections');});

//Position
Route::prefix('planning/position')->group(function () {
  Route::get('/', [App\Http\Controllers\Planning\PositionController::class, 'index'])->name('position.index');
  Route::post('/store', [App\Http\Controllers\Planning\PositionController::class, 'store'])->name('position.store');
  Route::post('/{id}/update', [App\Http\Controllers\Planning\PositionController::class, 'update'])->name('position.update');
  Route::post('/{id}/delete', [App\Http\Controllers\Planning\PositionController::class, 'destroy'])->name('position.delete');});

//Position Level
Route::prefix('/planning/position-level')->group(function () {
  Route::get('/', [PositionLevelController::class, 'index'])->name('position-level.index');
  Route::post('/store', [PositionLevelController::class, 'store'])->name('position-level.store');
  Route::post('/{id}/update', [PositionLevelController::class, 'update'])->name('position-level.update');
  Route::post('/{id}/delete', [PositionLevelController::class, 'destroy'])->name('position-level.delete');});

  //Parenthetical Title
Route::prefix('/planning/parenthetical-title')->group(function () {
  Route::get('/', [ParentheticalTitleController::class, 'index'])->name('parenthetical-title.index');
  Route::post('/store', [ParentheticalTitleController::class, 'store'])->name('parenthetical-title.store');
  Route::post('/{id}/update', [ParentheticalTitleController::class, 'update'])->name('parenthetical-title.update');
  Route::post('/{id}/delete', [ParentheticalTitleController::class, 'destroy'])->name('parenthetical-title.delete');});

//vacant position 
Route::get('/planning/vacant-position', [VacantPositionController::class, 'index'])->name('vacant.position');
Route::post('/planning/vacant-positions/store', [VacantPositionController::class, 'store'])->name('vacant.positions.store');

// Division Sections (used for dynamic dropdowns etc.)
Route::get('/division/{id}/sections', [DivisionController::class, 'getSections']);

// Employee Edit/Update (can be grouped separately if needed)
Route::get('/employee/{id}/edit', [UserController::class, 'edit'])->name('employee.edit');
Route::prefix('/planning/list-of-employee')->name('employee.')->group(function () {
  Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');});

// Report Generation
Route::prefix('planning')->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('planning.reports');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('planning.reports.export');});

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

//-------------------------------------------------------END OF PLANNING-----------------------------------------------------------


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

// User Interface
// Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
// Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
// Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
// Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
// Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
// Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
// Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
// Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
// Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
// Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
// Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
// Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
// Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
// Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
// Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
// Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
// Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
// Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
// Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

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
Route::get('/learning/scholarship', [ScholarshipController::class, 'index'])->name('scholarship.index');
Route::post('/learning/scholarship', [ScholarshipController::class, 'store'])->name('scholarships.store');
Route::post('/scholarships/{id}/status', [ScholarshipController::class, 'updateStatus'])->name('scholarships.status');
Route::get('/learning/events', [EventsController::class, 'index'])->name('events');       // for events list page
Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events'); // JSON for FullCalendar
Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('learning.calendar.events');

Route::post('/events', [EventsController::class, 'store'])->name('events.store'); // For form submission
Route::post('/events/store', [EventsController::class, 'store'])->name('events.store');
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


// HR WELFAREEEE - FRANS
Route::get('/welfare', [Analytics::class, 'index'])->name('listofnomination');
