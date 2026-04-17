<x-dashboard.layouts.app>
    <div class="container-fluid timesheets-page">
        <section class="timesheets-hero mb-4">
            <div>
                <h2 class="timesheets-hero__title mb-1">{{ __('timesheet.index.hero_title') }}</h2>
                <p class="timesheets-hero__subtitle mb-0">{{ __('timesheet.index.hero_subtitle') }}</p>
            </div>
            <div class="timesheets-hero__stats">
                <div class="timesheets-hero__stat">
                    <span class="label">{{ __('timesheet.index.stat_total') }}</span>
                    <strong>{{ $timesheetStats['total'] }}</strong>
                </div>
                <div class="timesheets-hero__stat">
                    <span class="label">{{ __('timesheet.index.stat_hours') }}</span>
                    <strong>{{ $timesheetStats['hours'] }}<span class="fw-normal opacity-75">h</span></strong>
                </div>
                <div class="timesheets-hero__stat">
                    <span class="label">{{ __('timesheet.index.stat_this_month') }}</span>
                    <strong>{{ $timesheetStats['this_month'] }}</strong>
                </div>
                <div class="timesheets-hero__stat">
                    <span class="label">{{ __('timesheet.index.stat_tasks') }}</span>
                    <strong>{{ $timesheetStats['tasks_touched'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 timesheets-card">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-6 col-lg-3 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-6 col-lg-3 form-group">
                        <x-dashboard.form._select name="user_id" allowClear defaultOption
                                                  :data="$users" class="select2"/>
                    </div>

                    <div class="col-md-6 col-lg-3 form-group">
                        <x-dashboard.form._select name="task_id" allowClear defaultOption
                                                  :data="$tasks" class="select2"/>
                    </div>

                    <div class="col-md-6 col-lg-3 form-group">
                        <x-dashboard.form._input name="date" type="date"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="user" data-orderable="false">{{ __('label.user') }}</th>
                   <th data-key="task" data-orderable="false">{{ __('label.task') }}</th>
                   <th data-key="date">{{ __('label.date') }}</th>
                   <th data-key="start_time">{{ __('label.start_time') }}</th>
                   <th data-key="end_time">{{ __('label.end_time') }}</th>
                   <th data-key="duration_minutes">{{ __('label.duration_minutes') }}</th>
                   <th data-key="note">{{ __('label.note') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/timesheet/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
