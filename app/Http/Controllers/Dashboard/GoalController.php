<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Goal\IGoalRepository;
use App\Http\Controllers\Dashboard\Concerns\AuthorizesDashboardEmployeeAccess;
use App\Http\Requests\Goal\GoalRequest;
use App\Http\Requests\Goal\GoalSearchRequest;
use App\Models\Goal\Goal;
use App\Models\Goal\GoalSearch;
use App\Services\Goal\GoalService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class GoalController extends BaseController
{
    use AuthorizesDashboardEmployeeAccess;

    public function __construct(
        GoalService $service,
        IGoalRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        $base = $this->scopedGoalsQuery();
        $total = (clone $base)->count();
        $achieved = (clone $base)
            ->where('target_value', '>', 0)
            ->whereColumn('current_value', '>=', 'target_value')
            ->count();
        $avgProgress = (clone $base)
            ->where('target_value', '>', 0)
            ->selectRaw('AVG(LEAST(100, (current_value / target_value) * 100)) as aggregate')
            ->value('aggregate');
        $avgProgress = $avgProgress !== null ? round((float) $avgProgress, 1) : null;
        $overdue = (clone $base)
            ->whereNotNull('deadline')
            ->whereDate('deadline', '<', now()->toDateString())
            ->where('target_value', '>', 0)
            ->whereColumn('current_value', '<', 'target_value')
            ->count();

        return $this->dashboardView('goal.index', array_merge($this->service->getIndexViewData(), [
            'createRoute' => $this->dashboardUserIsAdmin() ? route('dashboard.goals.create') : null,
            'goalStats' => [
                'total' => $total,
                'avg_progress' => $avgProgress,
                'achieved' => $achieved,
                'overdue' => $overdue,
            ],
        ]));
    }

    protected function scopedGoalsQuery(): Builder
    {
        $query = Goal::query();
        if (!$this->dashboardUserIsAdmin()) {
            $query->where('user_id', auth()->id());
        }

        return $query;
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
        $this->abortUnlessAdminCanManageHrRecords();

        return $this->dashboardView(
            view: 'goal.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(GoalRequest $request): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.goals.index'),
        ]);
    }

    public function show(Goal $goal): View
    {
        $this->abortUnlessAdminOrOwnsUserId($goal->user_id);

        return $this->dashboardView(
            view: 'goal.form',
            vars: $this->service->getViewData($goal->id),
            viewMode: 'show'
        );
    }

    public function edit(Goal $goal): View
    {
        $this->abortUnlessAdminCanManageHrRecords();

        return $this->dashboardView(
            view: 'goal.form',
            vars: $this->service->getViewData($goal->id),
            viewMode: 'edit'
        );
    }

    public function update(GoalRequest $request, Goal $goal): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated(), $goal->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.goals.index'),
        ]);
    }

    public function destroy(Goal $goal): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->delete($goal->id);

        return $this->sendOkDeleted();
    }
}
