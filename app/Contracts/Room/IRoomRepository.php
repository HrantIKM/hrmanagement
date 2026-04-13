<?php

namespace App\Contracts\Room;

use Illuminate\Support\Collection;

interface IRoomRepository
{
    public function getForSelect(string $column = 'name', string $key = 'id'): Collection;
}
