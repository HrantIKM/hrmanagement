<?php

namespace App\Services\LeaveRequest;

use App\Contracts\LeaveRequest\ILeaveRequestRepository;
use App\Contracts\User\IUserRepository;
use App\Models\LeaveRequest\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest\Enums\LeaveRequestType;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LeaveRequestService extends BaseService
{
    public function __construct(
        ILeaveRequestRepository $repository,
        protected IUserRepository $userRepository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $leaveRequest = $id
            ? $this->repository->find($id)
            : $this->repository->getInstance();

        return [
            'leaveRequest' => $leaveRequest,
            'users' => $this->userRepository->getForSelect(),
            'leaveRequestTypeOptions' => collect(LeaveRequestType::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('leaveRequest.type.' . $v)]),
            'leaveRequestStatusOptions' => collect(LeaveRequestStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('leaveRequest.status.' . $v)]),
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        return DB::transaction(function () use ($data, $id) {
            return $id
                ? $this->repository->update($id, $data)
                : $this->repository->create($data);
        });
    }
}
