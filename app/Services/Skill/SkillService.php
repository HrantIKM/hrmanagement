<?php

namespace App\Services\Skill;

use App\Contracts\Skill\ISkillRepository;
use App\Models\Department\Department;
use App\Models\Department\Enums\DepartmentCode;
use App\Models\Skill\Enums\SkillCategory;
use App\Services\BaseService;

class SkillService extends BaseService
{
    public function __construct(
        ISkillRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $skill = $id
            ? $this->repository->find($id)
            : $this->repository->getInstance();

        return [
            'skill' => $skill,
            'skillCategoryOptions' => collect(SkillCategory::ALL)
                ->mapWithKeys(fn(string $v) => [$v => __('skill.category.' . $v)]),
            'departmentOptions' => Department::query()
                ->whereIn('name', DepartmentCode::values())
                ->orderBy('name')
                ->get()
                ->mapWithKeys(fn(Department $d) => [$d->id => $d->name]),
        ];
    }
}
