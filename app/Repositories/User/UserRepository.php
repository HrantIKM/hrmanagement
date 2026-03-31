<?php

namespace App\Repositories\User;

use App\Contracts\User\IUserRepository;
use App\Models\User\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getForSelect(string $column = 'name', string $key = 'id'): Collection
    {
        return $this->model
            ->selectRaw('CONCAT(first_name, " ", last_name) as full_name, id')
            ->pluck('full_name', $key);
    }
}
