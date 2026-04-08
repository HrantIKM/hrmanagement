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
use Illuminate\Http\JsonResponse;

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
        return $this->dashboardView('task.index', [
            'projects' => $this->projectRepository->getForSelect(),
            'users' => $this->userRepository->getForSelect(),
            'taskPriorities' => collect(TaskPriority::ALL)
                ->mapWithKeys(fn(string $v) => [$v => __('task.priority.' . $v)]),
            'taskStatuses' => collect(TaskStatus::ALL)
                ->mapWithKeys(fn(string $v) => [$v => __('task.status.' . $v)]),
            'createRoute' => $this->dashboardUserIsAdmin() ? route('dashboard.tasks.create') : null,
        ]);
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
        $this->abortUnlessAdminOrOwnsUserId($task->user_id);

        return $this->dashboardView(
            view: 'task.form',
            vars: $this->service->getViewData($task->id),
            viewMode: 'show'
        );
    }

    public function edit(Task $task): View
    {
        $this->abortUnlessAdminCanManageHrRecords();

        return $this->dashboardView(
            view: 'task.form',
            vars: $this->service->getViewData($task->id),
            viewMode: 'edit'
        );
    }

    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $task->id);

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
