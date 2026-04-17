<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Project\IProjectRepository;
use App\Http\Controllers\Dashboard\Concerns\AuthorizesDashboardEmployeeAccess;
use App\Http\Requests\Project\ProjectRequest;
use App\Http\Requests\Project\ProjectSearchRequest;
use App\Models\Project\Enums\ProjectStatus;
use App\Models\Project\Project;
use App\Models\Project\ProjectSearch;
use App\Services\Project\ProjectService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class ProjectController extends BaseController
{
    use AuthorizesDashboardEmployeeAccess;

    public function __construct(
        ProjectService $service,
        IProjectRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        $base = $this->scopedProjectsQuery();
        $total = (clone $base)->count();
        $planning = (clone $base)->where('status', ProjectStatus::PLANNING)->count();
        $active = (clone $base)->where('status', ProjectStatus::ACTIVE)->count();
        $completed = (clone $base)->where('status', ProjectStatus::COMPLETED)->count();

        return $this->dashboardView('project.index', [
            'projectStatuses' => collect(ProjectStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('project.status.' . $v)]),
            'createRoute' => $this->dashboardUserIsAdmin() ? route('dashboard.projects.create') : null,
            'projectStats' => [
                'total' => $total,
                'planning' => $planning,
                'active' => $active,
                'completed' => $completed,
            ],
        ]);
    }

    protected function scopedProjectsQuery(): Builder
    {
        $query = Project::query();
        if (!$this->dashboardUserIsAdmin()) {
            $query->whereHas('users', fn ($q) => $q->where('users.id', auth()->id()));
        }

        return $query;
    }

    public function getListData(ProjectSearchRequest $request): array
    {
        $searcher = new ProjectSearch($request->validated());

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
            view: 'project.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.projects.index'),
        ]);
    }

    public function show(Project $project): View
    {
        $this->abortUnlessAdminOrProjectMember($project);

        return $this->dashboardView(
            view: 'project.form',
            vars: $this->service->getViewData($project->id),
            viewMode: 'show'
        );
    }

    public function edit(Project $project): View
    {
        $this->abortUnlessAdminCanManageHrRecords();

        return $this->dashboardView(
            view: 'project.form',
            vars: $this->service->getViewData($project->id),
            viewMode: 'edit'
        );
    }

    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated(), $project->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.projects.index'),
        ]);
    }

    public function destroy(Project $project): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->delete($project->id);

        return $this->sendOkDeleted();
    }
}
