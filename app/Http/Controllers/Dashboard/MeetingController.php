<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Meeting\MeetingRequest;
use App\Http\Requests\Meeting\MeetingSearchRequest;
use App\Models\Meeting\Enums\MeetingStatus;
use App\Http\Controllers\Concerns\FormatsHolidaysForCalendar;
use App\Http\Controllers\Concerns\FormatsLeaveRequestsForCalendar;
use App\Http\Controllers\Concerns\ParsesFullCalendarFeedRange;
use App\Models\Holiday\Holiday;
use App\Models\LeaveRequest\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest\LeaveRequest;
use App\Models\Meeting\MeetingSearch;
use App\Models\Meeting\Meeting;
use App\Services\Meeting\MeetingService;
use App\Contracts\Meeting\IMeetingRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MeetingController extends BaseController
{
    use FormatsHolidaysForCalendar;
    use FormatsLeaveRequestsForCalendar;
    use ParsesFullCalendarFeedRange;

    public function __construct(
        MeetingService $service,
        IMeetingRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('meeting.index', [
            'meetingStatuses' => collect(MeetingStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('meeting.status.' . $v)]),
            'createRoute' => route('dashboard.meetings.create'),
        ]);
    }

    public function getListData(MeetingSearchRequest $request): array
    {
        $searcher = new MeetingSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'meeting.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(MeetingRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.meetings.index')
        ]);
    }

    public function show(Meeting $meeting): View
    {
        return $this->dashboardView(
            view: 'meeting.form',
            vars: $this->service->getViewData($meeting->id),
            viewMode: 'show'
        );
    }

    public function edit(Meeting $meeting): View
    {
        return $this->dashboardView(
            view: 'meeting.form',
            vars: $this->service->getViewData($meeting->id),
            viewMode: 'edit'
        );
    }

    public function update(MeetingRequest $request, Meeting $meeting): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $meeting->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.meetings.index')
        ]);
    }

    public function destroy(Meeting $meeting): JsonResponse
    {
        // If deleting other data except model use service
        // $this->service->delete($meeting->id);
        $this->repository->destroy($meeting->id);

        return $this->sendOkDeleted();
    }

    public function calendar(): View
    {
        return $this->dashboardView('meeting.calendar');
    }

    public function calendarFeed(Request $request): JsonResponse
    {
        $query = Meeting::query()
            ->with(['participants:id,first_name,last_name,email', 'room:id,name'])
            ->select(['id', 'title', 'start_at', 'end_at', 'status', 'room_id', 'location']);

        if ($request->filled('start')) {
            $query->where('end_at', '>=', $request->input('start'));
        }
        if ($request->filled('end')) {
            $query->where('start_at', '<', $request->input('end'));
        }

        $meetingEvents = $query->get()->map(function (Meeting $meeting) {
            return [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'start' => optional($meeting->start_at)->toIso8601String(),
                'end' => optional($meeting->end_at)->toIso8601String(),
                'editable' => true,
                'extendedProps' => [
                    'isLeave' => false,
                    'status' => $meeting->status,
                    'room' => $meeting->room?->name,
                    'location' => $meeting->location,
                ],
            ];
        });

        $leaveQuery = LeaveRequest::query()
            ->with(['user:id,first_name,last_name,email'])
            ->where('status', LeaveRequestStatus::APPROVED)
            ->select(['id', 'user_id', 'type', 'start_date', 'end_date']);

        $visibleStart = $this->fullCalendarVisibleStartDate($request);
        $visibleEndExclusive = $this->fullCalendarVisibleEndExclusiveDate($request);

        if ($visibleStart !== null) {
            $leaveQuery->whereDate('end_date', '>=', $visibleStart);
        }
        if ($visibleEndExclusive !== null) {
            $leaveQuery->whereDate('start_date', '<', $visibleEndExclusive);
        }

        $leaveEvents = $leaveQuery->get()->map(fn (LeaveRequest $leave) => $this->leaveRequestToFullCalendarEvent($leave));

        $holidayQuery = Holiday::query()->orderBy('date');
        if ($visibleStart !== null) {
            $holidayQuery->whereDate('date', '>=', $visibleStart);
        }
        if ($visibleEndExclusive !== null) {
            $holidayQuery->whereDate('date', '<', $visibleEndExclusive);
        }

        $holidayEvents = $holidayQuery->get()->map(fn (Holiday $holiday) => $this->holidayToFullCalendarEvent($holiday));

        return response()->json($meetingEvents->concat($leaveEvents)->concat($holidayEvents)->values());
    }

    public function move(Request $request, Meeting $meeting): JsonResponse
    {
        $validated = $request->validate([
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);

        $data = array_merge($meeting->only(['title', 'description', 'room_id', 'location', 'status', 'summary']), $validated);
        $data['user_ids'] = $meeting->participants()->pluck('users.id')->all();

        $this->service->createOrUpdate($data, $meeting->id);

        return $this->sendOkUpdated();
    }

    public function createActionItems(Meeting $meeting): JsonResponse
    {
        $count = $this->service->convertMinutesToTasks($meeting);

        return response()->json([
            'status' => 'OK',
            'created_tasks_count' => $count,
            'message' => $count > 0
                ? "{$count} action item(s) converted into tasks."
                : 'No action-item lines found in meeting summary.',
        ]);
    }
}
