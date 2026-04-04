<?php

namespace App\Services\User;

use App\Contracts\Department\IDepartmentRepository;
use App\Contracts\Position\IPositionRepository;
use App\Contracts\Role\IRoleRepository;
use App\Contracts\Skill\ISkillRepository;
use App\Contracts\User\IUserRepository;
use App\Models\User\Enums\EmploymentStatus;
use App\Services\BaseService;
use App\Services\File\FileTempService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function __construct(
        IUserRepository $repository,
        FileTempService $fileService,
        protected IRoleRepository $roleRepository,
        protected IDepartmentRepository $departmentRepository,
        protected IPositionRepository $positionRepository,
        protected ISkillRepository $skillRepository
    ) {
        $this->repository = $repository;
        $this->fileService = $fileService;
    }

    public function getViewData(?int $id = null): array
    {
        $user = null;
        $userRoleIds = null;
        $userSkillIds = null;

        if ($id) {
            $user = $this->repository->find($id, ['department', 'position', 'skills', 'roles']);
            $userRoleIds = $user->roles->pluck('id')->all();
            $userSkillIds = $user->skills->pluck('id')->all();
        }

        $employmentStatusOptions = collect(EmploymentStatus::ALL)
            ->mapWithKeys(fn (string $value) => [$value => __('user.employment_status.' . $value)]);

        return [
            'roles' => $this->roleRepository->getForSelect(),
            'departments' => $this->departmentRepository->getForSelect(),
            'positions' => $this->positionRepository->getForSelect(),
            'skills' => $this->skillRepository->getForSelect(),
            'employmentStatusOptions' => $employmentStatusOptions,
            'user' => $user ?? $this->repository->getInstance(),
            'userRoleIds' => $userRoleIds,
            'userSkillIds' => $userSkillIds,
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        $skillIds = array_key_exists('skill_ids', $data) ? $data['skill_ids'] : null;
        unset($data['skill_ids']);

        $roleIds = $data['role_ids'] ?? [];
        unset($data['role_ids']);

        foreach (['department_id', 'position_id', 'hire_date', 'salary'] as $nullableKey) {
            if (array_key_exists($nullableKey, $data) && $data[$nullableKey] === '') {
                $data[$nullableKey] = null;
            }
        }

        unset($data['password_confirmation']);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        return DB::transaction(function () use ($id, $data, $roleIds, $skillIds) {
            $user = $id
                ? $this->repository->update($id, $data)
                : $this->repository->create($data);

            $user->syncRolesData($roleIds);
            if ($skillIds !== null) {
                $user->skills()->sync($skillIds);
            }

            $this->fileService->storeFile($user, $data);

            return $user->refresh();
        });
    }
}
