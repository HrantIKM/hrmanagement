<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.leave-requests.store') : route('dashboard.leave-requests.update', $leaveRequest->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.leave-requests.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="user_id" :data="$users" :value="$leaveRequest->user_id"
                                                      class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="type" :data="$leaveRequestTypeOptions"
                                                      :value="$leaveRequest->type" class="select2"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="status" :data="$leaveRequestStatusOptions"
                                                      :value="$leaveRequest->status" class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._select name="approved_by" :data="$users"
                                                      :value="$leaveRequest->approved_by"
                                                      class="select2" defaultOption allowClear/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="start_date" type="date" :value="$leaveRequest->start_date?->format('Y-m-d')"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="end_date" type="date" :value="$leaveRequest->end_date?->format('Y-m-d')"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._textarea name="reason" :value="$leaveRequest->reason" rows="4"/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/leave-request/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>


