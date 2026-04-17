<x-dashboard.layouts.app>
    <div class="container-fluid projects-page">
        <section class="projects-hero mb-4">
            <div>
                <h2 class="projects-hero__title mb-1">{{ __('project.index.hero_title') }}</h2>
                <p class="projects-hero__subtitle mb-0">{{ __('project.index.hero_subtitle') }}</p>
            </div>
            <div class="projects-hero__stats">
                <div class="projects-hero__stat">
                    <span class="label">{{ __('project.index.stat_total') }}</span>
                    <strong>{{ $projectStats['total'] }}</strong>
                </div>
                <div class="projects-hero__stat">
                    <span class="label">{{ __('project.index.stat_planning') }}</span>
                    <strong>{{ $projectStats['planning'] }}</strong>
                </div>
                <div class="projects-hero__stat">
                    <span class="label">{{ __('project.index.stat_active') }}</span>
                    <strong>{{ $projectStats['active'] }}</strong>
                </div>
                <div class="projects-hero__stat">
                    <span class="label">{{ __('project.index.stat_completed') }}</span>
                    <strong>{{ $projectStats['completed'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 projects-card">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="name"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._select name="status" allowClear defaultOption
                                                  :data="$projectStatuses" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="name">{{ __('label.name') }}</th>
                   <th data-key="status_display" data-orderable="false">{{ __('label.status') }}</th>
                   <th data-key="start_date">{{ __('label.start_date') }}</th>
                   <th data-key="end_date">{{ __('label.end_date') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/project/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
