@php
    use App\Models\Salary\Enums\SalaryChangeReason;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.salaries.store') : route('dashboard.salaries.update', $salary->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.salaries.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="user_id" title="user" :data="$users" :value="$salary->user_id ?? ''"
                                                      allowClear defaultOption class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="change_reason" :data="$salaryChangeReasonOptions"
                                                      :value="$salary->change_reason ?? SalaryChangeReason::ANNUAL" class="select2"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="amount" type="number" step="0.01" :value="$salary->amount"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="effective_date" type="date" :value="$salary->effective_date?->format('Y-m-d')"/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/salary/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
