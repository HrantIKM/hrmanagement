<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>
            <div class="px-4 pt-3 d-flex flex-wrap gap-2">
                <a href="{{ route('dashboard.payslips.exportCsv') }}" class="btn btn-outline-primary btn-sm">Download Report (CSV)</a>
                <a href="{{ route('dashboard.payslips.exportExcel') }}" class="btn btn-outline-primary btn-sm">Download Report (Excel)</a>
            </div>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._select name="user_id" allowClear defaultOption
                                                  :data="$users" class="select2"/>
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
                   <th data-key="user" data-orderable="false">{{ __('label.user') }}</th>
                   <th data-key="period_display" data-orderable="false">{{ __('label.pay_period') }}</th>
                   <th data-key="net_total">{{ __('label.net_total') }}</th>
                   <th data-key="pdf_path" data-orderable="false">{{ __('label.pdf_path') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/payslip/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
