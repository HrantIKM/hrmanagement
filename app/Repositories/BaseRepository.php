<?php

namespace App\Repositories;

use App\Contracts\IBaseRepository;
use App\Models\Base\Enums\ShowStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BaseRepository implements IBaseRepository
{
    public Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getInstance(): Model
    {
        return new $this->model();
    }

    public function create(array $data): Model
    {
        if ($this->model->defaultValues) {
            $data = [...$this->model->defaultValues, ...$data];
        }

        return $this->model->create($data);
    }

    public function insert(array $data): bool
    {
        return $this->model->insert($data);
    }

    public function find(int $id, array $with = [], bool $throw = true): Model
    {
        $model = empty($with) ? $this->model : $this->model::with($with);

        return $throw ? $model->findOrFail($id) : $model->find($id);
    }

    public function findUpdate(int $id, array $data): bool
    {
        return $this->find($id)->update($data);
    }

    public function findOrFail(int|string $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function firstOrFailByUUID(string $uuid): Model
    {
        return $this->model->where(['uuid' => $uuid])->firstOrFail();
    }

    public function firstOrFailByToken(string $token): Model
    {
        return $this->model->where(['token' => $token])->firstOrFail();
    }

    public function firstBySlug(string $slug): ?Model
    {
        return $this->model->whereSlug($slug)->first();
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function get(?array $columns = null): Collection
    {
        return $columns ? $this->model->get($columns) : $this->model->get();
    }

    public function getWith(array $with = []): Collection
    {
        return $this->model->with($with)->get();
    }

    public function getWhereIn(array $whereIn): Collection
    {
        return $this->model->whereIn('id', $whereIn)->get();
    }

    public function getWhere(array $where): Collection
    {
        return $this->model->where($where)->get();
    }

    public function firstWhere(array $where): ?Model
    {
        return $this->model->where($where)->first();
    }

    public function getForSelect(string $column = 'name', string $key = 'id'): Collection
    {
        return $this->model->pluck($column, $key);
    }

    public function getForSelectMl(string $column = 'name', string $key = 'id'): Collection
    {
        return $this->model->joinML()->pluck($column, $key);
    }

    public function update(int $id, array $data): Model
    {
        $model = $this->find($id);
        if ($model->defaultValues) {
            $data = [...$model->defaultValues, ...$data];
        }

        $model->update($data);

        return $model->refresh();
    }

    public function updateOrCreate(array $whereData, array $data): Model
    {
        return $this->model->updateOrCreate($whereData, $data);
    }

    public function updateIn(array $ids, array $data): int
    {
        return $this->model->whereIn('id', $ids)->update($data);
    }

    public function updateWhere(array $whereData, array $data): int
    {
        return $this->model->where($whereData)->update($data);
    }

    public function destroy(int|string $id): int
    {
        return $this->model->destroy($id);
    }

    public function softDelete(int $id): bool|int
    {
        $currentModel = $this->model->findOrFail($id);

        if (method_exists($currentModel, 'canDelete') && !$currentModel->canDelete()) {
            return false;
        }

        $updateData = [
            'show_status' => ShowStatus::DELETED,
        ];

        if ($this->model->hasUserInfo) {
            $updateData = $updateData + ['deleted_user_id' => Auth::id(), 'deleted_user_ip' => request()->ip()];
        }

        return $this->model->where('id', $id)->update($updateData);
    }

    public function saveMl(Model $model, array $mlsData): void
    {
        foreach ($mlsData as $lngCode => $mlData) {
            $model->mls()->updateOrCreate(
                [
                    $this->model->getForeignKey() => $model->id,
                    'lng_code' => $lngCode,
                ],
                $mlData
            );
        }
    }
}
