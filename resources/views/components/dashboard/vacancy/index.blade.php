<x-dashboard.layouts.app>
    <div class="container-fluid vacancies-page">
        <section class="vacancies-hero mb-4">
            <div>
                <h2 class="vacancies-hero__title mb-1">{{ __('vacancy.index.hero_title') }}</h2>
                <p class="vacancies-hero__subtitle mb-0">{{ __('vacancy.index.hero_subtitle') }}</p>
            </div>
            <div class="vacancies-hero__stats">
                <div class="vacancies-hero__stat">
                    <span class="label">{{ __('vacancy.index.stat_total') }}</span>
                    <strong>{{ $vacancyStats['total'] }}</strong>
                </div>
                <div class="vacancies-hero__stat">
                    <span class="label">{{ __('vacancy.index.stat_open') }}</span>
                    <strong>{{ $vacancyStats['open'] }}</strong>
                </div>
                <div class="vacancies-hero__stat">
                    <span class="label">{{ __('vacancy.index.stat_closed') }}</span>
                    <strong>{{ $vacancyStats['closed'] }}</strong>
                </div>
                <div class="vacancies-hero__stat">
                    <span class="label">{{ __('vacancy.index.stat_closing_soon') }}</span>
                    <strong>{{ $vacancyStats['closing_soon'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 vacancies-card">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-3 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-3 form-group">
                        <x-dashboard.form._input name="title"/>
                    </div>

                    <div class="col-md-3 form-group">
                        <x-dashboard.form._select name="position_id" allowClear defaultOption
                                                  :data="$positions" class="select2"/>
                    </div>

                    <div class="col-md-3 form-group">
                        <x-dashboard.form._select name="status" allowClear defaultOption
                                                  :data="$vacancyStatuses" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="title">{{ __('label.title') }}</th>
                   <th data-key="position" data-orderable="false">{{ __('label.position_id') }}</th>
                   <th data-key="status_display" data-orderable="false">{{ __('label.status') }}</th>
                   <th data-key="closing_date">{{ __('label.closing_date') }}</th>
                   <th data-key="skills" data-orderable="false">{{ __('label.skill_ids') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/vacancy/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
