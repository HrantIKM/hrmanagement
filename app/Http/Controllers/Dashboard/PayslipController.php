<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Payslip\IPayslipRepository;
use App\Http\Controllers\Dashboard\Concerns\AuthorizesDashboardEmployeeAccess;
use App\Http\Requests\Payslip\PayslipRequest;
use App\Http\Requests\Payslip\PayslipSearchRequest;
use App\Exports\PayslipsExport;
use App\Models\Payslip\Payslip;
use App\Models\Payslip\PayslipSearch;
use App\Services\Payslip\PayslipService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PayslipController extends BaseController
{
    use AuthorizesDashboardEmployeeAccess;

    public function __construct(
        PayslipService $service,
        IPayslipRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View|RedirectResponse
    {
        if (!$this->dashboardUserIsAdmin()) {
            return redirect()->route('dashboard.payslips.mine');
        }

        return $this->dashboardView('payslip.index', array_merge($this->service->getIndexViewData(), [
            'createRoute' => route('dashboard.payslips.create'),
        ]));
    }

    public function myIndex(): View
    {
        view()->share('subHeaderData', ['pageName' => 'payslip.my-index']);

        $userId = (int) auth()->id();
        $payslips = Payslip::query()
            ->where('user_id', $userId)
            ->orderByDesc('period_year')
            ->orderByDesc('period_month')
            ->get();

        $stats = [
            'count' => $payslips->count(),
            'net_total_sum' => round((float) $payslips->sum('net_total'), 2),
            'latest_period' => $payslips->first()?->period_display,
        ];

        return $this->dashboardView('payslip.my-index', array_merge($this->service->getIndexViewData(), [
            'payslips' => $payslips,
            'stats' => $stats,
        ]));
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
        $this->abortUnlessAdminCanManageHrRecords();

        return $this->dashboardView(
            view: 'payslip.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(PayslipRequest $request): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.payslips.index'),
        ]);
    }

    public function show(Payslip $payslip): View
    {
        $this->abortUnlessAdminOrOwnsUserId($payslip->user_id);

        return $this->dashboardView(
            view: 'payslip.form',
            vars: array_merge($this->service->getViewData($payslip->id), [
                'indexUrl' => $this->dashboardUserIsAdmin()
                    ? route('dashboard.payslips.index')
                    : route('dashboard.payslips.mine'),
            ]),
            viewMode: 'show'
        );
    }

    public function edit(Payslip $payslip): View
    {
        $this->abortUnlessAdminCanManageHrRecords();

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
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->delete($payslip->id);

        return $this->sendOkDeleted();
    }

    public function download(Payslip $payslip): Response
    {
        $this->abortUnlessAdminOrOwnsUserId($payslip->user_id);

        return $this->service->downloadGeneratedPdf($payslip->id);
    }

    public function exportExcel(): BinaryFileResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        return Excel::download(new PayslipsExport(), 'payroll-report.xlsx');
    }

    public function exportCsv(): StreamedResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=payroll-report.csv',
        ];

        return response()->stream(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Employee', 'Period', 'Base', 'Bonus', 'Deductions', 'Net Total']);

            Payslip::query()->with('user:id,first_name,last_name,email')->chunk(300, function ($rows) use ($out) {
                foreach ($rows as $payslip) {
                    fputcsv($out, [
                        $payslip->id,
                        $payslip->user?->name ?? $payslip->user?->email,
                        $payslip->period_display,
                        $payslip->base_amount,
                        $payslip->bonus,
                        $payslip->deductions,
                        $payslip->net_total,
                    ]);
                }
            });
            fclose($out);
        }, 200, $headers);
    }
}
