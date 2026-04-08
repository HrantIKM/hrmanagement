<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Meeting\MeetingRequest;
use App\Http\Requests\Meeting\MeetingSearchRequest;
use App\Models\Meeting\Enums\MeetingStatus;
use App\Models\Meeting\MeetingSearch;
use App\Models\Meeting\Meeting;
use App\Services\Meeting\MeetingService;
use App\Contracts\Meeting\IMeetingRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class MeetingController extends BaseController
{
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
}
