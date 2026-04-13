<?php

namespace App\Services\Attendance;

use App\Contracts\Attendance\IAttendanceRepository;
use App\Contracts\User\IUserRepository;
use App\Models\Attendance\Enums\AttendanceStatus;
use App\Models\Attendance\Attendance;
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
        $data = $this->applyAttendanceLogic($data);

        return DB::transaction(function () use ($data, $id) {
            return $id
                ? $this->repository->update($id, $data)
                : $this->repository->create($data);
        });
    }

    public function clockIn(int $userId): Attendance
    {
        $now = Carbon::now();
        $attendance = Attendance::query()->firstOrNew([
            'user_id' => $userId,
            'date' => $now->toDateString(),
        ]);

        if (!$attendance->exists || !$attendance->clock_in) {
            $attendance->clock_in = $now;
            $attendance->status = $this->isLate($now) ? AttendanceStatus::LATE : AttendanceStatus::PRESENT;
            $attendance->clock_out = null;
            $attendance->total_hours = null;
            $attendance->save();
        }

        return $attendance->refresh();
    }

    public function clockOut(int $userId): ?Attendance
    {
        $attendance = Attendance::query()
            ->where('user_id', $userId)
            ->whereDate('date', Carbon::today())
            ->first();

        if (!$attendance || !$attendance->clock_in) {
            return null;
        }

        $attendance->clock_out = Carbon::now();
        $attendance->total_hours = $this->calculateHoursWithLunchBreak(
            Carbon::parse($attendance->clock_in),
            Carbon::parse($attendance->clock_out)
        );
        $attendance->save();

        return $attendance->refresh();
    }

    private function applyAttendanceLogic(array $data): array
    {
        $clockIn = !empty($data['clock_in']) ? Carbon::parse($data['clock_in']) : null;
        $clockOut = !empty($data['clock_out']) ? Carbon::parse($data['clock_out']) : null;

        if ($clockIn) {
            $data['status'] = $this->isLate($clockIn) ? AttendanceStatus::LATE : ($data['status'] ?? AttendanceStatus::PRESENT);
        }

        if ($clockIn && $clockOut) {
            $data['total_hours'] = $this->calculateHoursWithLunchBreak($clockIn, $clockOut);
        } else {
            $data['total_hours'] = null;
        }

        return $data;
    }

    private function isLate(Carbon $clockIn): bool
    {
        $threshold = $clockIn->copy()->startOfDay()->setHour(9)->setMinute(5);

        return $clockIn->greaterThan($threshold);
    }

    private function calculateHoursWithLunchBreak(Carbon $clockIn, Carbon $clockOut): float
    {
        $minutes = max(0, $clockIn->diffInMinutes($clockOut) - 60);

        return round($minutes / 60, 2);
    }
}
