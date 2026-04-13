<?php

namespace App\Http\Controllers\Website;

use App\Http\Requests\Website\CareerApplicationRequest;
use App\Models\Vacancy\Enums\VacancyStatus;
use App\Models\Vacancy\Vacancy;
use App\Services\Candidate\CandidateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CareerController extends BaseController
{
    public function __construct(
        protected CandidateService $candidateService
    ) {
    }

    public function index(): View
    {
        $vacancies = Vacancy::query()
            ->with(['position:id,title', 'skills:id,name'])
            ->where('status', VacancyStatus::OPEN)
            ->orderByDesc('id')
            ->get();

        return view('careers.index', [
            'vacancies' => $vacancies,
        ]);
    }

    public function show(Vacancy $vacancy): View
    {
        abort_unless($vacancy->status === VacancyStatus::OPEN, 404);

        $vacancy->load(['position:id,title', 'skills:id,name']);

        return view('careers.show', [
            'vacancy' => $vacancy,
        ]);
    }

    public function apply(CareerApplicationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->candidateService->createOrUpdate(
            [
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'vacancy_id' => (int) $data['vacancy_id'],
            ],
            null,
            $request->file('resume')
        );

        return redirect()
            ->route('careers.show', $data['vacancy_id'])
            ->with('success', 'Application submitted successfully. Our team will review your CV.');
    }
}
