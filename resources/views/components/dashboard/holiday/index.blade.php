<x-dashboard.layouts.app>
    <div class="container-fluid holidays-page">
        <section class="holidays-hero mb-4">
            <div>
                <h2 class="holidays-hero__title mb-1">{{ __('holiday.index.hero_title') }}</h2>
                <p class="holidays-hero__subtitle mb-0">{{ __('holiday.index.hero_subtitle') }}</p>
            </div>
            <div class="holidays-hero__stats">
                <div class="holidays-hero__stat">
                    <span class="label">{{ __('holiday.index.stat_total') }}</span>
                    <strong>{{ $holidayStats['total'] }}</strong>
                </div>
                <div class="holidays-hero__stat">
                    <span class="label">{{ __('holiday.index.stat_upcoming') }}</span>
                    <strong>{{ $holidayStats['upcoming'] }}</strong>
                </div>
                <div class="holidays-hero__stat">
                    <span class="label">{{ __('holiday.index.stat_public') }}</span>
                    <strong>{{ $holidayStats['public'] }}</strong>
                </div>
                <div class="holidays-hero__stat">
                    <span class="label">{{ __('holiday.index.stat_this_year') }}</span>
                    <strong>{{ $holidayStats['this_year'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 holidays-card">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-3 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-3 form-group">
                        <x-dashboard.form._input name="name"/>
                    </div>

                    <div class="col-md-3 form-group">
                        <x-dashboard.form._input name="date" type="date"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="name">{{ __('label.name') }}</th>
                   <th data-key="date">{{ __('label.date') }}</th>
                   <th data-key="is_public" data-orderable="false">{{ __('holiday.index.col_public') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/holiday/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
