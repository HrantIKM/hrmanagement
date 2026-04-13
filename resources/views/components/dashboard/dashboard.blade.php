<x-dashboard.layouts.app>
    @php
        $departmentCount = count($departmentHeadcount ?? []);
        $taskCount = (int) array_sum(array_values($taskStatusBreakdown ?? []));
        $leaveCount = (int) array_sum(array_values($leaveStatusBreakdown ?? []));
        $meetingCount = (int) array_sum(array_values($meetingStatusBreakdown ?? []));
        $attendanceCurrentMonth = (float) collect($monthlyAttendanceHours ?? [])->last();
    @endphp
    <div class="container-fluid dashboard-home">
        <div class="dashboard-home-hero card mb-4 border-0 shadow-sm">
            <div class="card-body py-4">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-8">
                        <h2 class="dashboard-home-title mb-1">Welcome back, {{ auth()->user()->first_name ?? 'Team' }}!</h2>
                        <p class="text-muted mb-0">Workforce overview, productivity trends, and quick actions in one place.</p>
                    </div>
                    <div class="col-lg-4">
                        <div class="dashboard-quick-actions d-flex flex-wrap justify-content-lg-end gap-2">
                            <button type="button" class="btn btn-success" id="quick-clock-in" data-url="{{ route('dashboard.attendances.clockIn') }}">
                                Clock In
                            </button>
                            <button type="button" class="btn btn-warning" id="quick-clock-out" data-url="{{ route('dashboard.attendances.clockOut') }}">
                                Clock Out
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="dashboard-stat-card card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="dashboard-stat-label">Departments</div>
                        <div class="dashboard-stat-value">{{ $departmentCount }}</div>
                        <div class="dashboard-stat-meta">People distribution overview</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="dashboard-stat-card card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="dashboard-stat-label">Tasks</div>
                        <div class="dashboard-stat-value">{{ $taskCount }}</div>
                        <div class="dashboard-stat-meta">Across backlog, in-progress, done</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="dashboard-stat-card card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="dashboard-stat-label">Leave Requests</div>
                        <div class="dashboard-stat-value">{{ $leaveCount }}</div>
                        <div class="dashboard-stat-meta">Pending, approved, or rejected</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="dashboard-stat-card card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="dashboard-stat-label">Attendance (This Month)</div>
                        <div class="dashboard-stat-value">{{ number_format($attendanceCurrentMonth, 1) }}h</div>
                        <div class="dashboard-stat-meta">{{ $meetingCount }} meetings tracked</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-xl-7">
                <div class="card border-0 shadow-sm h-100">
                    <x-dashboard.layouts.partials.card-header title="Attendance Trend (Last 6 Months)"/>
                    <div class="card-body">
                        <canvas id="attendance-trend-chart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="card border-0 shadow-sm h-100">
                    <x-dashboard.layouts.partials.card-header title="Department Headcount"/>
                    <div class="card-body">
                        <canvas id="department-headcount-chart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <x-dashboard.layouts.partials.card-header title="Task Status Snapshot"/>
                    <div class="card-body">
                        <canvas id="task-status-chart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <x-dashboard.layouts.partials.card-header title="Leave vs Meeting Status"/>
                    <div class="card-body">
                        <canvas id="leave-meeting-chart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <x-dashboard.layouts.partials.card-header title="Company Skill Heatmap"/>
                    <div class="card-body">
                        <canvas id="skill-heatmap-chart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <x-dashboard.layouts.partials.card-header title="Top Contributors (Timesheet Hours)"/>
                    <div class="card-body">
                        @if(($topContributors ?? collect())->isEmpty())
                            <div class="text-muted">No timesheet data yet.</div>
                        @else
                            @foreach($topContributors as $member)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>{{ $member['name'] }}</span>
                                        <span class="fw-semibold">{{ $member['hours'] }}h</span>
                                    </div>
                                    <div class="progress dashboard-soft-progress">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, $member['hours']) }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <x-dashboard.layouts.partials.card-header title="Recent System Activity"/>
                    <div class="card-body dashboard-activity-feed">
                        @if(($activities ?? collect())->isEmpty())
                            <div class="text-muted">No recent activity yet.</div>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($activities as $activity)
                                    <li class="list-group-item px-0">
                                        <div class="fw-semibold">{{ $activity->description }}</div>
                                        <small class="text-muted">
                                            {{ class_basename($activity->subject_type ?? 'System') }} #{{ $activity->subject_id ?? '-' }}
                                            | {{ $activity->created_at?->format('Y-m-d H:i') }}
                                        </small>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border-0 shadow-sm">
            <x-dashboard.layouts.partials.card-header title="Project Progress"/>
            <div class="card-body">
                @forelse($projectProgress as $project)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $project['name'] }}</span>
                            <span class="fw-semibold">{{ $project['percentage'] }}%</span>
                        </div>
                        <div class="progress dashboard-soft-progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $project['percentage'] }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">No project progress data available.</div>
                @endforelse
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            (function () {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const chartFontColor = '#6b7280';
                const palette = ['#4f46e5', '#06b6d4', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6', '#14b8a6'];

                const run = async (btn) => {
                    const response = await fetch(btn.dataset.url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    let result = {};
                    try {
                        result = await response.json();
                    } catch (e) {
                        result = {};
                    }
                    const msg = result.message || (response.ok ? 'Done' : 'Something went wrong.');
                    if (typeof showSuccessMessage === 'function' && response.ok) {
                        showSuccessMessage(msg);
                    } else if (typeof showErrorMessage === 'function' && !response.ok) {
                        showErrorMessage(msg);
                    } else {
                        alert(msg);
                    }
                };

                document.getElementById('quick-clock-in')?.addEventListener('click', function () { run(this); });
                document.getElementById('quick-clock-out')?.addEventListener('click', function () { run(this); });

                Chart.defaults.color = chartFontColor;
                Chart.defaults.borderColor = '#eef2f7';

                const departmentData = @json($departmentHeadcount ?? []);
                const skillData = @json($skillHeatmap ?? []);
                const taskStatusData = @json($taskStatusBreakdown ?? []);
                const leaveStatusData = @json($leaveStatusBreakdown ?? []);
                const meetingStatusData = @json($meetingStatusBreakdown ?? []);
                const attendanceData = @json($monthlyAttendanceHours ?? []);

                const headcountCtx = document.getElementById('department-headcount-chart');
                if (headcountCtx && Object.keys(departmentData).length > 0) {
                    new Chart(headcountCtx, {
                        type: 'doughnut',
                        data: {
                            labels: Object.keys(departmentData),
                            datasets: [{ data: Object.values(departmentData), backgroundColor: palette }],
                        },
                        options: { plugins: { legend: { position: 'bottom' } } },
                    });
                }

                const skillCtx = document.getElementById('skill-heatmap-chart');
                if (skillCtx && Object.keys(skillData).length > 0) {
                    new Chart(skillCtx, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(skillData),
                            datasets: [{ data: Object.values(skillData), backgroundColor: '#6366f1', borderRadius: 6 }],
                        },
                        options: {
                            indexAxis: 'y',
                            plugins: { legend: { display: false } },
                        },
                    });
                }

                const taskStatusCtx = document.getElementById('task-status-chart');
                if (taskStatusCtx && Object.keys(taskStatusData).length > 0) {
                    new Chart(taskStatusCtx, {
                        type: 'polarArea',
                        data: {
                            labels: Object.keys(taskStatusData),
                            datasets: [{ data: Object.values(taskStatusData), backgroundColor: palette }],
                        },
                        options: { plugins: { legend: { position: 'bottom' } } },
                    });
                }

                const attendanceTrendCtx = document.getElementById('attendance-trend-chart');
                if (attendanceTrendCtx && Object.keys(attendanceData).length > 0) {
                    new Chart(attendanceTrendCtx, {
                        type: 'line',
                        data: {
                            labels: Object.keys(attendanceData),
                            datasets: [{
                                label: 'Hours worked',
                                data: Object.values(attendanceData),
                                fill: true,
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37,99,235,0.14)',
                                tension: 0.35,
                                pointRadius: 4,
                            }],
                        },
                        options: {
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true } },
                        },
                    });
                }

                const leaveMeetingCtx = document.getElementById('leave-meeting-chart');
                if (leaveMeetingCtx && (Object.keys(leaveStatusData).length > 0 || Object.keys(meetingStatusData).length > 0)) {
                    const labels = Array.from(new Set([
                        ...Object.keys(leaveStatusData),
                        ...Object.keys(meetingStatusData),
                    ]));
                    new Chart(leaveMeetingCtx, {
                        type: 'bar',
                        data: {
                            labels,
                            datasets: [
                                {
                                    label: 'Leave requests',
                                    data: labels.map((l) => leaveStatusData[l] || 0),
                                    backgroundColor: '#14b8a6',
                                    borderRadius: 6,
                                },
                                {
                                    label: 'Meetings',
                                    data: labels.map((l) => meetingStatusData[l] || 0),
                                    backgroundColor: '#f97316',
                                    borderRadius: 6,
                                },
                            ],
                        },
                        options: {
                            plugins: { legend: { position: 'bottom' } },
                            scales: { y: { beginAtZero: true } },
                        },
                    });
                }
            })();
        </script>
    </x-slot>
</x-dashboard.layouts.app>




