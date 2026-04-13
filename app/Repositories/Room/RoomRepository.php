<?php

namespace App\Repositories\Room;

use App\Contracts\Room\IRoomRepository;
use App\Models\Room\Room;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class RoomRepository extends BaseRepository implements IRoomRepository
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }

    public function getForSelect(string $column = 'name', string $key = 'id'): Collection
    {
        return parent::getForSelect($column, $key);
    }
}
