<?php

namespace App\Repositories\Task;

use App\Contracts\Task\ITaskRepository;
use App\Models\Task\Task;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class TaskRepository extends BaseRepository implements ITaskRepository
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    public function getForSelect(string $column = 'title', string $key = 'id'): Collection
    {
        return parent::getForSelect($column, $key);
    }
}
