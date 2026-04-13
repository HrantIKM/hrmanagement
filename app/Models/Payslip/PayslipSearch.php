<?php

namespace App\Models\Payslip;

use App\Models\Base\Concerns\ScopesEmployeeOwnedDataTable;
use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class PayslipSearch extends Search
{
    use ScopesEmployeeOwnedDataTable;

    protected array $orderables = [
        'id',
        'period_year',
        'period_month',
        'net_total',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        $query = Payslip::with(['user'])->select([
            'id',
            'period_month',
            'period_year',
            'base_amount',
            'bonus',
            'deductions',
            'net_total',
            'pdf_path',
            'user_id',
        ]);

        $this->scopeToAssigneeUnlessAdmin($query);

        return $query
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['user_id']) && $this->dashboardUserIsAdmin(), function ($query) use ($filters) {
                $query->where('user_id', $filters['user_id']);
            })
            ->when(!empty($filters['period_month']), function ($query) use ($filters) {
                $query->where('period_month', $filters['period_month']);
            })
            ->when(!empty($filters['period_year']), function ($query) use ($filters) {
                $query->where('period_year', $filters['period_year']);
            });
    }

    public function totalCount(): int
    {
        return $this->assigneeScopedTotalCount(Payslip::class);
    }
}
