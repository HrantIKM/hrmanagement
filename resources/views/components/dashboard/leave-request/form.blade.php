@php
    use App\Models\LeaveRequest\Enums\LeaveRequestStatus;
    use App\Models\RoleAndPermission\Enums\RoleType;
    $isLeaveAdmin = auth()->user()?->hasRole(RoleType::ADMIN) ?? false;
    $isLeaveAdminViewer = $isLeaveAdmin
        && isset($leaveRequest->id)
        && (int) $leaveRequest->user_id !== (int) auth()->id();
    $showQuickDecision = $isLeaveAdmin
        && ($viewMode ?? '') === 'edit'
        && isset($leaveRequest->id)
        && $leaveRequest->status === LeaveRequestStatus::PENDING;
@endphp
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
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="control-label">{{ __('leaveRequest.requesting_employee') }}</label>
                            <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                                {{ $leaveRequest->user?->name ?? auth()->user()->name }}
                            </div>
                            <p class="form-text text-muted small mb-0">{{ $isLeaveAdminViewer ? __('leaveRequest.requesting_employee_help_admin') : __('leaveRequest.requesting_employee_help') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="type" :data="$leaveRequestTypeOptions"
                                                      :value="$leaveRequest->type" class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        @if($isLeaveAdmin)
                            <div class="form-group required">
                                <x-dashboard.form._select name="status" :data="$leaveRequestStatusOptions"
                                                          :value="$leaveRequest->status" class="select2"/>
                            </div>
                        @elseif(($viewMode ?? '') === 'show')
                            <div class="form-group">
                                <label class="control-label">{{ __('label.status') }}</label>
                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                                    {{ $leaveRequest->status_display }}
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <label class="control-label">{{ __('label.status') }}</label>
                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                                    {{ __('leaveRequest.status_readonly_employee') }}
                                </div>
                                <input type="hidden" name="status" value="{{ LeaveRequestStatus::PENDING }}"/>
                            </div>
                        @endif
                    </div>
                </div>

                @if($isLeaveAdmin)
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <x-dashboard.form._select name="approved_by" :data="$approverUsers"
                                                          :value="$leaveRequest->approved_by"
                                                          class="select2" defaultOption allowClear/>
                            </div>
                        </div>
                    </div>
                @endif

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

            @if($showQuickDecision)
                <div class="card-footer d-flex flex-wrap gap-2 align-items-center">
                    <span class="text-muted me-2">{{ __('leaveRequest.approve') }} / {{ __('leaveRequest.reject') }}:</span>
                    <form id="leave-request-approve-form" method="post" action="{{ route('dashboard.leave-requests.approve', $leaveRequest) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">{{ __('leaveRequest.approve') }}</button>
                    </form>
                    <form id="leave-request-reject-form" method="post" action="{{ route('dashboard.leave-requests.reject', $leaveRequest) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">{{ __('leaveRequest.reject') }}</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/leave-request/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>


