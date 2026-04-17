<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Position\IPositionRepository;
use App\Contracts\Vacancy\IVacancyRepository;
use App\Http\Requests\Vacancy\VacancyRequest;
use App\Models\Vacancy\Enums\VacancyStatus;
use App\Http\Requests\Vacancy\VacancySearchRequest;
use App\Models\Vacancy\Vacancy;
use App\Models\Vacancy\VacancySearch;
use App\Services\Vacancy\VacancyService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class VacancyController extends BaseController
{
    public function __construct(
        VacancyService $service,
        IVacancyRepository $repository,
        protected IPositionRepository $positionRepository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        $total = Vacancy::count();
        $open = Vacancy::where('status', VacancyStatus::OPEN)->count();
        $closed = Vacancy::where('status', VacancyStatus::CLOSED)->count();
        $closingSoon = Vacancy::where('status', VacancyStatus::OPEN)
            ->whereNotNull('closing_date')
            ->whereDate('closing_date', '>=', now()->toDateString())
            ->whereDate('closing_date', '<=', now()->addDays(14)->toDateString())
            ->count();

        return $this->dashboardView('vacancy.index', [
            'positions' => $this->positionRepository->getForSelect(),
            'vacancyStatuses' => collect(VacancyStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('vacancy.status.' . $v)]),
            'createRoute' => route('dashboard.vacancies.create'),
            'vacancyStats' => [
                'total' => $total,
                'open' => $open,
                'closed' => $closed,
                'closing_soon' => $closingSoon,
            ],
        ]);
    }

    public function getListData(VacancySearchRequest $request): array
    {
        $searcher = new VacancySearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'vacancy.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(VacancyRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.vacancies.index'),
        ]);
    }

    public function show(Vacancy $vacancy): View
    {
        return $this->dashboardView(
            view: 'vacancy.form',
            vars: $this->service->getViewData($vacancy->id),
            viewMode: 'show'
        );
    }

    public function edit(Vacancy $vacancy): View
    {
        return $this->dashboardView(
            view: 'vacancy.form',
            vars: $this->service->getViewData($vacancy->id),
            viewMode: 'edit'
        );
    }

    public function update(VacancyRequest $request, Vacancy $vacancy): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $vacancy->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.vacancies.index'),
        ]);
    }

    public function destroy(Vacancy $vacancy): JsonResponse
    {
        $this->service->delete($vacancy->id);

        return $this->sendOkDeleted();
    }
}
