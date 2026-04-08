<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :createRoute="route('dashboard.leave-requests.create')"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="user_id" type="number"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._select name="type" allowClear defaultOption
                                                  :data="$leaveRequestTypes" class="select2"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._select name="status" allowClear defaultOption
                                                  :data="$leaveRequestStatuses" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="user_id">{{ __('label.user_id') }}</th>
                   <th data-key="type_display" data-orderable="false">{{ __('label.type') }}</th>
                   <th data-key="status_display" data-orderable="false">{{ __('label.status') }}</th>
                   <th data-key="start_date">{{ __('label.start_date') }}</th>
                   <th data-key="end_date">{{ __('label.end_date') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/leave-request/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>




