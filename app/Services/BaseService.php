<?php

namespace App\Services;

use App\Contracts\IBaseRepository;
use App\Services\File\FileService;
use App\Services\File\FileTempService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    protected IBaseRepository $repository;

    protected FileService $fileService;

    /**
     * Function to create or update model.
     */
    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        return DB::transaction(function () use ($data, $id) {
            return $this->createOrUpdateWithoutTransaction($data, $id);
        });
    }

    /**
     * Function to create or update model without transaction.
     */
    public function createOrUpdateWithoutTransaction(array $data, ?int $id = null): Model
    {
        $model = $id
            ? $this->repository->update($id, $data)
            : $this->repository->create($data);

        // Ml
        if (isset($data['ml'])) {
            $this->repository->saveMl($model, $data['ml']);
        }

        // Files
        if ($model->hasFilesData()) {
            $this->fileService()->storeFile($model, $data);
        }

        return $model;
    }

    /**
     * Function to return view data.
     */
    public function getViewData(?int $id = null): array
    {
        // Create Mode
        if ($id === null) {
            $model = $this->repository->getInstance();

            return [
                $model::getClassNameCamelCase() => $this->repository->getInstance(),
            ];
        }

        // Edit Mode
        $model = $this->repository->find($id);
        $variableKey = $model::getClassNameCamelCase();

        $data = [
            $variableKey => $model,
        ];

        if ($model->mls) {
            $data["{$variableKey}Ml"] = $model->mls->keyBy('lng_code');
        }

        return $data;
    }

    /**
     * Function to delete model.
     */
    public function delete(int $id): void
    {
        $model = $this->repository->find($id);
        if ($model->hasShowStatus()) {
            $this->repository->softDelete($id);
        } else {
            if ($model->hasFilesData()) {
                $this->fileService()->deleteModelFile($model);
            }

            $this->repository->destroy($id);
        }
    }

    /**
     * Function to get FileService class.
     */
    private function fileService(): FileTempService
    {
        return app()->make(FileTempService::class);
    }
}
