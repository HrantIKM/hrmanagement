<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header title="Meetings Calendar"/>
            <div class="card-body">
                <p class="text-muted small mb-3">{{ __('leaveRequest.calendar_hint') }}</p>
                <div id="meetings-calendar"
                     data-feed-url="{{ route('dashboard.meetings.calendarFeed') }}"
                     data-move-url-template="{{ route('dashboard.meetings.move', ':id') }}"></div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
        <script src="{{ asset('/js/dashboard/meeting/calendar.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
