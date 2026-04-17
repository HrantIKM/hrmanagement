<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\Concerns\AuthorizesDashboardEmployeeAccess;
use App\Http\Requests\Room\RoomRequest;
use App\Http\Requests\Room\RoomSearchRequest;
use App\Models\Meeting\Meeting;
use App\Models\Room\RoomSearch;
use App\Models\Room\Room;
use App\Services\Room\RoomService;
use App\Contracts\Room\IRoomRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class RoomController extends BaseController
{
    use AuthorizesDashboardEmployeeAccess;

    public function __construct(
        RoomService $service,
        IRoomRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        $total = Room::count();
        $withMeetings = Room::has('meetings')->count();
        $meetingRows = Meeting::whereNotNull('room_id')->count();

        return $this->dashboardView('room.index', [
            'createRoute' => $this->dashboardUserIsAdmin() ? route('dashboard.rooms.create') : null,
            'roomStats' => [
                'total' => $total,
                'with_meetings' => $withMeetings,
                'unused' => max(0, $total - $withMeetings),
                'meeting_bookings' => $meetingRows,
            ],
        ]);
    }

    public function getListData(RoomSearchRequest $request): array
    {
        $searcher = new RoomSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        $this->abortUnlessAdminCanManageHrRecords();

        return $this->dashboardView(
            view: 'room.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(RoomRequest $request): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.rooms.index'),
        ]);
    }

    public function show(Room $room): View
    {
        return $this->dashboardView(
            view: 'room.form',
            vars: $this->service->getViewData($room->id),
            viewMode: 'show'
        );
    }

    public function edit(Room $room): View
    {
        $this->abortUnlessAdminCanManageHrRecords();

        return $this->dashboardView(
            view: 'room.form',
            vars: $this->service->getViewData($room->id),
            viewMode: 'edit'
        );
    }

    public function update(RoomRequest $request, Room $room): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated(), $room->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.rooms.index'),
        ]);
    }

    public function destroy(Room $room): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        // If deleting other data except model use service
        // $this->service->delete($room->id);
        $this->repository->destroy($room->id);

        return $this->sendOkDeleted();
    }
}
