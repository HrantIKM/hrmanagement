<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\Concerns\AuthorizesDashboardEmployeeAccess;
use App\Contracts\Department\IDepartmentRepository;
use App\Http\Requests\Department\DepartmentRequest;
use App\Http\Requests\Department\DepartmentSearchRequest;
use App\Models\Department\Department;
use App\Models\Department\DepartmentSearch;
use App\Services\Department\DepartmentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class DepartmentController extends BaseController
{
    use AuthorizesDashboardEmployeeAccess;

    public function __construct(
        DepartmentService $service,
        IDepartmentRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        view()->share('subHeaderData', ['pageName' => 'department.hub']);

        return $this->dashboardView('department.hub', [
            'canManageDepartments' => $this->dashboardUserIsAdmin(),
        ]);
    }

    public function table(): View
    {
        view()->share('subHeaderData', ['pageName' => 'department.table']);

        return $this->dashboardView('department.table', [
            'createRoute' => $this->dashboardUserIsAdmin() ? route('dashboard.departments.create') : null,
        ]);
    }

    public function hubData(): JsonResponse
    {
        return response()->json($this->service->buildHubPayload());
    }

    public function getListData(DepartmentSearchRequest $request): array
    {
        $searcher = new DepartmentSearch($request->validated());

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
            view: 'department.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(DepartmentRequest $request): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.departments.index'),
        ]);
    }

    public function show(Department $department): View
    {
        return $this->dashboardView(
            view: 'department.form',
            vars: $this->service->getViewData($department->id),
            viewMode: 'show'
        );
    }

    public function edit(Department $department): View
    {
        $this->abortUnlessAdminCanManageHrRecords();

        return $this->dashboardView(
            view: 'department.form',
            vars: $this->service->getViewData($department->id),
            viewMode: 'edit'
        );
    }

    public function update(DepartmentRequest $request, Department $department): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated(), $department->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.departments.index'),
        ]);
    }

    public function destroy(Department $department): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->delete($department->id);

        return $this->sendOkDeleted();
    }
}
