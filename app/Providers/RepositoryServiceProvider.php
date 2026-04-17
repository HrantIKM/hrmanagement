<?php

namespace App\Providers;

use App\Contracts\Attendance\IAttendanceRepository;
use App\Contracts\Candidate\ICandidateRepository;
use App\Contracts\Department\IDepartmentRepository;
use App\Contracts\File\IFileRepository;
use App\Contracts\Goal\IGoalRepository;
use App\Contracts\Holiday\IHolidayRepository;
use App\Contracts\LeaveRequest\ILeaveRequestRepository;
use App\Contracts\LeaveBalance\ILeaveBalanceRepository;
use App\Contracts\Meeting\IMeetingRepository;
use App\Contracts\Payslip\IPayslipRepository;
use App\Contracts\Position\IPositionRepository;
use App\Contracts\Project\IProjectRepository;
use App\Contracts\Review\IReviewRepository;
use App\Contracts\Room\IRoomRepository;
use App\Contracts\Role\IRoleRepository;
use App\Contracts\Salary\ISalaryRepository;
use App\Contracts\Skill\ISkillRepository;
use App\Contracts\Task\ITaskRepository;
use App\Contracts\Timesheet\ITimesheetRepository;
use App\Contracts\User\IUserRepository;
use App\Contracts\Vacancy\IVacancyRepository;
use App\Repositories\Attendance\AttendanceRepository;
use App\Repositories\Candidate\CandidateRepository;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\File\FileRepository;
use App\Repositories\Goal\GoalRepository;
use App\Repositories\Holiday\HolidayRepository;
use App\Repositories\LeaveRequest\LeaveRequestRepository;
use App\Repositories\LeaveBalance\LeaveBalanceRepository;
use App\Repositories\Meeting\MeetingRepository;
use App\Repositories\Payslip\PayslipRepository;
use App\Repositories\Position\PositionRepository;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\Review\ReviewRepository;
use App\Repositories\Room\RoomRepository;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Salary\SalaryRepository;
use App\Repositories\Skill\SkillRepository;
use App\Repositories\Task\TaskRepository;
use App\Repositories\Timesheet\TimesheetRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Vacancy\VacancyRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array|string[]
     */
    public array $singletons = [
        IUserRepository::class => UserRepository::class,
        IRoleRepository::class => RoleRepository::class,
        IFileRepository::class => FileRepository::class,
        IDepartmentRepository::class => DepartmentRepository::class,
        IPositionRepository::class => PositionRepository::class,
        ISkillRepository::class => SkillRepository::class,
        IVacancyRepository::class => VacancyRepository::class,
        ICandidateRepository::class => CandidateRepository::class,
        IProjectRepository::class => ProjectRepository::class,
        ITaskRepository::class => TaskRepository::class,
        ITimesheetRepository::class => TimesheetRepository::class,
        IGoalRepository::class => GoalRepository::class,
        IHolidayRepository::class => HolidayRepository::class,
        ILeaveBalanceRepository::class => LeaveBalanceRepository::class,
        ILeaveRequestRepository::class => LeaveRequestRepository::class,
        IAttendanceRepository::class => AttendanceRepository::class,
        IMeetingRepository::class => MeetingRepository::class,
        IReviewRepository::class => ReviewRepository::class,
        IRoomRepository::class => RoomRepository::class,
        ISalaryRepository::class => SalaryRepository::class,
        IPayslipRepository::class => PayslipRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
