<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Concerns\FormatsHolidaysForCalendar;
use App\Http\Controllers\Concerns\FormatsLeaveRequestsForCalendar;
use App\Http\Controllers\Concerns\ParsesFullCalendarFeedRange;
use App\Http\Requests\Attendance\AttendanceRequest;
use App\Http\Requests\Attendance\AttendanceSearchRequest;
use App\Models\Attendance\Enums\AttendanceStatus;
use App\Models\Attendance\AttendanceSearch;
use App\Models\Attendance\Attendance;
use App\Services\Attendance\AttendanceService;
use App\Contracts\Attendance\IAttendanceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Models\LeaveRequest\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest\LeaveRequest;
use App\Models\Meeting\Meeting;
use App\Models\Holiday\Holiday;
use App\Models\RoleAndPermission\Enums\RoleType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AttendanceController extends BaseController
{
    use FormatsHolidaysForCalendar;
    use FormatsLeaveRequestsForCalendar;
    use ParsesFullCalendarFeedRange;

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

    public function clockIn(): JsonResponse
    {
        $attendance = $this->service->clockIn((int) Auth::id());

        return response()->json([
            'status' => 'OK',
            'message' => 'Clock-in registered.',
            'attendance' => $attendance,
        ]);
    }

    public function clockOut(): JsonResponse
    {
        $attendance = $this->service->clockOut((int) Auth::id());

        if (!$attendance) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'No clock-in found for today.',
            ], 422);
        }

        return response()->json([
            'status' => 'OK',
            'message' => 'Clock-out registered.',
            'attendance' => $attendance,
        ]);
    }

    public function calendarFeed(Request $request): JsonResponse
    {
        $isAdmin = auth()->user()?->hasRole(RoleType::ADMIN);
        $userId = (int) Auth::id();

        $visibleStart = $this->fullCalendarVisibleStartDate($request);
        $visibleEndExclusive = $this->fullCalendarVisibleEndExclusiveDate($request);

        $events = [];

        $attendanceQuery = Attendance::query()->with('user:id,first_name,last_name');
        if (!$isAdmin) {
            $attendanceQuery->where('user_id', $userId);
        }
        if ($visibleStart !== null) {
            $attendanceQuery->whereDate('date', '>=', $visibleStart);
        }
        if ($visibleEndExclusive !== null) {
            $attendanceQuery->whereDate('date', '<', $visibleEndExclusive);
        }

        foreach ($attendanceQuery->get() as $attendance) {
            $events[] = [
                'id' => 'att-' . $attendance->id,
                'title' => ($isAdmin ? (($attendance->user?->name ?? 'User') . ' - ') : '') . strtoupper((string) $attendance->status),
                'start' => $attendance->date?->format('Y-m-d'),
                'allDay' => true,
                'editable' => false,
                'backgroundColor' => $attendance->status === 'late' ? '#f59e0b' : '#2563eb',
                'borderColor' => 'transparent',
                'extendedProps' => ['isAttendance' => true],
            ];
        }

        $leaveQuery = LeaveRequest::query()
            ->with(['user:id,first_name,last_name,email'])
            ->where('status', LeaveRequestStatus::APPROVED)
            ->select(['id', 'user_id', 'type', 'start_date', 'end_date']);

        if (!$isAdmin) {
            $leaveQuery->where('user_id', $userId);
        }
        if ($visibleStart !== null) {
            $leaveQuery->whereDate('end_date', '>=', $visibleStart);
        }
        if ($visibleEndExclusive !== null) {
            $leaveQuery->whereDate('start_date', '<', $visibleEndExclusive);
        }

        foreach ($leaveQuery->get() as $leave) {
            $events[] = $this->leaveRequestToFullCalendarEvent($leave);
        }

        $meetingQuery = Meeting::query();
        if (!$isAdmin) {
            $meetingQuery->whereHas('participants', fn ($q) => $q->where('users.id', $userId));
        }
        if ($request->filled('start')) {
            $meetingQuery->where('end_at', '>=', $request->input('start'));
        }
        if ($request->filled('end')) {
            $meetingQuery->where('start_at', '<', $request->input('end'));
        }

        foreach ($meetingQuery->get() as $meeting) {
            $events[] = [
                'id' => 'meeting-' . $meeting->id,
                'title' => 'Meeting: ' . $meeting->title,
                'start' => optional($meeting->start_at)->toIso8601String(),
                'end' => optional($meeting->end_at)->toIso8601String(),
                'editable' => false,
                'backgroundColor' => '#0ea5e9',
                'borderColor' => 'transparent',
                'extendedProps' => ['isMeeting' => true],
            ];
        }

        $holidayQuery = Holiday::query()->orderBy('date');
        if ($visibleStart !== null) {
            $holidayQuery->whereDate('date', '>=', $visibleStart);
        }
        if ($visibleEndExclusive !== null) {
            $holidayQuery->whereDate('date', '<', $visibleEndExclusive);
        }

        foreach ($holidayQuery->get() as $holiday) {
            $events[] = $this->holidayToFullCalendarEvent($holiday);
        }

        return response()->json($events);
    }
}
