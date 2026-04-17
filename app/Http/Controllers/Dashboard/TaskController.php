<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Project\IProjectRepository;
use App\Contracts\Task\ITaskRepository;
use App\Contracts\User\IUserRepository;
use App\Http\Controllers\Dashboard\Concerns\AuthorizesDashboardEmployeeAccess;
use App\Http\Requests\Task\TaskRequest;
use App\Http\Requests\Task\TaskSearchRequest;
use App\Models\Task\Enums\TaskPriority;
use App\Models\Task\Enums\TaskStatus;
use App\Models\Task\Task;
use App\Models\Task\TaskSearch;
use App\Services\Task\TaskService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    use AuthorizesDashboardEmployeeAccess;

    public function __construct(
        TaskService $service,
        ITaskRepository $repository,
        protected IProjectRepository $projectRepository,
        protected IUserRepository $userRepository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        $base = $this->scopedTasksQuery();
        $total = (clone $base)->count();
        $done = (clone $base)->where('status', TaskStatus::DONE)->count();
        $open = (clone $base)->where('status', '!=', TaskStatus::DONE)->count();
        $overdue = (clone $base)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->where('status', '!=', TaskStatus::DONE)
            ->count();

        return $this->dashboardView('task.index', [
            'projects' => $this->projectRepository->getForSelect(),
            'users' => $this->userRepository->getForSelect(),
            'taskPriorities' => collect(TaskPriority::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('task.priority.' . $v)]),
            'taskStatuses' => collect(TaskStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('task.status.' . $v)]),
            'createRoute' => $this->dashboardUserIsAdmin() ? route('dashboard.tasks.create') : null,
            'boardRoute' => route('dashboard.tasks.board'),
            'taskStats' => [
                'total' => $total,
                'open' => $open,
                'done' => $done,
                'overdue' => $overdue,
            ],
        ]);
    }

    protected function scopedTasksQuery(): Builder
    {
        return Task::query();
    }

    public function getListData(TaskSearchRequest $request): array
    {
        $searcher = new TaskSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        $this->abortUnlessAdminCanManageHrRecords();

        return $this->dashboardView(
            view: 'task.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.tasks.index'),
        ]);
    }

    public function show(Task $task): View
    {
        return $this->dashboardView(
            view: 'task.show',
            vars: $this->service->getIssueViewData($task->id)
        );
    }

    public function board(): View
    {
        return $this->dashboardView('task.board', [
            'taskStatusesOrdered' => [
                TaskStatus::BACKLOG,
                TaskStatus::TODO,
                TaskStatus::IN_PROGRESS,
                TaskStatus::READY_TO_TEST,
                TaskStatus::DONE,
            ],
            'canAdminManage' => $this->dashboardUserIsAdmin(),
        ]);
    }

    public function boardData(): JsonResponse
    {
        $authId = (int) auth()->id();
        $isAdmin = $this->dashboardUserIsAdmin();
        $query = Task::with(['project:id,name', 'user:id,first_name,last_name,email'])
            ->select(['id', 'title', 'description', 'status', 'priority', 'project_id', 'user_id', 'due_date']);

        $tasks = $query->orderBy('id')->get()->map(function (Task $task) use ($authId, $isAdmin) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'status_display' => $task->status_display,
                'priority' => $task->priority,
                'priority_display' => $task->priority_display,
                'project' => $task->project ? ['id' => $task->project->id, 'name' => $task->project->name] : null,
                'user' => $task->user ? ['id' => $task->user->id, 'name' => $task->user->name] : null,
                'due_date' => $task->due_date?->format('Y-m-d'),
                'can_manage_status' => $isAdmin || (int) $task->user_id === $authId,
                'can_edit' => $isAdmin || (int) $task->user_id === $authId,
                'can_delete' => $isAdmin,
            ];
        })->values();

        return response()->json($tasks);
    }

    public function move(Request $request, Task $task): JsonResponse
    {
        $this->abortUnlessAdminOrOwnsUserId($task->user_id);

        $validated = $request->validate([
            'status' => 'required|string',
        ]);

        abort_unless(in_array($validated['status'], TaskStatus::ALL, true), 422);

        $this->repository->update($task->id, [
            'status' => $validated['status'],
        ]);

        return $this->sendOkUpdated();
    }

    public function edit(Task $task): View
    {
        $this->abortUnlessAdminOrOwnsUserId($task->user_id);

        return $this->dashboardView(
            view: 'task.form',
            vars: $this->service->getViewData($task->id),
            viewMode: 'edit'
        );
    }

    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        if (!$this->dashboardUserIsAdmin()) {
            $this->abortUnlessAdminOrOwnsUserId($task->user_id);
            $data = $request->validated();
            $data['user_id'] = $task->user_id;
            $data['project_id'] = $task->project_id;
            $this->service->createOrUpdate($data, $task->id);
        } else {
            $this->service->createOrUpdate($request->validated(), $task->id);
        }

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.tasks.index'),
        ]);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->delete($task->id);

        return $this->sendOkDeleted();
    }
}
