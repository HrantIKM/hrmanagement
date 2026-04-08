<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Attendance\AttendanceRequest;
use App\Http\Requests\Attendance\AttendanceSearchRequest;
use App\Models\Attendance\Enums\AttendanceStatus;
use App\Models\Attendance\AttendanceSearch;
use App\Models\Attendance\Attendance;
use App\Services\Attendance\AttendanceService;
use App\Contracts\Attendance\IAttendanceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class AttendanceController extends BaseController
{
    public function __construct(
        AttendanceService $service,
        IAttendanceRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('attendance.index', [
            'attendanceStatuses' => collect(AttendanceStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('attendance.status.' . $v)]),
        ]);
    }

    public function getListData(AttendanceSearchRequest $request): array
    {
        $searcher = new AttendanceSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'attendance.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(AttendanceRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.attendances.index')
        ]);
    }

    public function show(Attendance $attendance): View
    {
        return $this->dashboardView(
            view: 'attendance.form',
            vars: $this->service->getViewData($attendance->id),
            viewMode: 'show'
        );
    }

    public function edit(Attendance $attendance): View
    {
        return $this->dashboardView(
            view: 'attendance.form',
            vars: $this->service->getViewData($attendance->id),
            viewMode: 'edit'
        );
    }

    public function update(AttendanceRequest $request, Attendance $attendance): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $attendance->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.attendances.index')
        ]);
    }

    public function destroy(Attendance $attendance): JsonResponse
    {
        // If deleting other data except model use service
        // $this->service->delete($attendance->id);
        $this->repository->destroy($attendance->id);

        return $this->sendOkDeleted();
    }
}
