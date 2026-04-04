<?php

namespace App\Http\Controllers\Dashboard;

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
    public function __construct(
        SalaryService $service,
        ISalaryRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('salary.index', $this->service->getIndexViewData());
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
        return $this->dashboardView(
            view: 'salary.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(SalaryRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.salaries.index'),
        ]);
    }

    public function show(Salary $salary): View
    {
        return $this->dashboardView(
            view: 'salary.form',
            vars: $this->service->getViewData($salary->id),
            viewMode: 'show'
        );
    }

    public function edit(Salary $salary): View
    {
        return $this->dashboardView(
            view: 'salary.form',
            vars: $this->service->getViewData($salary->id),
            viewMode: 'edit'
        );
    }

    public function update(SalaryRequest $request, Salary $salary): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $salary->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.salaries.index'),
        ]);
    }

    public function destroy(Salary $salary): JsonResponse
    {
        $this->service->delete($salary->id);

        return $this->sendOkDeleted();
    }
}
