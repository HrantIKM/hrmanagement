<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Task\ITaskRepository;
use App\Contracts\Timesheet\ITimesheetRepository;
use App\Contracts\User\IUserRepository;
use App\Http\Controllers\Dashboard\Concerns\AuthorizesDashboardEmployeeAccess;
use App\Http\Requests\Timesheet\TimesheetRequest;
use App\Http\Requests\Timesheet\TimesheetSearchRequest;
use App\Models\Task\Task;
use App\Models\Timesheet\Timesheet;
use App\Models\Timesheet\TimesheetSearch;
use App\Services\Timesheet\TimesheetService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimesheetController extends BaseController
{
    use AuthorizesDashboardEmployeeAccess;

    public function __construct(
        TimesheetService $service,
        ITimesheetRepository $repository,
        protected ITaskRepository $taskRepository,
        protected IUserRepository $userRepository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        $base = $this->scopedTimesheetsQuery();
        $total = (clone $base)->count();
        $totalMinutes = (int) (clone $base)->sum('duration_minutes');
        $hoursLogged = round($totalMinutes / 60, 1);
        $thisMonth = (clone $base)
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->count();
        $tasksTouched = (int) (clone $base)->whereNotNull('task_id')->distinct()->count('task_id');

        return $this->dashboardView('timesheet.index', [
            'tasks' => $this->taskRepository->getForSelect(),
            'users' => $this->userRepository->getForSelect(),
            'createRoute' => route('dashboard.timesheets.create'),
            'timesheetStats' => [
                'total' => $total,
                'hours' => $hoursLogged,
                'this_month' => $thisMonth,
                'tasks_touched' => $tasksTouched,
            ],
        ]);
    }

    protected function scopedTimesheetsQuery(): Builder
    {
        $query = Timesheet::query();
        if (!$this->dashboardUserIsAdmin()) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public function getListData(TimesheetSearchRequest $request): array
    {
        $searcher = new TimesheetSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(Request $request): View
    {
        $defaults = [
            'task_id' => $request->filled('task_id') ? (int) $request->input('task_id') : null,
            'user_id' => $request->filled('user_id') ? (int) $request->input('user_id') : null,
            'date' => $request->input('date'),
        ];

        if (!$this->dashboardUserIsAdmin()) {
            $defaults['user_id'] = (int) auth()->id();
        }

        return $this->dashboardView(
            view: 'timesheet.form',
            vars: $this->service->getViewData(defaults: $defaults)
        );
    }

    public function store(TimesheetRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (!$this->dashboardUserIsAdmin()) {
            $data['user_id'] = (int) auth()->id();
            if (!empty($data['task_id'])) {
                $task = Task::query()->find($data['task_id']);
                abort_unless(
                    $task && (int) $task->user_id === (int) auth()->id(),
                    403
                );
            }
        }

        $this->service->createOrUpdate($data);

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.timesheets.index'),
        ]);
    }

    public function show(Timesheet $timesheet): View
    {
        $this->abortUnlessAdminOrOwnsUserId($timesheet->user_id);

        return $this->dashboardView(
            view: 'timesheet.form',
            vars: $this->service->getViewData($timesheet->id),
            viewMode: 'show'
        );
    }

    public function edit(Timesheet $timesheet): View
    {
        $this->abortUnlessAdminOrOwnsUserId($timesheet->user_id);

        return $this->dashboardView(
            view: 'timesheet.form',
            vars: $this->service->getViewData($timesheet->id),
            viewMode: 'edit'
        );
    }

    public function update(TimesheetRequest $request, Timesheet $timesheet): JsonResponse
    {
        $this->abortUnlessAdminOrOwnsUserId($timesheet->user_id);

        $data = $request->validated();
        if (!$this->dashboardUserIsAdmin()) {
            $data['user_id'] = (int) auth()->id();
            if (!empty($data['task_id'])) {
                $task = Task::query()->find($data['task_id']);
                abort_unless(
                    $task && (int) $task->user_id === (int) auth()->id(),
                    403
                );
            }
        }

        $this->service->createOrUpdate($data, $timesheet->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.timesheets.index'),
        ]);
    }

    public function destroy(Timesheet $timesheet): JsonResponse
    {
        $this->abortUnlessAdminOrOwnsUserId($timesheet->user_id);

        $this->service->delete($timesheet->id);

        return $this->sendOkDeleted();
    }
}
