@php
    $rawAiInput = old('raw_ai_data');
    if ($rawAiInput === null) {
        $rawAiInput = isset($candidate->raw_ai_data) && $candidate->raw_ai_data
            ? json_encode($candidate->raw_ai_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            : '';
    }
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.candidates.store') : route('dashboard.candidates.update', $candidate->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.candidates.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="full_name" :value="$candidate->full_name"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._input name="email" type="email" :value="$candidate->email"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._input name="resume" type="file" accept="application/pdf"/>
                            @if($candidate->resume_url)
                                <a href="{{ $candidate->resume_url }}" target="_blank" class="d-inline-block mt-2">
                                    Open current resume PDF
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._input name="match_score" type="number" :value="$candidate->match_score"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._select name="vacancy_id" allowClear defaultOption
                                                      :data="$vacancies" :value="$candidate->vacancy_id" class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._select name="skill_ids[]" :data="$skills" :value="$candidateSkillIds ?? ''"
                                                      multiple class="select2" allowClear/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._textarea name="raw_ai_data" :value="$rawAiInput" rows="12"/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/candidate/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>

