<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Project\IProjectRepository;
use App\Http\Requests\Project\ProjectRequest;
use App\Http\Requests\Project\ProjectSearchRequest;
use App\Models\Project\Enums\ProjectStatus;
use App\Models\Project\Project;
use App\Models\Project\ProjectSearch;
use App\Services\Project\ProjectService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ProjectController extends BaseController
{
    public function __construct(
        ProjectService $service,
        IProjectRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('project.index', [
            'projectStatuses' => collect(ProjectStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('project.status.' . $v)]),
        ]);
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
        return $this->dashboardView(
            view: 'project.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.projects.index'),
        ]);
    }

    public function show(Project $project): View
    {
        return $this->dashboardView(
            view: 'project.form',
            vars: $this->service->getViewData($project->id),
            viewMode: 'show'
        );
    }

    public function edit(Project $project): View
    {
        return $this->dashboardView(
            view: 'project.form',
            vars: $this->service->getViewData($project->id),
            viewMode: 'edit'
        );
    }

    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $project->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.projects.index'),
        ]);
    }

    public function destroy(Project $project): JsonResponse
    {
        $this->service->delete($project->id);

        return $this->sendOkDeleted();
    }
}
