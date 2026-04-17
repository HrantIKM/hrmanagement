<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\Concerns\AuthorizesDashboardEmployeeAccess;
use App\Http\Requests\Holiday\HolidayRequest;
use App\Http\Requests\Holiday\HolidaySearchRequest;
use App\Models\Holiday\HolidaySearch;
use App\Models\Holiday\Holiday;
use App\Services\Holiday\HolidayService;
use App\Contracts\Holiday\IHolidayRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class HolidayController extends BaseController
{
    use AuthorizesDashboardEmployeeAccess;

    public function __construct(
        HolidayService $service,
        IHolidayRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        $total = Holiday::count();
        $upcoming = Holiday::whereDate('date', '>=', now()->toDateString())->count();
        $publicCount = Holiday::where('is_public', true)->count();
        $thisYear = Holiday::whereYear('date', now()->year)->count();

        return $this->dashboardView('holiday.index', [
            'createRoute' => $this->dashboardUserIsAdmin() ? route('dashboard.holidays.create') : null,
            'holidayStats' => [
                'total' => $total,
                'upcoming' => $upcoming,
                'public' => $publicCount,
                'this_year' => $thisYear,
            ],
        ]);
    }

    public function getListData(HolidaySearchRequest $request): array
    {
        $searcher = new HolidaySearch($request->validated());

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
            view: 'holiday.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(HolidayRequest $request): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.holidays.index')
        ]);
    }

    public function show(Holiday $holiday): View
    {
        return $this->dashboardView(
            view: 'holiday.form',
            vars: $this->service->getViewData($holiday->id),
            viewMode: 'show'
        );
    }

    public function edit(Holiday $holiday): View
    {
        $this->abortUnlessAdminCanManageHrRecords();

        return $this->dashboardView(
            view: 'holiday.form',
            vars: $this->service->getViewData($holiday->id),
            viewMode: 'edit'
        );
    }

    public function update(HolidayRequest $request, Holiday $holiday): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated(), $holiday->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.holidays.index')
        ]);
    }

    public function destroy(Holiday $holiday): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        // If deleting other data except model use service
        // $this->service->delete($holiday->id);
        $this->repository->destroy($holiday->id);

        return $this->sendOkDeleted();
    }
}
