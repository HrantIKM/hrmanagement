<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.holidays.store') : route('dashboard.holidays.update', $holiday->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.holidays.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="name" :value="$holiday->name"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="date" type="date" :value="$holiday->date?->format('Y-m-d')"/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/holiday/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>


