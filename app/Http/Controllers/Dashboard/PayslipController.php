<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Payslip\IPayslipRepository;
use App\Http\Requests\Payslip\PayslipRequest;
use App\Http\Requests\Payslip\PayslipSearchRequest;
use App\Models\Payslip\Payslip;
use App\Models\Payslip\PayslipSearch;
use App\Services\Payslip\PayslipService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class PayslipController extends BaseController
{
    public function __construct(
        PayslipService $service,
        IPayslipRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('payslip.index', $this->service->getIndexViewData());
    }

    public function getListData(PayslipSearchRequest $request): array
    {
        $searcher = new PayslipSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'payslip.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(PayslipRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.payslips.index'),
        ]);
    }

    public function show(Payslip $payslip): View
    {
        return $this->dashboardView(
            view: 'payslip.form',
            vars: $this->service->getViewData($payslip->id),
            viewMode: 'show'
        );
    }

    public function edit(Payslip $payslip): View
    {
        return $this->dashboardView(
            view: 'payslip.form',
            vars: $this->service->getViewData($payslip->id),
            viewMode: 'edit'
        );
    }

    public function update(PayslipRequest $request, Payslip $payslip): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $payslip->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.payslips.index'),
        ]);
    }

    public function destroy(Payslip $payslip): JsonResponse
    {
        $this->service->delete($payslip->id);

        return $this->sendOkDeleted();
    }
}
