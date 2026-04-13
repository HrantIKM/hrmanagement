@php
    use App\Models\Vacancy\Enums\VacancyStatus;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.vacancies.store') : route('dashboard.vacancies.update', $vacancy->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.vacancies.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="title" :value="$vacancy->title"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._select name="position_id" allowClear defaultOption
                                                      :data="$positions" :value="$vacancy->position_id" class="select2"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="status" :data="$vacancyStatusOptions"
                                                      :value="$vacancy->status ?? VacancyStatus::OPEN" class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._input name="closing_date" type="date" :value="$vacancy->closing_date?->format('Y-m-d')"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._textarea name="description" :value="$vacancy->description" rows="8" id="vacancy-description-editor"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._select name="skill_ids[]" :data="$skills" :value="$vacancySkillIds ?? ''"
                                                      multiple class="select2" allowClear/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/vacancy/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>

