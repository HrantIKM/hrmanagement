<?php

namespace App\Services\Attendance;

use App\Contracts\Attendance\IAttendanceRepository;
use App\Contracts\User\IUserRepository;
use App\Models\Attendance\Enums\AttendanceStatus;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AttendanceService extends BaseService
{
    public function __construct(
        IAttendanceRepository $repository,
        protected IUserRepository $userRepository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $attendance = $id
            ? $this->repository->find($id)
            : $this->repository->getInstance();

        return [
            'attendance' => $attendance,
            'users' => $this->userRepository->getForSelect(),
            'attendanceStatusOptions' => collect(AttendanceStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('attendance.status.' . $v)]),
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        if (!empty($data['clock_in']) && !empty($data['clock_out'])) {
            $clockIn = Carbon::parse($data['clock_in']);
            $clockOut = Carbon::parse($data['clock_out']);
            $data['total_hours'] = round($clockIn->diffInMinutes($clockOut) / 60, 2);
        } else {
            $data['total_hours'] = null;
        }

        return DB::transaction(function () use ($data, $id) {
            return $id
                ? $this->repository->update($id, $data)
                : $this->repository->create($data);
        });
    }
}
