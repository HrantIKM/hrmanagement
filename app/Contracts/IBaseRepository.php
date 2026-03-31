<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IBaseRepository
{
    public function getInstance(): Model;

    public function create(array $data): Model;

    public function insert(array $data): bool;

    public function find(int $id, array $with = [], bool $throw = true): Model;

    public function findUpdate(int $id, array $data): bool;

    public function findOrFail(int|string $id): Model;

    public function firstOrFailByUUID(string $uuid): Model;

    public function firstOrFailByToken(string $token): Model;

    public function firstBySlug(string $slug): ?Model;

    public function all(): Collection;

    public function get(?array $columns = null): Collection;

    public function getWith(array $with = []): Collection;

    public function getWhereIn(array $whereIn): Collection;

    public function getWhere(array $where): Collection;

    public function firstWhere(array $where): ?Model;

    public function getForSelect(string $column = 'name', string $key = 'id'): Collection;

    public function getForSelectMl(string $column = 'name', string $key = 'id'): Collection;

    public function update(int $id, array $data): Model;

    public function updateOrCreate(array $whereData, array $data): Model;

    public function updateIn(array $ids, array $data): int;

    public function updateWhere(array $whereData, array $data): int;

    public function destroy(int|string $id): int;

    public function saveMl(Model $model, array $mlsData): void;
}
