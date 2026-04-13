<?php

use App\Http\Controllers\Dashboard\ArticleController;
use App\Http\Controllers\Dashboard\AttendanceController;
use App\Http\Controllers\Dashboard\CandidateController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DepartmentController;
use App\Http\Controllers\Dashboard\FileController;
use App\Http\Controllers\Dashboard\GoalController;
use App\Http\Controllers\Dashboard\HolidayController;
use App\Http\Controllers\Dashboard\LeaveRequestController;
use App\Http\Controllers\Dashboard\LeaveBalanceController;
use App\Http\Controllers\Dashboard\MeetingController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\PayslipController;
use App\Http\Controllers\Dashboard\PositionController;
use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\Dashboard\ReviewController;
use App\Http\Controllers\Dashboard\RoomController;
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
    Route::get('task-board', [TaskController::class, 'board'])->name('tasks.board');
    Route::get('task-board/data', [TaskController::class, 'boardData'])->name('tasks.boardData');
    Route::put('task-board/{task}/move', [TaskController::class, 'move'])->name('tasks.move');

    Route::resource('timesheets', TimesheetController::class);
    Route::get('timesheets/dataTable/get-list', [TimesheetController::class, 'getListData'])->name('timesheets.getListData');

    Route::resource('goals', GoalController::class);
    Route::get('goals/dataTable/get-list', [GoalController::class, 'getListData'])->name('goals.getListData');

    Route::get('reviews/my', [ReviewController::class, 'myIndex'])->name('reviews.mine');
    Route::resource('reviews', ReviewController::class);
    Route::get('reviews/dataTable/get-list', [ReviewController::class, 'getListData'])->name('reviews.getListData');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->whereUuid('id')->name('notifications.read');

    Route::resource('meetings', MeetingController::class);
    Route::get('meetings/dataTable/get-list', [MeetingController::class, 'getListData'])->name('meetings.getListData');
    Route::get('meetings-calendar', [MeetingController::class, 'calendar'])->name('meetings.calendar');
    Route::get('meetings-calendar/feed', [MeetingController::class, 'calendarFeed'])->name('meetings.calendarFeed');
    Route::put('meetings/{meeting}/move', [MeetingController::class, 'move'])->name('meetings.move');
    Route::post('meetings/{meeting}/action-items', [MeetingController::class, 'createActionItems'])->name('meetings.createActionItems');

    Route::post('leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::post('leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::get('leave-requests/dataTable/get-list', [LeaveRequestController::class, 'getListData'])->name('leave-requests.getListData');

    Route::resource('leave-balances', LeaveBalanceController::class);
    Route::get('leave-balances/dataTable/get-list', [LeaveBalanceController::class, 'getListData'])->name('leave-balances.getListData');

    Route::resource('attendances', AttendanceController::class);
    Route::get('attendances/dataTable/get-list', [AttendanceController::class, 'getListData'])->name('attendances.getListData');
    Route::get('attendances/calendar-feed', [AttendanceController::class, 'calendarFeed'])->name('attendances.calendarFeed');
    Route::post('attendances/clock-in', [AttendanceController::class, 'clockIn'])->name('attendances.clockIn');
    Route::post('attendances/clock-out', [AttendanceController::class, 'clockOut'])->name('attendances.clockOut');

    Route::get('payslips/my', [PayslipController::class, 'myIndex'])->name('payslips.mine');
    Route::resource('payslips', PayslipController::class);
    Route::get('payslips/dataTable/get-list', [PayslipController::class, 'getListData'])->name('payslips.getListData');
    Route::get('payslips/{payslip}/download', [PayslipController::class, 'download'])->name('payslips.download');
    Route::get('payslips/export/csv', [PayslipController::class, 'exportCsv'])->name('payslips.exportCsv');
    Route::get('payslips/export/xlsx', [PayslipController::class, 'exportExcel'])->name('payslips.exportExcel');
});

// Admin-only catalog & HR records
Route::group(['middleware' => ["role:$roleAdmin"]], function () {
    Route::resource('users', UserController::class);
    Route::get('users/dataTable/get-list', [UserController::class, 'getListData'])->name('users.getListData');
    Route::get('users/export/csv', [UserController::class, 'exportCsv'])->name('users.exportCsv');
    Route::get('users/export/xlsx', [UserController::class, 'exportExcel'])->name('users.exportExcel');

    Route::resource('departments', DepartmentController::class);
    Route::get('departments/dataTable/get-list', [DepartmentController::class, 'getListData'])->name('departments.getListData');

    Route::resource('positions', PositionController::class);
    Route::get('positions/dataTable/get-list', [PositionController::class, 'getListData'])->name('positions.getListData');

    Route::resource('skills', SkillController::class);
    Route::get('skills/dataTable/get-list', [SkillController::class, 'getListData'])->name('skills.getListData');

    Route::resource('holidays', HolidayController::class);
    Route::get('holidays/dataTable/get-list', [HolidayController::class, 'getListData'])->name('holidays.getListData');

    Route::resource('rooms', RoomController::class);
    Route::get('rooms/dataTable/get-list', [RoomController::class, 'getListData'])->name('rooms.getListData');

    Route::resource('vacancies', VacancyController::class);
    Route::get('vacancies/dataTable/get-list', [VacancyController::class, 'getListData'])->name('vacancies.getListData');

    Route::get('candidates/{candidate}/resume', [CandidateController::class, 'resume'])->name('candidates.resume');
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
