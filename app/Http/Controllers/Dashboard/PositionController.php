<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Department\IDepartmentRepository;
use App\Contracts\Position\IPositionRepository;
use App\Http\Requests\Position\PositionRequest;
use App\Http\Requests\Position\PositionSearchRequest;
use App\Models\Department\Department;
use App\Models\Position\Position;
use App\Models\Position\PositionSearch;
use App\Services\Position\PositionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class PositionController extends BaseController
{
    public function __construct(
        PositionService $service,
        IPositionRepository $repository,
        protected IDepartmentRepository $departmentRepository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        $total = Position::count();
        $departmentsWithRoles = Department::whereHas('positions')->count();
        $withPayBand = Position::whereNotNull('min_salary')->whereNotNull('max_salary')->count();
        $unassignedDepartment = Position::whereNull('department_id')->count();

        return $this->dashboardView('position.index', [
            'departments' => $this->departmentRepository->getForSelect(),
            'createRoute' => route('dashboard.positions.create'),
            'positionStats' => [
                'total' => $total,
                'departments' => $departmentsWithRoles,
                'pay_bands' => $withPayBand,
                'unassigned' => $unassignedDepartment,
            ],
        ]);
    }

    public function getListData(PositionSearchRequest $request): array
    {
        $searcher = new PositionSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'position.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(PositionRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.positions.index'),
        ]);
    }

    public function show(Position $position): View
    {
        return $this->dashboardView(
            view: 'position.form',
            vars: $this->service->getViewData($position->id),
            viewMode: 'show'
        );
    }

    public function edit(Position $position): View
    {
        return $this->dashboardView(
            view: 'position.form',
            vars: $this->service->getViewData($position->id),
            viewMode: 'edit'
        );
    }

    public function update(PositionRequest $request, Position $position): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $position->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.positions.index'),
        ]);
    }

    public function destroy(Position $position): JsonResponse
    {
        $this->service->delete($position->id);

        return $this->sendOkDeleted();
    }
}
