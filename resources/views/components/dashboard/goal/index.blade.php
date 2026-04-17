<x-dashboard.layouts.app>
    <div class="container-fluid goals-page">
        <section class="goals-hero mb-4">
            <div>
                <h2 class="goals-hero__title mb-1">{{ __('goal.index.hero_title') }}</h2>
                <p class="goals-hero__subtitle mb-0">{{ __('goal.index.hero_subtitle') }}</p>
            </div>
            <div class="goals-hero__stats">
                <div class="goals-hero__stat">
                    <span class="label">{{ __('goal.index.stat_total') }}</span>
                    <strong>{{ $goalStats['total'] }}</strong>
                </div>
                <div class="goals-hero__stat">
                    <span class="label">{{ __('goal.index.stat_avg_progress') }}</span>
                    <strong>{{ $goalStats['avg_progress'] !== null ? $goalStats['avg_progress'] . '%' : '—' }}</strong>
                </div>
                <div class="goals-hero__stat">
                    <span class="label">{{ __('goal.index.stat_achieved') }}</span>
                    <strong>{{ $goalStats['achieved'] }}</strong>
                </div>
                <div class="goals-hero__stat">
                    <span class="label">{{ __('goal.index.stat_overdue') }}</span>
                    <strong>{{ $goalStats['overdue'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 goals-card">
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
                        <x-dashboard.form._select name="user_id" allowClear defaultOption
                                                  :data="$users" class="select2"/>
                    </div>

                    <div class="col-md-3 form-group">
                        <x-dashboard.form._select name="type" allowClear defaultOption
                                                  :data="$goalTypeOptions" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="title">{{ __('label.title') }}</th>
                   <th data-key="user" data-orderable="false">{{ __('label.user') }}</th>
                   <th data-key="type_display" data-orderable="false">{{ __('label.goal_type') }}</th>
                   <th data-key="target_value">{{ __('label.target_value') }}</th>
                   <th data-key="current_value">{{ __('label.current_value') }}</th>
                   <th data-key="progress_percent" data-orderable="false">{{ __('label.progress_percent') }}</th>
                   <th data-key="deadline">{{ __('label.deadline') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/goal/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
