<?php

namespace App\Services\LeaveBalance;

use App\Contracts\LeaveBalance\ILeaveBalanceRepository;
use App\Contracts\User\IUserRepository;
use App\Models\LeaveBalance\LeaveBalance;
use App\Services\BaseService;
use Illuminate\Support\Collection;

class LeaveBalanceService extends BaseService
{
    public function __construct(
        ILeaveBalanceRepository $repository,
        protected IUserRepository $userRepository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $leaveBalance = $id ? $this->repository->find($id) : $this->repository->getInstance();

        return [
            'leaveBalance' => $leaveBalance,
            'users' => $this->userRepository->getForSelect(),
        ];
    }

    /**
     * Balances for the dashboard summary (newest years first).
     */
    public function balancesForUser(int $userId): Collection
    {
        return LeaveBalance::query()
            ->where('user_id', $userId)
            ->orderByDesc('year')
            ->get();
    }
}
