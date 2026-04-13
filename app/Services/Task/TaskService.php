<?php

namespace App\Services\Task;

use App\Contracts\Project\IProjectRepository;
use App\Contracts\Task\ITaskRepository;
use App\Contracts\User\IUserRepository;
use App\Models\Task\Enums\TaskPriority;
use App\Models\Task\Enums\TaskStatus;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

class TaskService extends BaseService
{
    public function __construct(
        ITaskRepository $repository,
        protected IProjectRepository $projectRepository,
        protected IUserRepository $userRepository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        if ($id) {
            $task = $this->repository->find($id, ['project', 'user']);
        } else {
            $task = $this->repository->getInstance();
        }

        return [
            'task' => $task,
            'projects' => $this->projectRepository->getForSelect(),
            'users' => $this->userRepository->getForSelect(),
            'taskPriorityOptions' => collect(TaskPriority::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('task.priority.' . $v)]),
            'taskStatusOptions' => collect(TaskStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('task.status.' . $v)]),
        ];
    }

    public function getIssueViewData(int $id): array
    {
        $task = $this->repository->find($id, ['project', 'user', 'timesheets.user']);

        return [
            'task' => $task,
            'timesheetDefaultDate' => Carbon::now()->format('Y-m-d'),
        ];
    }
}
