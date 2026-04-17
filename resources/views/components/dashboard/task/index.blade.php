<x-dashboard.layouts.app>
    <div class="container-fluid tasks-index-page">
        <section class="tasks-index-hero mb-4">
            <div class="flex-grow-1">
                <h2 class="tasks-index-hero__title mb-1">{{ __('task.index.hero_title') }}</h2>
                <p class="tasks-index-hero__subtitle mb-3 mb-md-2">{{ __('task.index.hero_subtitle') }}</p>
                <div class="tasks-index-hero__meta">
                    <a href="{{ $boardRoute }}" class="btn btn-light btn-sm">{{ __('task.index.open_board') }}</a>
                </div>
            </div>
            <div class="tasks-index-hero__stats">
                <div class="tasks-index-hero__stat">
                    <span class="label">{{ __('task.index.stat_total') }}</span>
                    <strong>{{ $taskStats['total'] }}</strong>
                </div>
                <div class="tasks-index-hero__stat">
                    <span class="label">{{ __('task.index.stat_open') }}</span>
                    <strong>{{ $taskStats['open'] }}</strong>
                </div>
                <div class="tasks-index-hero__stat">
                    <span class="label">{{ __('task.index.stat_done') }}</span>
                    <strong>{{ $taskStats['done'] }}</strong>
                </div>
                <div class="tasks-index-hero__stat">
                    <span class="label">{{ __('task.index.stat_overdue') }}</span>
                    <strong>{{ $taskStats['overdue'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 tasks-index-card">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-6 col-lg-4 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-6 col-lg-4 form-group">
                        <x-dashboard.form._input name="title"/>
                    </div>

                    <div class="col-md-6 col-lg-4 form-group">
                        <x-dashboard.form._select name="project_id" allowClear defaultOption
                                                  :data="$projects" class="select2"/>
                    </div>

                    <div class="col-md-6 col-lg-4 form-group">
                        <x-dashboard.form._select name="user_id" allowClear defaultOption
                                                  :data="$users" class="select2"/>
                    </div>

                    <div class="col-md-6 col-lg-4 form-group">
                        <x-dashboard.form._select name="priority" allowClear defaultOption
                                                  :data="$taskPriorities" class="select2"/>
                    </div>

                    <div class="col-md-6 col-lg-4 form-group">
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
