<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <x-dashboard.layouts.partials.card-header title="Task Details"/>
                    <div class="card-body">
                        <h5 class="mb-2">{{ $task->title }}</h5>
                        <div class="mb-2 text-muted">{{ $task->project?->name }} | {{ $task->user?->name }}</div>
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ $task->priority_display }}</span>
                            <span class="badge bg-primary">{{ $task->status_display }}</span>
                        </div>
                        <p class="mb-0">{{ $task->description }}</p>
                    </div>
                </div>

                <div class="card mb-4">
                    <x-dashboard.layouts.partials.card-header title="Logged Time"/>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>{{ __('label.date') }}</th>
                                    <th>{{ __('label.user') }}</th>
                                    <th>{{ __('label.duration_minutes') }}</th>
                                    <th>{{ __('label.note') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($task->timesheets as $timesheet)
                                    <tr>
                                        <td>{{ $timesheet->date?->format('Y-m-d') }}</td>
                                        <td>{{ $timesheet->user?->name }}</td>
                                        <td>{{ $timesheet->duration_minutes }}</td>
                                        <td>{{ $timesheet->note }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-muted">No time logs yet.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <x-dashboard.layouts.partials.card-header title="Log Time"/>
                    <div class="card-body">
                        <a href="{{ route('dashboard.timesheets.create', ['task_id' => $task->id, 'user_id' => $task->user_id, 'date' => $timesheetDefaultDate]) }}"
                           class="btn btn-primary w-100">
                            Log Time
                        </a>
                        <small class="text-muted d-block mt-2">This opens a prefilled timesheet for this task.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.layouts.app>
