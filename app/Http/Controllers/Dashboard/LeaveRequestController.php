<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\LeaveRequest\LeaveRequestRequest;
use App\Http\Requests\LeaveRequest\LeaveRequestSearchRequest;
use App\Models\LeaveRequest\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest\Enums\LeaveRequestType;
use App\Models\LeaveRequest\LeaveRequestSearch;
use App\Models\LeaveRequest\LeaveRequest;
use App\Models\RoleAndPermission\Enums\RoleType;
use App\Services\LeaveRequest\LeaveRequestService;
use App\Contracts\LeaveRequest\ILeaveRequestRepository;
use Illuminate\Database\Eloquent\Builder;
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
        $base = $this->scopedLeaveRequestsQuery();
        $total = (clone $base)->count();
        $pending = (clone $base)->where('status', LeaveRequestStatus::PENDING)->count();
        $approved = (clone $base)->where('status', LeaveRequestStatus::APPROVED)->count();
        $rejected = (clone $base)->where('status', LeaveRequestStatus::REJECTED)->count();

        return $this->dashboardView('leave-request.index', [
            'leaveRequestTypes' => collect(LeaveRequestType::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('leaveRequest.type.' . $v)]),
            'leaveRequestStatuses' => collect(LeaveRequestStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('leaveRequest.status.' . $v)]),
            'leaveRequestAdminFilters' => auth()->user()?->hasRole(RoleType::ADMIN) ?? false,
            'leaveRequestStats' => [
                'total' => $total,
                'pending' => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
            ],
        ]);
    }

    protected function scopedLeaveRequestsQuery(): Builder
    {
        $query = LeaveRequest::query();
        if (!(auth()->user()?->hasRole(RoleType::ADMIN) ?? false)) {
            $query->where('user_id', auth()->id());
        }

        return $query;
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
        $this->authorizeLeaveRequestAccess($leaveRequest);

        return $this->dashboardView(
            view: 'leave-request.form',
            vars: $this->service->getViewData($leaveRequest->id),
            viewMode: 'show'
        );
    }

    public function edit(LeaveRequest $leaveRequest): View
    {
        $this->authorizeLeaveRequestAccess($leaveRequest);
        $this->authorizeEmployeeCanMutatePendingOnly($leaveRequest);

        return $this->dashboardView(
            view: 'leave-request.form',
            vars: $this->service->getViewData($leaveRequest->id),
            viewMode: 'edit'
        );
    }

    public function update(LeaveRequestRequest $request, LeaveRequest $leaveRequest): JsonResponse
    {
        $this->authorizeLeaveRequestAccess($leaveRequest);
        $this->authorizeEmployeeCanMutatePendingOnly($leaveRequest);

        $this->service->createOrUpdate($request->validated(), $leaveRequest->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.leave-requests.index')
        ]);
    }

    public function destroy(LeaveRequest $leaveRequest): JsonResponse
    {
        $this->authorizeLeaveRequestAccess($leaveRequest);
        $this->authorizeEmployeeCanMutatePendingOnly($leaveRequest);

        $this->service->delete($leaveRequest->id);

        return $this->sendOkDeleted();
    }

    public function approve(LeaveRequest $leaveRequest): JsonResponse
    {
        abort_unless(auth()->user()?->hasRole(RoleType::ADMIN), 403);
        $this->service->applyDecision($leaveRequest, LeaveRequestStatus::APPROVED);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.leave-requests.index'),
        ]);
    }

    public function reject(LeaveRequest $leaveRequest): JsonResponse
    {
        abort_unless(auth()->user()?->hasRole(RoleType::ADMIN), 403);
        $this->service->applyDecision($leaveRequest, LeaveRequestStatus::REJECTED);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.leave-requests.index'),
        ]);
    }

    private function authorizeLeaveRequestAccess(LeaveRequest $leaveRequest): void
    {
        if (auth()->user()?->hasRole(RoleType::ADMIN)) {
            return;
        }

        abort_unless((int) $leaveRequest->user_id === (int) auth()->id(), 403);
    }

    private function authorizeEmployeeCanMutatePendingOnly(LeaveRequest $leaveRequest): void
    {
        if (auth()->user()?->hasRole(RoleType::ADMIN)) {
            return;
        }

        abort_unless($leaveRequest->status === LeaveRequestStatus::PENDING, 403);
    }
}
