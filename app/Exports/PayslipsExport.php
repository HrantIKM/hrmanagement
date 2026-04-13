<?php

namespace App\Exports;

use App\Models\Payslip\Payslip;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayslipsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Payslip::query()->with('user:id,first_name,last_name,email');
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['ID', 'Employee', 'Period', 'Base', 'Bonus', 'Deductions', 'Net Total'];
    }

    /**
     * @param  Payslip  $payslip
     * @return array<int, string|int|float|null>
     */
    public function map($payslip): array
    {
        return [
            $payslip->id,
            $payslip->user?->name ?? $payslip->user?->email,
            $payslip->period_display,
            $payslip->base_amount,
            $payslip->bonus,
            $payslip->deductions,
            $payslip->net_total,
        ];
    }
}
