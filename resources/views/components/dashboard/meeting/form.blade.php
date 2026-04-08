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
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._input name="location" :value="$meeting->location"/>
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


