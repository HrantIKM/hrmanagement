<?php

namespace App\Repositories\Vacancy;

use App\Contracts\Vacancy\IVacancyRepository;
use App\Models\Vacancy\Vacancy;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class VacancyRepository extends BaseRepository implements IVacancyRepository
{
    public function __construct(Vacancy $model)
    {
        parent::__construct($model);
    }

    public function getForSelect(string $column = 'title', string $key = 'id'): Collection
    {
        return parent::getForSelect($column, $key);
    }
}
