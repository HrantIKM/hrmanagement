<?php

namespace App\Services\Timesheet;

use App\Contracts\Task\ITaskRepository;
use App\Contracts\Timesheet\ITimesheetRepository;
use App\Contracts\User\IUserRepository;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TimesheetService extends BaseService
{
    public function __construct(
        ITimesheetRepository $repository,
        protected ITaskRepository $taskRepository,
        protected IUserRepository $userRepository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null, array $defaults = []): array
    {
        if ($id) {
            $timesheet = $this->repository->find($id, ['user', 'task']);
        } else {
            $timesheet = $this->repository->getInstance();
            $timesheet->fill([
                'user_id' => $defaults['user_id'] ?? null,
                'task_id' => $defaults['task_id'] ?? null,
                'date' => $defaults['date'] ?? null,
            ]);
        }

        return [
            'timesheet' => $timesheet,
            'tasks' => $this->taskRepository->getForSelect(),
            'users' => $this->userRepository->getForSelect(),
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        foreach (['task_id', 'start_time', 'end_time', 'duration_minutes', 'note'] as $key) {
            if (array_key_exists($key, $data) && $data[$key] === '') {
                $data[$key] = null;
            }
        }

        if (array_key_exists('date', $data) && $data['date'] === '') {
            $data['date'] = null;
        }

        if (empty($data['duration_minutes']) && !empty($data['start_time']) && !empty($data['end_time']) && !empty($data['date'])) {
            try {
                $start = Carbon::parse($data['date'] . ' ' . $data['start_time']);
                $end = Carbon::parse($data['date'] . ' ' . $data['end_time']);
                if ($end->greaterThanOrEqualTo($start)) {
                    $data['duration_minutes'] = $start->diffInMinutes($end);
                }
            } catch (\Throwable) {
            }
        }

        return parent::createOrUpdate($data, $id);
    }
}
