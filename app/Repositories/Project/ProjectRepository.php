<?php

namespace App\Repositories\Project;

use App\Contracts\Project\IProjectRepository;
use App\Repositories\BaseRepository;
use App\Models\Project\Project;

class ProjectRepository extends BaseRepository implements IProjectRepository
{
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }
}
