<?php

namespace App\Repositories\Skill;

use App\Contracts\Skill\ISkillRepository;
use App\Repositories\BaseRepository;
use App\Models\Skill\Skill;

class SkillRepository extends BaseRepository implements ISkillRepository
{
    public function __construct(Skill $model)
    {
        parent::__construct($model);
    }
}
