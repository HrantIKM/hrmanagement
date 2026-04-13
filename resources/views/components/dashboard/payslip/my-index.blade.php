<x-dashboard.layouts.app>
    <div class="container-fluid payslip-mine-page">
        @if($payslips->isEmpty())
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-3 text-primary" style="font-size: 3rem; line-height: 1;">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                    <h4 class="mb-2">{{ __('payslip.my.empty_title') }}</h4>
                    <p class="text-muted mb-0 col-lg-8 mx-auto">{{ __('payslip.my.empty_body') }}</p>
                </div>
            </div>
        @else
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm bg-primary text-white">
                        <div class="card-body">
                            <div class="small text-white-50 text-uppercase fw-semibold">{{ __('payslip.my.stat_total') }}</div>
                            <div class="display-6 fw-bold">{{ $stats['count'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="small text-muted text-uppercase fw-semibold">{{ __('payslip.my.stat_total_net') }}</div>
                            <div class="display-6 fw-bold text-success">
                                {{ number_format((float) $stats['net_total_sum'], 2) }} {{ __('payslip.my.currency_suffix') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="small text-muted text-uppercase fw-semibold">{{ __('payslip.my.stat_latest') }}</div>
                            <div class="fs-4 fw-semibold text-dark">{{ $stats['latest_period'] ?? __('payslip.my.latest_none') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="mb-3 fw-semibold">{{ __('payslip.my.readable_heading') }}</h5>
            <div class="row g-3 mb-4">
                @foreach($payslips as $p)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex flex-wrap justify-content-between gap-2 align-items-start mb-3">
                                    <div>
                                        <div class="text-muted small text-uppercase">{{ __('payslip.my.period') }}</div>
                                        <div class="fw-semibold fs-5">{{ $p->period_display }}</div>
                                    </div>
                                    <div class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-2">
                                        {{ __('payslip.my.net') }}: {{ number_format((float) $p->net_total, 2) }} {{ __('payslip.my.currency_suffix') }}
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-4">
                                        <div class="small text-muted">{{ __('payslip.my.base') }}</div>
                                        <div class="fw-semibold">{{ number_format((float) $p->base_amount, 2) }}</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="small text-muted">{{ __('payslip.my.bonus') }}</div>
                                        <div class="fw-semibold">{{ number_format((float) $p->bonus, 2) }}</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="small text-muted">{{ __('payslip.my.deductions') }}</div>
                                        <div class="fw-semibold">{{ number_format((float) $p->deductions, 2) }}</div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('dashboard.payslips.download', $p) }}" class="btn btn-sm btn-outline-primary">
                                        {{ __('payslip.my.download_pdf') }}
                                    </a>
                                    <a href="{{ route('dashboard.payslips.show', $p) }}" class="btn btn-sm btn-outline-secondary">
                                        {{ __('payslip.my.view_detail') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($payslips->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header fw-semibold">{{ __('payslip.my.table_heading') }}</div>
                <div class="card-body">
                    <x-dashboard.datatable._filters_form>
                        <div class="col-md-4 col-lg-2 form-group">
                            <x-dashboard.form._input name="id" type="number"/>
                        </div>
                        <div class="col-md-4 col-lg-2 form-group">
                            <x-dashboard.form._select name="period_month" allowClear defaultOption
                                                      :data="$payslipMonthOptions" class="select2"/>
                        </div>
                        <div class="col-md-4 col-lg-2 form-group">
                            <x-dashboard.form._input name="period_year" type="number"/>
                        </div>
                    </x-dashboard.datatable._filters_form>

                    <x-dashboard.datatable._table>
                        <th data-key="id">{{ __('label.id') }}</th>
                        <th data-key="period_display" data-orderable="false">{{ __('label.pay_period') }}</th>
                        <th data-key="net_total">{{ __('label.net_total') }}</th>
                        <th data-key="pdf_path" data-orderable="false">{{ __('label.pdf_path') }}</th>
                        <th class="text-center">{{ __('label.actions') }}</th>
                    </x-dashboard.datatable._table>
                </div>
            </div>
        @endif
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/payslip/my-index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
