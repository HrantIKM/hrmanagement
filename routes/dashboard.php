<?php

use App\Http\Controllers\Dashboard\ArticleController;
use App\Http\Controllers\Dashboard\AttendanceController;
use App\Http\Controllers\Dashboard\CandidateController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DepartmentController;
use App\Http\Controllers\Dashboard\FileController;
use App\Http\Controllers\Dashboard\GoalController;
use App\Http\Controllers\Dashboard\LeaveRequestController;
use App\Http\Controllers\Dashboard\MeetingController;
use App\Http\Controllers\Dashboard\PayslipController;
use App\Http\Controllers\Dashboard\PositionController;
use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\Dashboard\ReviewController;
use App\Http\Controllers\Dashboard\SalaryController;
use App\Http\Controllers\Dashboard\SkillController;
use App\Http\Controllers\Dashboard\TaskController;
use App\Http\Controllers\Dashboard\TimesheetController;
use App\Http\Controllers\Dashboard\User\ProfileController;
use App\Http\Controllers\Dashboard\User\UserController;
use App\Http\Controllers\Dashboard\VacancyController;
use App\Models\RoleAndPermission\Enums\RoleType;
use Illuminate\Support\Facades\Route;

$roleAdmin = RoleType::ADMIN;
$rolesAdminOrUser = RoleType::ADMIN . '|' . RoleType::USER;

Route::get('/', [DashboardController::class, 'index'])->name('index');

// Files
Route::group(['prefix' => 'files', 'as' => 'files.'], function () {
    Route::delete('delete/{id}', [FileController::class, 'delete'])->whereUuid('id')->name('delete');
    Route::post('store-temp-file', [FileController::class, 'storeTempFile'])->name('storeTempFile');
});

// Translations
Route::controller(Barryvdh\TranslationManager\Controller::class)->as('translation.')->group(function () {
    Route::get('/translations', 'getIndex')->name('manager');
    Route::get('/translations/view/{group?}', 'getView')->name('group');
});

// Employee self-service + admin (lists scoped in *Search models; mutations checked in controllers)
Route::group(['middleware' => ["role:$rolesAdminOrUser"]], function () {
    Route::resource('projects', ProjectController::class);
    Route::get('projects/dataTable/get-list', [ProjectController::class, 'getListData'])->name('projects.getListData');

    Route::resource('tasks', TaskController::class);
    Route::get('tasks/dataTable/get-list', [TaskController::class, 'getListData'])->name('tasks.getListData');

    Route::resource('timesheets', TimesheetController::class);
    Route::get('timesheets/dataTable/get-list', [TimesheetController::class, 'getListData'])->name('timesheets.getListData');

    Route::resource('goals', GoalController::class);
    Route::get('goals/dataTable/get-list', [GoalController::class, 'getListData'])->name('goals.getListData');

    Route::resource('reviews', ReviewController::class);
    Route::get('reviews/dataTable/get-list', [ReviewController::class, 'getListData'])->name('reviews.getListData');

    Route::resource('meetings', MeetingController::class);
    Route::get('meetings/dataTable/get-list', [MeetingController::class, 'getListData'])->name('meetings.getListData');

    Route::resource('leave-requests', LeaveRequestController::class);
    Route::get('leave-requests/dataTable/get-list', [LeaveRequestController::class, 'getListData'])->name('leave-requests.getListData');

    Route::resource('attendances', AttendanceController::class);
    Route::get('attendances/dataTable/get-list', [AttendanceController::class, 'getListData'])->name('attendances.getListData');

    Route::resource('payslips', PayslipController::class);
    Route::get('payslips/dataTable/get-list', [PayslipController::class, 'getListData'])->name('payslips.getListData');
});

// Admin-only catalog & HR records
Route::group(['middleware' => ["role:$roleAdmin"]], function () {
    Route::resource('users', UserController::class);
    Route::get('users/dataTable/get-list', [UserController::class, 'getListData'])->name('users.getListData');

    Route::resource('departments', DepartmentController::class);
    Route::get('departments/dataTable/get-list', [DepartmentController::class, 'getListData'])->name('departments.getListData');

    Route::resource('positions', PositionController::class);
    Route::get('positions/dataTable/get-list', [PositionController::class, 'getListData'])->name('positions.getListData');

    Route::resource('skills', SkillController::class);
    Route::get('skills/dataTable/get-list', [SkillController::class, 'getListData'])->name('skills.getListData');

    Route::resource('vacancies', VacancyController::class);
    Route::get('vacancies/dataTable/get-list', [VacancyController::class, 'getListData'])->name('vacancies.getListData');

    Route::resource('candidates', CandidateController::class);
    Route::get('candidates/dataTable/get-list', [CandidateController::class, 'getListData'])->name('candidates.getListData');

    Route::resource('salaries', SalaryController::class);
    Route::get('salaries/dataTable/get-list', [SalaryController::class, 'getListData'])->name('salaries.getListData');
});

// Articles
Route::resource('articles', ArticleController::class);
Route::get('articles/dataTable/get-list', [ArticleController::class, 'getListData'])->name('articles.getListData');

// Profile
Route::controller(ProfileController::class)->as('profile.')->group(function () {
    Route::get('profile', 'index')->name('index');
    Route::put('profile/{id}', 'update')->whereNumber('id')->name('update');
});

// Vue Example
Route::view('vue-example', 'components.dashboard.vue-example.index')->name('vue-example.index');
