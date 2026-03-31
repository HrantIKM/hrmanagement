<?php

namespace App\Services\Profile;

use App\Contracts\User\IUserRepository;
use App\Services\BaseService;
use App\Services\File\FileTempService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileService extends BaseService
{
    public function __construct(
        IUserRepository $repository,
        FileTempService $fileService,
    ) {
        $this->repository = $repository;
        $this->fileService = $fileService;
    }

    public function update(array $data, ?int $id = null): Model
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->repository->update($id, $data);
            $this->fileService->storeFile($user, $data);

            if (isset($data['new_password'])) {
                $user->fill([
                    'password' => Hash::make($data['new_password']),
                ])->save();
            }

            return $user;
        });
    }
}
