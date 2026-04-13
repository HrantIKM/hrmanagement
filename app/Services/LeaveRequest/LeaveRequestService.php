<?php

namespace App\Services\LeaveRequest;

use App\Contracts\LeaveRequest\ILeaveRequestRepository;
use App\Contracts\User\IUserRepository;
use App\Models\LeaveBalance\LeaveBalance;
use App\Models\LeaveRequest\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest\Enums\LeaveRequestType;
use App\Models\LeaveRequest\LeaveRequest;
use App\Models\RoleAndPermission\Enums\RoleType;
use App\Models\User\User;
use App\Notifications\LeaveRequestDecisionNotification;
use App\Notifications\LeaveRequestPendingNotification;
use App\Services\BaseService;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class LeaveRequestService extends BaseService
{
    public function __construct(
        ILeaveRequestRepository $repository,
        protected IUserRepository $userRepository
    ) {
        $this->repository = $repository;
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $model = $this->repository->find($id);
            if (($model->status ?? null) === LeaveRequestStatus::APPROVED) {
                $this->adjustLeaveBalanceForDates(
                    (int) $model->user_id,
                    $model->start_date,
                    $model->end_date,
                    -1
                );
            }
            parent::delete($id);
        });
    }

    public function getViewData(?int $id = null): array
    {
        $leaveRequest = $id
            ? $this->repository->find($id, ['user'])
            : $this->repository->getInstance();

        return [
            'leaveRequest' => $leaveRequest,
            'approverUsers' => $this->userRepository->getForSelect(),
            'leaveRequestTypeOptions' => collect(LeaveRequestType::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('leaveRequest.type.' . $v)]),
            'leaveRequestStatusOptions' => collect(LeaveRequestStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('leaveRequest.status.' . $v)]),
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        return DB::transaction(function () use ($data, $id) {
            $user = auth()->user();
            if (!$user?->hasRole(RoleType::ADMIN)) {
                $data['status'] = LeaveRequestStatus::PENDING;
                $data['approved_by'] = null;
            } elseif (
                ($data['status'] ?? '') === LeaveRequestStatus::APPROVED
                || ($data['status'] ?? '') === LeaveRequestStatus::REJECTED
            ) {
                if (empty($data['approved_by'])) {
                    $data['approved_by'] = $user?->id;
                }
            }

            $previous = $id !== null ? $this->repository->find($id) : null;

            $leaveRequest = $id
                ? $this->repository->update($id, $data)
                : $this->repository->create($data);

            if ($previous && $previous->status === LeaveRequestStatus::APPROVED) {
                $this->adjustLeaveBalanceForDates(
                    (int) $previous->user_id,
                    $previous->start_date,
                    $previous->end_date,
                    -1
                );
            }

            if (($leaveRequest->status ?? null) === LeaveRequestStatus::APPROVED) {
                $this->adjustLeaveBalanceForDates(
                    (int) $leaveRequest->user_id,
                    $leaveRequest->start_date,
                    $leaveRequest->end_date,
                    1
                );
            }

            $leaveRequest->load('user');
            if ($this->shouldNotifyApproversOfPendingLeave($previous, $leaveRequest)) {
                $this->notifyApproversOfPendingLeave($leaveRequest);
            }

            if ($this->shouldNotifyEmployeeOfDecision($previous, $leaveRequest)) {
                $employee = $leaveRequest->user;
                if ($employee) {
                    $employee->notify(new LeaveRequestDecisionNotification(
                        $leaveRequest,
                        (string) $leaveRequest->status
                    ));
                }
            }

            return $leaveRequest;
        });
    }

    public function applyDecision(LeaveRequest $leaveRequest, string $status): Model
    {
        if (!in_array($status, [LeaveRequestStatus::APPROVED, LeaveRequestStatus::REJECTED], true)) {
            $status = LeaveRequestStatus::REJECTED;
        }

        return $this->createOrUpdate([
            'user_id' => (int) $leaveRequest->user_id,
            'type' => $leaveRequest->type,
            'status' => $status,
            'start_date' => $leaveRequest->start_date->format('Y-m-d'),
            'end_date' => $leaveRequest->end_date->format('Y-m-d'),
            'reason' => $leaveRequest->reason,
            'approved_by' => auth()->id(),
        ], $leaveRequest->id);
    }

    private function shouldNotifyEmployeeOfDecision(?Model $previous, Model $leaveRequest): bool
    {
        $new = (string) ($leaveRequest->status ?? '');
        if ($new !== LeaveRequestStatus::APPROVED && $new !== LeaveRequestStatus::REJECTED) {
            return false;
        }

        if ($previous === null) {
            return true;
        }

        return (string) $previous->status !== $new;
    }

    private function shouldNotifyApproversOfPendingLeave(?Model $previous, Model $leaveRequest): bool
    {
        if (($leaveRequest->status ?? null) !== LeaveRequestStatus::PENDING) {
            return false;
        }

        if ($previous === null) {
            return true;
        }

        if (($previous->status ?? null) !== LeaveRequestStatus::PENDING) {
            return true;
        }

        return $this->dateUnequal($previous->start_date, $leaveRequest->start_date)
            || $this->dateUnequal($previous->end_date, $leaveRequest->end_date)
            || (string) $previous->type !== (string) $leaveRequest->type
            || (int) $previous->user_id !== (int) $leaveRequest->user_id
            || (string) ($previous->reason ?? '') !== (string) ($leaveRequest->reason ?? '');
    }

    private function dateUnequal(mixed $a, mixed $b): bool
    {
        $ca = $a instanceof CarbonInterface ? $a : ($a ? Carbon::parse($a) : null);
        $cb = $b instanceof CarbonInterface ? $b : ($b ? Carbon::parse($b) : null);
        if ($ca === null && $cb === null) {
            return false;
        }
        if ($ca === null || $cb === null) {
            return true;
        }

        return !$ca->isSameDay($cb);
    }

    private function notifyApproversOfPendingLeave(Model $leaveRequest): void
    {
        $admins = User::role(RoleType::ADMIN)->get();
        if ($admins->isEmpty()) {
            return;
        }

        Notification::send($admins, new LeaveRequestPendingNotification($leaveRequest));
    }

    /**
     * @param  int  $sign  1 to consume days, -1 to release (e.g. after edit or rejection)
     */
    private function adjustLeaveBalanceForDates(int $userId, $startDate, $endDate, int $sign): void
    {
        if (!$startDate || !$endDate || ($sign !== 1 && $sign !== -1)) {
            return;
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $year = (int) $start->format('Y');
        $days = max(0, $start->diffInDays($end) + 1);
        $delta = round($days * $sign, 2);

        $balance = LeaveBalance::query()->firstOrCreate(
            ['user_id' => $userId, 'year' => $year],
            ['total_days' => 20, 'used_days' => 0]
        );
        $balance->used_days = round(max(0, (float) $balance->used_days + $delta), 2);
        $balance->save();
    }
}
