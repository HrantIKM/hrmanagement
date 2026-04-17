<x-dashboard.layouts.app>
    <div class="container-fluid candidates-page">
        <section class="candidates-hero mb-4">
            <div>
                <h2 class="candidates-hero__title mb-1">Talent Pipeline</h2>
                <p class="candidates-hero__subtitle mb-0">Discover, compare, and shortlist top applicants faster.</p>
            </div>
            <div class="candidates-hero__stats">
                <div class="candidates-hero__stat">
                    <span class="label">Total</span>
                    <strong>{{ $candidateStats['total'] }}</strong>
                </div>
                <div class="candidates-hero__stat">
                    <span class="label">Avg match</span>
                    <strong>{{ $candidateStats['average_match'] }}%</strong>
                </div>
                <div class="candidates-hero__stat">
                    <span class="label">80%+ fit</span>
                    <strong>{{ $candidateStats['high_match'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 candidates-card">
            <x-dashboard.layouts.partials.card-header :createRoute="route('dashboard.candidates.create')"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-3 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-3 form-group">
                        <x-dashboard.form._input name="full_name"/>
                    </div>

                    <div class="col-md-3 form-group">
                        <x-dashboard.form._input name="email"/>
                    </div>

                    <div class="col-md-3 form-group">
                        <x-dashboard.form._select name="vacancy_id" allowClear defaultOption
                                                  :data="$vacancies" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="full_name">{{ __('label.full_name') }}</th>
                   <th data-key="email">{{ __('label.email') }}</th>
                   <th data-key="vacancy" data-orderable="false">{{ __('label.vacancy_id') }}</th>
                   <th data-key="match_score">{{ __('label.match_score') }}</th>
                   <th data-key="resume_path">{{ __('label.resume') }}</th>
                   <th data-key="skills" data-orderable="false">{{ __('label.skill_ids') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/candidate/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>

