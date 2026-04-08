<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Skill\ISkillRepository;
use App\Http\Requests\Skill\SkillRequest;
use App\Http\Requests\Skill\SkillSearchRequest;
use App\Models\Department\Department;
use App\Models\Department\Enums\DepartmentCode;
use App\Models\Skill\Enums\SkillCategory;
use App\Models\Skill\Skill;
use App\Models\Skill\SkillSearch;
use App\Services\Skill\SkillService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class SkillController extends BaseController
{
    public function __construct(
        SkillService $service,
        ISkillRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('skill.index', [
            'skillCategories' => collect(SkillCategory::ALL)
                ->mapWithKeys(fn(string $v) => [$v => __('skill.category.' . $v)]),
            'departments' => Department::query()
                ->whereIn('name', DepartmentCode::values())
                ->orderBy('name')
                ->get()
                ->mapWithKeys(fn(Department $d) => [$d->id => $d->name]),
        ]);
    }

    public function getListData(SkillSearchRequest $request): array
    {
        $searcher = new SkillSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'skill.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(SkillRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.skills.index'),
        ]);
    }

    public function show(Skill $skill): View
    {
        return $this->dashboardView(
            view: 'skill.form',
            vars: $this->service->getViewData($skill->id),
            viewMode: 'show'
        );
    }

    public function edit(Skill $skill): View
    {
        return $this->dashboardView(
            view: 'skill.form',
            vars: $this->service->getViewData($skill->id),
            viewMode: 'edit'
        );
    }

    public function update(SkillRequest $request, Skill $skill): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $skill->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.skills.index'),
        ]);
    }

    public function destroy(Skill $skill): JsonResponse
    {
        $this->service->delete($skill->id);

        return $this->sendOkDeleted();
    }
}
