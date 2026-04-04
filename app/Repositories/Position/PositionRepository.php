<?php

namespace App\Repositories\Position;

use App\Contracts\Position\IPositionRepository;
use App\Models\Position\Position;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class PositionRepository extends BaseRepository implements IPositionRepository
{
    public function __construct(Position $model)
    {
        parent::__construct($model);
    }

    public function getForSelect(string $column = 'title', string $key = 'id'): Collection
    {
        return parent::getForSelect($column, $key);
    }
}
