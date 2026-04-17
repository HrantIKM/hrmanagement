<x-dashboard.layouts.app>
    <div class="container-fluid attendance-page">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-3">{{ session('error') }}</div>
        @endif

        <section class="attendance-hero mb-4">
            <div class="attendance-hero__content">
                <h2 class="attendance-hero__title mb-1">Attendance Hub</h2>
                <p class="attendance-hero__subtitle mb-0">Track daily presence, clock sessions, and spot gaps quickly.</p>
                <div class="attendance-hero__status mt-2">
                    <span class="status-pill status-pill--{{ str_replace('_', '-', $todayAttendance?->status ?? 'pending') }}">
                        {{ $todayAttendance?->status_display ?? 'Not clocked in yet' }}
                    </span>
                    <span class="attendance-hero__status-meta">
                        @if($todayAttendance?->clock_in)
                            In: {{ $todayAttendance->clock_in->format('H:i') }}
                        @endif
                        @if($todayAttendance?->clock_out)
                            | Out: {{ $todayAttendance->clock_out->format('H:i') }}
                        @endif
                        @if($todayAttendance?->total_hours !== null)
                            | Hours: {{ $todayAttendance->total_hours }}
                        @endif
                    </span>
                </div>
            </div>
            <div class="attendance-hero__actions">
                <form action="{{ route('dashboard.attendances.clockIn') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm attendance-hero__btn" @disabled(!$canClockIn)>Clock in</button>
                </form>
                <form action="{{ route('dashboard.attendances.clockOut') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm attendance-hero__btn" @disabled(!$canClockOut)>Clock out</button>
                </form>
                <a href="{{ route('dashboard.attendances.create') }}" class="btn btn-primary btn-sm attendance-hero__btn">Add record</a>
            </div>
        </section>

        <div class="attendance-layout">
            <div class="attendance-layout__calendar">
                <div class="card attendance-card mb-4 mb-lg-0">
                    <div class="attendance-card__head">
                        <h5 class="mb-0">Attendance calendar</h5>
                        <p class="attendance-card__hint mb-0">Shows attendance, approved leave, holidays, and meetings in one timeline for quick planning.</p>
                    </div>
                    <div class="card-body">
                        <div id="attendance-calendar" data-feed-url="{{ route('dashboard.attendances.calendarFeed') }}"></div>
                    </div>
                </div>
            </div>
            <div class="attendance-layout__records">
                <div class="card attendance-card">
                    <x-dashboard.layouts.partials.card-header :createRoute="route('dashboard.attendances.create')"/>
                    <div class="card-body">
                        <x-dashboard.datatable._filters_form>
                            <div class="col-md-4 form-group">
                                <x-dashboard.form._input name="id" type="number"/>
                            </div>

                            <div class="col-md-4 form-group">
                                <x-dashboard.form._input name="user_id" type="number"/>
                            </div>

                            <div class="col-md-4 form-group">
                                <x-dashboard.form._input name="date" type="date"/>
                            </div>

                            <div class="col-md-4 form-group">
                                <x-dashboard.form._select name="status" allowClear defaultOption
                                                          :data="$attendanceStatuses" class="select2"/>
                            </div>
                        </x-dashboard.datatable._filters_form>

                        <x-dashboard.datatable._table>
                           <th data-key="id">{{ __('label.id') }}</th>
                           <th data-key="user_id" data-orderable="false">{{ __('label.user') }}</th>
                           <th data-key="date">{{ __('label.date') }}</th>
                           <th data-key="clock_in">{{ __('label.clock_in') }}</th>
                           <th data-key="clock_out">{{ __('label.clock_out') }}</th>
                           <th data-key="total_hours">{{ __('label.total_hours') }}</th>
                           <th data-key="status_display" data-orderable="false">{{ __('label.status') }}</th>
                           <th class="text-center">{{ __('label.actions') }}</th>
                        </x-dashboard.datatable._table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
        <script src="{{ asset('/js/dashboard/attendance/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>




