<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Candidate\ICandidateRepository;
use App\Contracts\Vacancy\IVacancyRepository;
use App\Http\Requests\Candidate\CandidateRequest;
use App\Http\Requests\Candidate\CandidateSearchRequest;
use App\Models\Candidate\Candidate;
use App\Models\Candidate\CandidateSearch;
use App\Services\Candidate\CandidateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class CandidateController extends BaseController
{
    public function __construct(
        CandidateService $service,
        ICandidateRepository $repository,
        protected IVacancyRepository $vacancyRepository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('candidate.index', [
            'vacancies' => $this->vacancyRepository->getForSelect(),
        ]);
    }

    public function getListData(CandidateSearchRequest $request): array
    {
        $searcher = new CandidateSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'candidate.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(CandidateRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.candidates.index'),
        ]);
    }

    public function show(Candidate $candidate): View
    {
        return $this->dashboardView(
            view: 'candidate.form',
            vars: $this->service->getViewData($candidate->id),
            viewMode: 'show'
        );
    }

    public function edit(Candidate $candidate): View
    {
        return $this->dashboardView(
            view: 'candidate.form',
            vars: $this->service->getViewData($candidate->id),
            viewMode: 'edit'
        );
    }

    public function update(CandidateRequest $request, Candidate $candidate): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $candidate->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.candidates.index'),
        ]);
    }

    public function destroy(Candidate $candidate): JsonResponse
    {
        $this->service->delete($candidate->id);

        return $this->sendOkDeleted();
    }
}
