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
use Illuminate\Support\Facades\Storage;

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
        $totalCandidates = Candidate::query()->count();
        $avgMatch = (float) Candidate::query()->avg('match_score');
        $highMatch = Candidate::query()->where('match_score', '>=', 80)->count();

        return $this->dashboardView('candidate.index', [
            'vacancies' => $this->vacancyRepository->getForSelect(),
            'candidateStats' => [
                'total' => $totalCandidates,
                'average_match' => round($avgMatch, 1),
                'high_match' => $highMatch,
            ],
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

    public function resume(Candidate $candidate)
    {
        abort_unless($candidate->resume_path, 404);
        abort_unless(Storage::disk('public')->exists($candidate->resume_path), 404);

        return Storage::disk('public')->response(
            $candidate->resume_path,
            basename($candidate->resume_path),
            ['Content-Type' => 'application/pdf']
        );
    }

    public function show(Candidate $candidate): View
    {
        return $this->dashboardView(
            view: 'candidate.show',
            vars: $this->service->getDetailViewData($candidate->id),
            viewMode: 'show',
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
