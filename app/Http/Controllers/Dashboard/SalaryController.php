<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\Concerns\AuthorizesDashboardEmployeeAccess;
use App\Contracts\Salary\ISalaryRepository;
use App\Http\Requests\Salary\SalaryRequest;
use App\Http\Requests\Salary\SalarySearchRequest;
use App\Models\Salary\Salary;
use App\Models\Salary\SalarySearch;
use App\Services\Salary\SalaryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class SalaryController extends BaseController
{
    use AuthorizesDashboardEmployeeAccess;

    public function __construct(
        SalaryService $service,
        ISalaryRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        $base = Salary::query();
        if (!$this->dashboardUserIsAdmin()) {
            $base->where('user_id', auth()->id());
        }

        $total = (clone $base)->count();
        $distinctUsers = $this->dashboardUserIsAdmin()
            ? (int) Salary::query()->selectRaw('COUNT(DISTINCT user_id) as aggregate')->value('aggregate')
            : ($total > 0 ? 1 : 0);
        $avgAmount = (clone $base)->avg('amount');
        $avgAmount = $avgAmount !== null ? round((float) $avgAmount, 2) : null;
        $thisYear = (clone $base)->whereYear('effective_date', now()->year)->count();

        return $this->dashboardView('salary.index', array_merge($this->service->getIndexViewData(), [
            'createRoute' => $this->dashboardUserIsAdmin() ? route('dashboard.salaries.create') : null,
            'salaryAdmin' => $this->dashboardUserIsAdmin(),
            'salaryStats' => [
                'total' => $total,
                'employees' => $distinctUsers,
                'avg_amount' => $avgAmount,
                'this_year' => $thisYear,
            ],
        ]));
    }

    public function getListData(SalarySearchRequest $request): array
    {
        $searcher = new SalarySearch($request->validated());

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
            view: 'salary.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(SalaryRequest $request): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.salaries.index'),
        ]);
    }

    public function show(Salary $salary): View
    {
        $this->abortUnlessAdminOrOwnsUserId($salary->user_id);

        return $this->dashboardView(
            view: 'salary.form',
            vars: $this->service->getViewData($salary->id),
            viewMode: 'show'
        );
    }

    public function edit(Salary $salary): View
    {
        $this->abortUnlessAdminCanManageHrRecords();

        return $this->dashboardView(
            view: 'salary.form',
            vars: $this->service->getViewData($salary->id),
            viewMode: 'edit'
        );
    }

    public function update(SalaryRequest $request, Salary $salary): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated(), $salary->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.salaries.index'),
        ]);
    }

    public function destroy(Salary $salary): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->delete($salary->id);

        return $this->sendOkDeleted();
    }
}
