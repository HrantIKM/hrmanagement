<x-dashboard.layouts.app>
    <div class="container-fluid meetings-page">
        <section class="meetings-hero mb-4">
            <div class="flex-grow-1">
                <h2 class="meetings-hero__title mb-1">{{ __('meeting.index.hero_title') }}</h2>
                <p class="meetings-hero__subtitle mb-3 mb-md-2">{{ __('meeting.index.hero_subtitle') }}</p>
                <div class="meetings-hero__meta">
                    <a href="{{ $calendarRoute }}" class="btn btn-light btn-sm">{{ __('meeting.index.open_calendar') }}</a>
                </div>
            </div>
            <div class="meetings-hero__stats">
                <div class="meetings-hero__stat">
                    <span class="label">{{ __('meeting.index.stat_total') }}</span>
                    <strong>{{ $meetingStats['total'] }}</strong>
                </div>
                <div class="meetings-hero__stat">
                    <span class="label">{{ __('meeting.index.stat_active') }}</span>
                    <strong>{{ $meetingStats['active'] }}</strong>
                </div>
                <div class="meetings-hero__stat">
                    <span class="label">{{ __('meeting.index.stat_completed') }}</span>
                    <strong>{{ $meetingStats['completed'] }}</strong>
                </div>
                <div class="meetings-hero__stat">
                    <span class="label">{{ __('meeting.index.stat_cancelled') }}</span>
                    <strong>{{ $meetingStats['cancelled'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 meetings-card">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="title"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._select name="status" allowClear defaultOption
                                                  :data="$meetingStatuses" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="title">{{ __('label.title') }}</th>
                   <th data-key="room" data-orderable="false">{{ __('label.room_id') }}</th>
                   <th data-key="status_display" data-orderable="false" class="text-center">{{ __('label.status') }}</th>
                   <th data-key="start_at">{{ __('label.start_at') }}</th>
                   <th data-key="end_at">{{ __('label.end_at') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/meeting/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
