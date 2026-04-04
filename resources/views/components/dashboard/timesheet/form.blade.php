@php
    $fmtTime = static function ($v): string {
        if ($v === null || $v === '') {
            return '';
        }
        $s = (string) $v;

        return strlen($s) >= 5 ? substr($s, 0, 5) : $s;
    };
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.timesheets.store') : route('dashboard.timesheets.update', $timesheet->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.timesheets.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group required">
                            <x-dashboard.form._select name="user_id" :data="$users" :value="$timesheet->user_id" class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <x-dashboard.form._select name="task_id" allowClear defaultOption
                                                      :data="$tasks" :value="$timesheet->task_id" class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group required">
                            <x-dashboard.form._input name="date" type="date" :value="$timesheet->date?->format('Y-m-d')"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <x-dashboard.form._input name="start_time" type="time" :value="$fmtTime($timesheet->start_time)"/>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <x-dashboard.form._input name="end_time" type="time" :value="$fmtTime($timesheet->end_time)"/>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <x-dashboard.form._input name="duration_minutes" type="number" :value="$timesheet->duration_minutes"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._textarea name="note" :value="$timesheet->note" rows="4"/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/timesheet/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>

