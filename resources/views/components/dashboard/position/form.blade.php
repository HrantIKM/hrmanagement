<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.positions.store') : route('dashboard.positions.update', $position->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.positions.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="title" :value="$position->title"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._select name="department_id" allowClear defaultOption
                                                      :data="$departments" :value="$position->department_id" class="select2"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <x-dashboard.form._input name="min_salary" type="number" decimal :value="$position->min_salary"/>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <x-dashboard.form._input name="max_salary" type="number" decimal :value="$position->max_salary"/>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <x-dashboard.form._input name="grade_level" :value="$position->grade_level"/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/position/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>

