<x-dashboard.layouts.app>
    <div class="container-fluid skills-page">
        <section class="skills-hero mb-4">
            <div>
                <h2 class="skills-hero__title mb-1">{{ __('skill.index.hero_title') }}</h2>
                <p class="skills-hero__subtitle mb-0">{{ __('skill.index.hero_subtitle') }}</p>
            </div>
            <div class="skills-hero__stats">
                <div class="skills-hero__stat">
                    <span class="label">{{ __('skill.index.stat_total') }}</span>
                    <strong>{{ $skillStats['total'] }}</strong>
                </div>
                <div class="skills-hero__stat">
                    <span class="label">{{ __('skill.index.stat_technical') }}</span>
                    <strong>{{ $skillStats['technical'] }}</strong>
                </div>
                <div class="skills-hero__stat">
                    <span class="label">{{ __('skill.index.stat_soft') }}</span>
                    <strong>{{ $skillStats['soft'] }}</strong>
                </div>
                <div class="skills-hero__stat">
                    <span class="label">{{ __('skill.index.stat_language') }}</span>
                    <strong>{{ $skillStats['language'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 skills-card">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-6 col-lg-3 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-6 col-lg-3 form-group">
                        <x-dashboard.form._input name="name"/>
                    </div>

                    <div class="col-md-6 col-lg-3 form-group">
                        <x-dashboard.form._select name="department_id" allowClear defaultOption
                                                  :data="$departments" class="select2"/>
                    </div>

                    <div class="col-md-6 col-lg-3 form-group">
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
