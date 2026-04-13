<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\LeaveBalance\ILeaveBalanceRepository;
use App\Http\Requests\LeaveBalance\LeaveBalanceRequest;
use App\Http\Requests\LeaveBalance\LeaveBalanceSearchRequest;
use App\Models\LeaveBalance\LeaveBalance;
use App\Models\LeaveBalance\LeaveBalanceSearch;
use App\Models\RoleAndPermission\Enums\RoleType;
use App\Services\LeaveBalance\LeaveBalanceService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class LeaveBalanceController extends BaseController
{
    public function __construct(
        LeaveBalanceService $service,
        ILeaveBalanceRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;

        $this->middleware(function ($request, $next) {
            $action = $request->route()?->getActionMethod();
            if (!in_array($action, ['create', 'store', 'edit', 'update', 'destroy', 'show'], true)) {
                return $next($request);
            }

            abort_unless(auth()->user()?->hasRole(RoleType::ADMIN), 403);

            return $next($request);
        });
    }

    public function index(): View
    {
        $userId = (int) auth()->id();
        $leaveBalanceAdmin = auth()->user()?->hasRole(RoleType::ADMIN) ?? false;

        return $this->dashboardView('leave-balance.index', [
            'createRoute' => $leaveBalanceAdmin ? route('dashboard.leave-balances.create') : null,
            'balanceCards' => $this->service->balancesForUser($userId),
            'leaveBalanceAdmin' => $leaveBalanceAdmin,
        ]);
    }

    public function getListData(LeaveBalanceSearchRequest $request): array
    {
        abort_unless(auth()->user()?->hasRole(RoleType::ADMIN), 403);

        $searcher = new LeaveBalanceSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'leave-balance.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(LeaveBalanceRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.leave-balances.index')
        ]);
    }

    public function show(LeaveBalance $leaveBalance): View
    {
        return $this->dashboardView(
            view: 'leave-balance.form',
            vars: $this->service->getViewData($leaveBalance->id),
            viewMode: 'show'
        );
    }

    public function edit(LeaveBalance $leaveBalance): View
    {
        return $this->dashboardView(
            view: 'leave-balance.form',
            vars: $this->service->getViewData($leaveBalance->id),
            viewMode: 'edit'
        );
    }

    public function update(LeaveBalanceRequest $request, LeaveBalance $leaveBalance): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $leaveBalance->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.leave-balances.index')
        ]);
    }

    public function destroy(LeaveBalance $leaveBalance): JsonResponse
    {
        // If deleting other data except model use service
        // $this->service->delete($leaveBalance->id);
        $this->repository->destroy($leaveBalance->id);

        return $this->sendOkDeleted();
    }
}
