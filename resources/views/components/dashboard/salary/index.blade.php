<x-dashboard.layouts.app>
    <div class="container-fluid salaries-page">
        <section class="salaries-hero mb-4">
            <div>
                <h2 class="salaries-hero__title mb-1">{{ __('salary.index.hero_title') }}</h2>
                <p class="salaries-hero__subtitle mb-0">{{ __('salary.index.hero_subtitle') }}</p>
            </div>
            <div class="salaries-hero__stats">
                <div class="salaries-hero__stat">
                    <span class="label">{{ __('salary.index.stat_total') }}</span>
                    <strong>{{ $salaryStats['total'] }}</strong>
                </div>
                <div class="salaries-hero__stat">
                    <span class="label">{{ __('salary.index.stat_employees') }}</span>
                    <strong>{{ $salaryStats['employees'] }}</strong>
                </div>
                <div class="salaries-hero__stat">
                    <span class="label">{{ __('salary.index.stat_avg') }}</span>
                    <strong>{{ $salaryStats['avg_amount'] !== null ? number_format($salaryStats['avg_amount'], 2) : '—' }}</strong>
                </div>
                <div class="salaries-hero__stat">
                    <span class="label">{{ __('salary.index.stat_this_year') }}</span>
                    <strong>{{ $salaryStats['this_year'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 salaries-card">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    @if($salaryAdmin ?? false)
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._select name="user_id" allowClear defaultOption
                                                  :data="$users" class="select2"/>
                    </div>
                    @endif

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._select name="change_reason" allowClear defaultOption
                                                  :data="$salaryChangeReasonOptions" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="user" data-orderable="false">{{ __('label.user') }}</th>
                   <th data-key="amount">{{ __('label.amount') }}</th>
                   <th data-key="effective_date">{{ __('label.effective_date') }}</th>
                   <th data-key="change_reason_display" data-orderable="false">{{ __('label.change_reason') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/salary/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
