<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

abstract class Search
{
    protected int $perPage = 25;

    protected int $start = 0;

    protected array $filters = [];

    protected array $order = [
        ['sort_by' => 'id', 'dir' => 'desc'],
    ];

    /**
     * @var string[]
     */
    protected array $orderables = [
        'id',
    ];

    public function __construct(mixed $data)
    {
        if (!empty($data['f'])) {
            $this->filters = $data['f'];
        }

        if (!empty($data['order'])) {
            $this->order = $data['order'];
        }

        if (!empty($data['start'])) {
            $this->start = $data['start'];
        }

        if (!empty($data['perPage'])) {
            $this->perPage = $data['perPage'];
        }
    }

    abstract protected function query(): Builder;

    protected function setOrdering(Builder $query): void
    {
        if (in_array($this->order['sort_by'] ?? '', $this->orderables)) {
            $query->orderBy($this->order['sort_by'], $this->order['sort_desc']);
        }
    }

    protected function setLimits(Builder $query): void
    {
        $query
            ->skip($this->start)
            ->take($this->perPage);
    }

    public function search(): Collection|array
    {
        $query = $this->query();
        $this->setOrdering($query);
        $this->setLimits($query);

        return $this->setReturnData($query);
    }

    public function setReturnData(Builder $query): mixed
    {
        return $query->get();
    }

    protected function totalCount(): int
    {
        return 0;
    }

    public function filteredCount(): int
    {
        return $this->query()->count();
    }
}
