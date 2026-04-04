<?php

namespace App\Services\Project;

use App\Contracts\Project\IProjectRepository;
use App\Contracts\User\IUserRepository;
use App\Models\Project\Enums\ProjectStatus;
use App\Services\BaseService;
use App\Services\File\FileTempService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjectService extends BaseService
{
    public function __construct(
        IProjectRepository $repository,
        protected IUserRepository $userRepository,
        protected FileTempService $fileTempService
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $projectUserIds = null;

        if ($id) {
            $project = $this->repository->find($id, ['icon', 'users']);
            $projectUserIds = $project->users->pluck('id')->all();
        } else {
            $project = $this->repository->getInstance();
        }

        return [
            'project' => $project,
            'users' => $this->userRepository->getForSelect(),
            'projectUserIds' => $projectUserIds,
            'projectStatusOptions' => collect(ProjectStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('project.status.' . $v)]),
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        $userIds = array_key_exists('user_ids', $data) ? $data['user_ids'] : null;
        unset($data['user_ids']);

        foreach (['start_date', 'end_date'] as $key) {
            if (array_key_exists($key, $data) && $data[$key] === '') {
                $data[$key] = null;
            }
        }

        return DB::transaction(function () use ($data, $id, $userIds) {
            $project = $id
                ? $this->repository->update($id, $data)
                : $this->repository->create($data);

            if ($userIds !== null) {
                $project->users()->sync($userIds);
            }

            $this->fileTempService->storeFile($project->refresh(), $data);

            return $project->refresh();
        });
    }
}
