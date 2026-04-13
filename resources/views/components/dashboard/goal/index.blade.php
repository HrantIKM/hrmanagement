<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._input name="title"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._select name="user_id" allowClear defaultOption
                                                  :data="$users" class="select2"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
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
