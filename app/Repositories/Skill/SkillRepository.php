<?php

namespace App\Repositories\Skill;

use App\Contracts\Skill\ISkillRepository;
use App\Models\Skill\Skill;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class SkillRepository extends BaseRepository implements ISkillRepository
{
    public function __construct(Skill $model)
    {
        parent::__construct($model);
    }

    public function getForSelect(string $column = 'name', string $key = 'id'): Collection
    {
        return $this->model->newQuery()
            ->with('department')
            ->orderBy('department_id')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function (Skill $skill) use ($key, $column) {
                $label = $skill->department
                    ? $skill->department->name . ' — ' . $skill->{$column}
                    : $skill->{$column};

                return [$skill->{$key} => $label];
            });
    }
}
