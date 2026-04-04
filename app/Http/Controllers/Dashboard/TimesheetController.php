<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Task\ITaskRepository;
use App\Contracts\Timesheet\ITimesheetRepository;
use App\Contracts\User\IUserRepository;
use App\Http\Requests\Timesheet\TimesheetRequest;
use App\Http\Requests\Timesheet\TimesheetSearchRequest;
use App\Models\Timesheet\Timesheet;
use App\Models\Timesheet\TimesheetSearch;
use App\Services\Timesheet\TimesheetService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class TimesheetController extends BaseController
{
    public function __construct(
        TimesheetService $service,
        ITimesheetRepository $repository,
        protected ITaskRepository $taskRepository,
        protected IUserRepository $userRepository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('timesheet.index', [
            'tasks' => $this->taskRepository->getForSelect(),
            'users' => $this->userRepository->getForSelect(),
        ]);
    }

    public function getListData(TimesheetSearchRequest $request): array
    {
        $searcher = new TimesheetSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'timesheet.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(TimesheetRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.timesheets.index'),
        ]);
    }

    public function show(Timesheet $timesheet): View
    {
        return $this->dashboardView(
            view: 'timesheet.form',
            vars: $this->service->getViewData($timesheet->id),
            viewMode: 'show'
        );
    }

    public function edit(Timesheet $timesheet): View
    {
        return $this->dashboardView(
            view: 'timesheet.form',
            vars: $this->service->getViewData($timesheet->id),
            viewMode: 'edit'
        );
    }

    public function update(TimesheetRequest $request, Timesheet $timesheet): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $timesheet->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.timesheets.index'),
        ]);
    }

    public function destroy(Timesheet $timesheet): JsonResponse
    {
        $this->service->delete($timesheet->id);

        return $this->sendOkDeleted();
    }
}
