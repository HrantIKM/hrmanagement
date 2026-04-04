<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :createRoute="route('dashboard.timesheets.create')"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._select name="user_id" allowClear defaultOption
                                                  :data="$users" class="select2"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._select name="task_id" allowClear defaultOption
                                                  :data="$tasks" class="select2"/>
                    </div>

                    <div class="col-md-4 form-group">
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

