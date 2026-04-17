@php
    use App\Models\Task\Enums\TaskPriority;
    use App\Models\Task\Enums\TaskStatus;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.tasks.store') : route('dashboard.tasks.update', $task->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.tasks.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="title" :value="$task->title"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._input name="due_date" type="date" :value="$task->due_date?->format('Y-m-d')"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="priority" :data="$taskPriorityOptions"
                                                      :value="$task->priority ?? TaskPriority::LOW" class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="status" :data="$taskStatusOptions"
                                                      :value="$task->status ?? TaskStatus::TODO" class="select2"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._select name="project_id" allowClear defaultOption
                                                      :data="$projects" :value="$task->project_id" class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._select name="user_id" allowClear defaultOption
                                                      :data="$users" :value="$task->user_id" class="select2"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._textarea name="description" :value="$task->description" rows="7" class="ckeditor5"/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/task/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>

