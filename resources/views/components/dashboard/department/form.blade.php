<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.departments.store') : route('dashboard.departments.update', $department->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.departments.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form.uploader._file
                                name="icon"
                                :configKey="$department->getFileConfigName()"
                                :value="$department->icon"
                            />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label" for="parent_id">{{ __('label.parent_department') }}</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="">{{ __('department.root_badge') }}</option>
                                @foreach($parentDepartments ?? [] as $opt)
                                    <option value="{{ $opt['id'] }}" @selected((int) ($department->parent_id ?? 0) === (int) $opt['id'])>
                                        {{ $opt['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="name" :value="$department->name"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._textarea name="description" :value="$department->description" rows="5"/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/department/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>

