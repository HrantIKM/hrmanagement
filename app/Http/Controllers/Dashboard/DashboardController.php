<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\RoleAndPermission\Enums\RoleType;
use App\Models\Attendance\Attendance;
use App\Models\Department\Department;
use App\Models\LeaveRequest\LeaveRequest;
use App\Models\Meeting\Meeting;
use App\Models\Project\Project;
use App\Models\Skill\Skill;
use App\Models\Task\Task;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends BaseController
{
    public function index(): View
    {
        $activities = collect();
        if (auth()->user()?->hasRole(RoleType::ADMIN)) {
            $activities = Activity::query()
                ->latest('id')
                ->limit(20)
                ->get();
        }

        $departmentHeadcount = Department::query()
            ->leftJoin('users', 'users.department_id', '=', 'departments.id')
            ->groupBy('departments.id', 'departments.name')
            ->pluck(DB::raw('COUNT(users.id) as cnt'), 'departments.name')
            ->toArray();

        $projectProgress = Project::query()
            ->select(['name', 'status'])
            ->limit(8)
            ->get()
            ->map(function ($project) {
                $percentage = match ($project->status) {
                    'completed' => 100,
                    'active' => 65,
                    'planning' => 20,
                    default => 40,
                };

                return [
                    'name' => $project->name,
                    'percentage' => $percentage,
                ];
            });

        $skillHeatmap = Skill::query()
            ->leftJoin('skill_user', 'skills.id', '=', 'skill_user.skill_id')
            ->groupBy('skills.id', 'skills.name')
            ->orderByDesc(DB::raw('COUNT(skill_user.user_id)'))
            ->limit(10)
            ->pluck(DB::raw('COUNT(skill_user.user_id) as cnt'), 'skills.name')
            ->toArray();

        $taskStatusBreakdown = Task::query()
            ->select('status', DB::raw('COUNT(*) as cnt'))
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        $leaveStatusBreakdown = LeaveRequest::query()
            ->select('status', DB::raw('COUNT(*) as cnt'))
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        $meetingStatusBreakdown = Meeting::query()
            ->select('status', DB::raw('COUNT(*) as cnt'))
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        $monthlyAttendanceHours = collect(range(5, 0))
            ->mapWithKeys(function (int $monthsAgo) {
                $from = Carbon::now()->subMonths($monthsAgo)->startOfMonth();
                $to = $from->copy()->endOfMonth();
                $hours = (float) Attendance::query()
                    ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
                    ->sum('total_hours');

                return [$from->format('M Y') => round($hours, 1)];
            })
            ->merge([Carbon::now()->format('M Y') => round((float) Attendance::query()
                ->whereBetween('date', [Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->endOfMonth()->toDateString()])
                ->sum('total_hours'), 1)])
            ->toArray();

        $topContributors = User::query()
            ->leftJoin('timesheets', 'timesheets.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.first_name',
                'users.last_name',
                DB::raw('COALESCE(SUM(timesheets.duration_minutes), 0) as mins')
            )
            ->groupBy('users.id', 'users.first_name', 'users.last_name')
            ->orderByDesc('mins')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $name = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?: "User #{$row->id}";

                return [
                    'name' => $name,
                    'hours' => round(((int) $row->mins) / 60, 1),
                ];
            });

        return $this->dashboardView('dashboard', [
            'activities' => $activities,
            'departmentHeadcount' => $departmentHeadcount,
            'projectProgress' => $projectProgress,
            'skillHeatmap' => $skillHeatmap,
            'taskStatusBreakdown' => $taskStatusBreakdown,
            'leaveStatusBreakdown' => $leaveStatusBreakdown,
            'meetingStatusBreakdown' => $meetingStatusBreakdown,
            'monthlyAttendanceHours' => $monthlyAttendanceHours,
            'topContributors' => $topContributors,
        ]);
    }
}
