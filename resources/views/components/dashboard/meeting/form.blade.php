@php
    use App\Models\Meeting\Enums\MeetingStatus;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.meetings.store') : route('dashboard.meetings.update', $meeting->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.meetings.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="title" :value="$meeting->title"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="status" :data="$meetingStatusOptions"
                                                      :value="$meeting->status ?? MeetingStatus::SCHEDULED" class="select2"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="start_at" type="datetime-local"
                                                     :value="$meeting->start_at?->format('Y-m-d\TH:i')"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="end_at" type="datetime-local"
                                                     :value="$meeting->end_at?->format('Y-m-d\TH:i')"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._select name="room_id" :data="$rooms" allowClear defaultOption
                                                      :value="$meeting->room_id" class="select2"
                                                      title="room_id"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <x-dashboard.form._input name="location" :value="$meeting->location"
                                                     title="meeting_link_notes"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._textarea name="description" :value="$meeting->description" rows="4"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._textarea name="summary" :value="$meeting->summary" rows="6"/>
                            <div class="alert alert-light border small mt-2 mb-2 text-body meeting-summary-help">
                                <p class="fw-semibold mb-2">{{ __('meeting.summary_help.title') }}</p>
                                <p class="mb-2">{{ __('meeting.summary_help.minutes') }}</p>
                                <p class="fw-semibold mb-1">{{ __('meeting.summary_help.action_title') }}</p>
                                <p class="mb-2">{{ __('meeting.summary_help.action_intro') }}</p>
                                <ul class="mb-2 ps-3">
                                    <li class="mb-2">
                                        {{ __('meeting.summary_help.format_checkbox') }}
                                        <pre class="small bg-white border rounded p-2 mt-1 mb-0 user-select-all">- [ ] Send the signed contract to Finance
* [ ] Schedule the follow-up review</pre>
                                    </li>
                                    <li>
                                        {{ __('meeting.summary_help.format_todo') }}
                                        <pre class="small bg-white border rounded p-2 mt-1 mb-0 user-select-all">Todo: Update the project timeline in the shared doc</pre>
                                    </li>
                                </ul>
                                <p class="mb-0 text-muted">{{ __('meeting.summary_help.assignee_note') }}</p>
                            </div>
                            @if(isset($meeting) && $meeting->id)
                                <button type="button"
                                        class="btn btn-outline-primary btn-sm mt-1"
                                        id="meeting-action-items-btn"
                                        data-url="{{ route('dashboard.meetings.createActionItems', $meeting->id) }}">
                                    {{ __('meeting.convert_minutes_to_tasks') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._select name="user_ids[]" title="participants"
                                                      :data="$users" :value="$meetingUserIds ?? ''"
                                                      multiple class="select2" allowClear/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/meeting/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>


