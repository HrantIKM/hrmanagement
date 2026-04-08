<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :createRoute="route('dashboard.attendances.create')"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="user_id" type="number"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="date" type="date"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._select name="status" allowClear defaultOption
                                                  :data="$attendanceStatuses" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="user_id">{{ __('label.user_id') }}</th>
                   <th data-key="date">{{ __('label.date') }}</th>
                   <th data-key="clock_in">{{ __('label.clock_in') }}</th>
                   <th data-key="clock_out">{{ __('label.clock_out') }}</th>
                   <th data-key="total_hours">{{ __('label.total_hours') }}</th>
                   <th data-key="status_display" data-orderable="false">{{ __('label.status') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/attendance/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>




