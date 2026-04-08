<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :createRoute="route('dashboard.skills.create')"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._input name="name"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._select name="department_id" allowClear defaultOption
                                                  :data="$departments" class="select2"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._select name="category" allowClear defaultOption
                                                  :data="$skillCategories" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="name">{{ __('label.name') }}</th>
                   <th data-key="department" data-orderable="false">{{ __('label.department_id') }}</th>
                   <th data-key="category_label" data-orderable="false">{{ __('label.category') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/skill/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
