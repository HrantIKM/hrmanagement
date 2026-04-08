<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\LeaveRequest\LeaveRequestRequest;
use App\Http\Requests\LeaveRequest\LeaveRequestSearchRequest;
use App\Models\LeaveRequest\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest\Enums\LeaveRequestType;
use App\Models\LeaveRequest\LeaveRequestSearch;
use App\Models\LeaveRequest\LeaveRequest;
use App\Services\LeaveRequest\LeaveRequestService;
use App\Contracts\LeaveRequest\ILeaveRequestRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class LeaveRequestController extends BaseController
{
    public function __construct(
        LeaveRequestService $service,
        ILeaveRequestRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('leave-request.index', [
            'leaveRequestTypes' => collect(LeaveRequestType::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('leaveRequest.type.' . $v)]),
            'leaveRequestStatuses' => collect(LeaveRequestStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('leaveRequest.status.' . $v)]),
        ]);
    }

    public function getListData(LeaveRequestSearchRequest $request): array
    {
        $searcher = new LeaveRequestSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'leave-request.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(LeaveRequestRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.leave-requests.index')
        ]);
    }

    public function show(LeaveRequest $leaveRequest): View
    {
        return $this->dashboardView(
            view: 'leave-request.form',
            vars: $this->service->getViewData($leaveRequest->id),
            viewMode: 'show'
        );
    }

    public function edit(LeaveRequest $leaveRequest): View
    {
        return $this->dashboardView(
            view: 'leave-request.form',
            vars: $this->service->getViewData($leaveRequest->id),
            viewMode: 'edit'
        );
    }

    public function update(LeaveRequestRequest $request, LeaveRequest $leaveRequest): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $leaveRequest->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.leave-requests.index')
        ]);
    }

    public function destroy(LeaveRequest $leaveRequest): JsonResponse
    {
        // If deleting other data except model use service
        // $this->service->delete($leaveRequest->id);
        $this->repository->destroy($leaveRequest->id);

        return $this->sendOkDeleted();
    }
}
