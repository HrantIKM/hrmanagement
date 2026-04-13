<?php

namespace App\Services\Payslip;

use App\Contracts\Payslip\IPayslipRepository;
use App\Contracts\User\IUserRepository;
use App\Models\Salary\Salary;
use App\Models\Timesheet\Timesheet;
use App\Services\BaseService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PayslipService extends BaseService
{
    public function __construct(
        IPayslipRepository $repository,
        protected IUserRepository $userRepository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $data = parent::getViewData($id);
        $data['users'] = $this->userRepository->getForSelect();

        return $data;
    }

    public function getIndexViewData(): array
    {
        $months = [];
        for ($m = 1; $m <= 12; ++$m) {
            $months[$m] = date('F', mktime(0, 0, 0, $m, 1));
        }

        return [
            'users' => $this->userRepository->getForSelect(),
            'payslipMonthOptions' => collect($months),
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        $pdf = request()->file('pdf');
        unset($data['pdf']);

        foreach (['bonus', 'deductions'] as $key) {
            if (!array_key_exists($key, $data) || $data[$key] === '' || $data[$key] === null) {
                $data[$key] = 0;
            }
        }

        return DB::transaction(function () use ($data, $id, $pdf) {
            if ($id !== null) {
                $existing = $this->repository->find($id);
                if ($pdf) {
                    if ($existing->pdf_path) {
                        Storage::disk('public')->delete($existing->pdf_path);
                    }
                    $data['pdf_path'] = $pdf->store('payslips', 'public');
                }

                return $this->repository->update($id, $data);
            }

            if ($pdf) {
                $data['pdf_path'] = $pdf->store('payslips', 'public');
            }

            return $this->repository->create($data);
        });
    }

    public function delete(int $id): void
    {
        $model = $this->repository->find($id);
        if ($model->pdf_path) {
            Storage::disk('public')->delete($model->pdf_path);
        }
        $this->repository->destroy($id);
    }

    public function downloadGeneratedPdf(int $id): Response
    {
        $payslip = $this->repository->find($id, ['user']);
        $periodStart = Carbon::create((int) $payslip->period_year, (int) $payslip->period_month, 1)->startOfMonth();
        $periodEnd = $periodStart->copy()->endOfMonth();

        $salary = Salary::query()
            ->where('user_id', $payslip->user_id)
            ->whereDate('effective_date', '<=', $periodEnd->toDateString())
            ->latest('effective_date')
            ->first();

        $timesheetMinutes = (int) Timesheet::query()
            ->where('user_id', $payslip->user_id)
            ->whereBetween('date', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->sum('duration_minutes');

        $hoursWorked = round($timesheetMinutes / 60, 2);
        $baseAmount = (float) ($salary?->amount ?? $payslip->base_amount);
        $bonus = (float) $payslip->bonus;
        $deductions = (float) $payslip->deductions;
        $computedNet = round($baseAmount + $bonus - $deductions, 2);

        $pdf = Pdf::loadView('pdf.payslip', [
            'payslip' => $payslip,
            'salary' => $salary,
            'hoursWorked' => $hoursWorked,
            'computedNet' => $computedNet,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
        ]);

        return $pdf->download("payslip-{$payslip->id}.pdf");
    }
}
