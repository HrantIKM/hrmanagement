<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :createRoute="route('dashboard.positions.create')"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-3 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-3 form-group">
                        <x-dashboard.form._input name="title"/>
                    </div>

                    <div class="col-md-3 form-group">
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

