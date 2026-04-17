<x-dashboard.layouts.app>
    <div class="container-fluid meetings-calendar-page">
        <section class="meetings-hero meetings-hero--calendar mb-4">
            <div class="flex-grow-1">
                <h2 class="meetings-hero__title mb-1">{{ __('meeting.calendar.hero_title') }}</h2>
                <p class="meetings-hero__subtitle mb-3 mb-md-2">{{ __('meeting.calendar.hero_subtitle') }}</p>
                <div class="meetings-hero__meta">
                    <a href="{{ $meetingsTableRoute }}" class="btn btn-light btn-sm">{{ __('meeting.calendar.open_table') }}</a>
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
            <div class="card-body p-3 p-md-4">
                <div class="meetings-calendar-surface">
                    <div id="meetings-calendar"
                         data-feed-url="{{ route('dashboard.meetings.calendarFeed') }}"
                         data-move-url-template="{{ route('dashboard.meetings.move', ':id') }}"></div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
        <script src="{{ asset('/js/dashboard/meeting/calendar.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
