<?php

namespace App\Services\Room;

use App\Contracts\Room\IRoomRepository;
use App\Services\BaseService;

class RoomService extends BaseService
{
    public function __construct(
        IRoomRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        return [
            'room' => $id !== null
                ? $this->repository->find($id)
                : $this->repository->getInstance(),
        ];
    }
}
