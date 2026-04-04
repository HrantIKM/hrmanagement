<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :createRoute="route('dashboard.tasks.create')"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._input name="title"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._select name="project_id" allowClear defaultOption
                                                  :data="$projects" class="select2"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._select name="user_id" allowClear defaultOption
                                                  :data="$users" class="select2"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._select name="priority" allowClear defaultOption
                                                  :data="$taskPriorities" class="select2"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._select name="status" allowClear defaultOption
                                                  :data="$taskStatuses" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="title">{{ __('label.title') }}</th>
                   <th data-key="project" data-orderable="false">{{ __('label.project') }}</th>
                   <th data-key="user" data-orderable="false">{{ __('label.assignee') }}</th>
                   <th data-key="priority_display" data-orderable="false">{{ __('label.priority') }}</th>
                   <th data-key="status_display" data-orderable="false">{{ __('label.task_status') }}</th>
                   <th data-key="due_date">{{ __('label.due_date') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/task/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>

