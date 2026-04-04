@php
    use App\Models\Project\Enums\ProjectStatus;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.projects.store') : route('dashboard.projects.update', $project->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.projects.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form.uploader._file
                                name="icon"
                                :configKey="$project->getFileConfigName()"
                                :value="$project->icon"
                            />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="name" :value="$project->name"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="status" :data="$projectStatusOptions"
                                                      :value="$project->status ?? ProjectStatus::PLANNING" class="select2"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._input name="start_date" type="date" :value="$project->start_date?->format('Y-m-d')"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._input name="end_date" type="date" :value="$project->end_date?->format('Y-m-d')"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._textarea name="description" :value="$project->description" rows="5"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._select name="user_ids[]" title="team_members" :data="$users" :value="$projectUserIds ?? ''"
                                                      multiple class="select2" allowClear/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/project/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>

