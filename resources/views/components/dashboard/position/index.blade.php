<x-dashboard.layouts.app>
    <div class="container-fluid positions-page">
        <section class="positions-hero mb-4">
            <div>
                <h2 class="positions-hero__title mb-1">{{ __('position.index.hero_title') }}</h2>
                <p class="positions-hero__subtitle mb-0">{{ __('position.index.hero_subtitle') }}</p>
            </div>
            <div class="positions-hero__stats">
                <div class="positions-hero__stat">
                    <span class="label">{{ __('position.index.stat_total') }}</span>
                    <strong>{{ $positionStats['total'] }}</strong>
                </div>
                <div class="positions-hero__stat">
                    <span class="label">{{ __('position.index.stat_departments') }}</span>
                    <strong>{{ $positionStats['departments'] }}</strong>
                </div>
                <div class="positions-hero__stat">
                    <span class="label">{{ __('position.index.stat_pay_bands') }}</span>
                    <strong>{{ $positionStats['pay_bands'] }}</strong>
                </div>
                <div class="positions-hero__stat">
                    <span class="label">{{ __('position.index.stat_unassigned') }}</span>
                    <strong>{{ $positionStats['unassigned'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 positions-card">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="title"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._select name="department_id" allowClear defaultOption
                                                  :data="$departments" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="title">{{ __('label.title') }}</th>
                   <th data-key="department" data-orderable="false">{{ __('label.department_id') }}</th>
                   <th data-key="min_salary">{{ __('label.min_salary') }}</th>
                   <th data-key="max_salary">{{ __('label.max_salary') }}</th>
                   <th data-key="grade_level">{{ __('label.grade_level') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/position/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
