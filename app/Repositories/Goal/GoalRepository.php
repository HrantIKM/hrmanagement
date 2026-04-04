<?php

namespace App\Repositories\Goal;

use App\Contracts\Goal\IGoalRepository;
use App\Repositories\BaseRepository;
use App\Models\Goal\Goal;

class GoalRepository extends BaseRepository implements IGoalRepository
{
    public function __construct(Goal $model)
    {
        parent::__construct($model);
    }
}
