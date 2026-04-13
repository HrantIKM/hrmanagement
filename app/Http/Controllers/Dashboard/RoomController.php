<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Room\RoomRequest;
use App\Http\Requests\Room\RoomSearchRequest;
use App\Models\Room\RoomSearch;
use App\Models\Room\Room;
use App\Services\Room\RoomService;
use App\Contracts\Room\IRoomRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class RoomController extends BaseController
{
    public function __construct(
        RoomService $service,
        IRoomRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('room.index', [
            'createRoute' => route('dashboard.rooms.create'),
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
        return $this->dashboardView(
            view: 'room.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(RoomRequest $request): JsonResponse
    {
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
        return $this->dashboardView(
            view: 'room.form',
            vars: $this->service->getViewData($room->id),
            viewMode: 'edit'
        );
    }

    public function update(RoomRequest $request, Room $room): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $room->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.rooms.index'),
        ]);
    }

    public function destroy(Room $room): JsonResponse
    {
        // If deleting other data except model use service
        // $this->service->delete($room->id);
        $this->repository->destroy($room->id);

        return $this->sendOkDeleted();
    }
}
