<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Goal\IGoalRepository;
use App\Http\Requests\Goal\GoalRequest;
use App\Http\Requests\Goal\GoalSearchRequest;
use App\Models\Goal\Goal;
use App\Models\Goal\GoalSearch;
use App\Services\Goal\GoalService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class GoalController extends BaseController
{
    public function __construct(
        GoalService $service,
        IGoalRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('goal.index', $this->service->getIndexViewData());
    }

    public function getListData(GoalSearchRequest $request): array
    {
        $searcher = new GoalSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'goal.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(GoalRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.goals.index'),
        ]);
    }

    public function show(Goal $goal): View
    {
        return $this->dashboardView(
            view: 'goal.form',
            vars: $this->service->getViewData($goal->id),
            viewMode: 'show'
        );
    }

    public function edit(Goal $goal): View
    {
        return $this->dashboardView(
            view: 'goal.form',
            vars: $this->service->getViewData($goal->id),
            viewMode: 'edit'
        );
    }

    public function update(GoalRequest $request, Goal $goal): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $goal->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.goals.index'),
        ]);
    }

    public function destroy(Goal $goal): JsonResponse
    {
        $this->service->delete($goal->id);

        return $this->sendOkDeleted();
    }
}
