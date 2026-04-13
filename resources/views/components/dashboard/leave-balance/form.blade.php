<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.leave-balances.store') : route('dashboard.leave-balances.update', $leaveBalance->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.leave-balances.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="user_id" :data="$users" :value="$leaveBalance->user_id" class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="year" type="number" :value="$leaveBalance->year ?? now()->year"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="total_days" type="number" :value="$leaveBalance->total_days ?? 20" step="0.01"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="used_days" type="number" :value="$leaveBalance->used_days ?? 0" step="0.01"/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/leave-balance/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>


