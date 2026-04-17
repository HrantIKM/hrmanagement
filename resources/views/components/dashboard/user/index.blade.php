<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">{{ __('page.user.index.stats_total') }}</div>
                        <div class="h4 mb-0">{{ $totalUsers }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">{{ __('page.user.index.stats_active') }}</div>
                        <div class="h4 mb-0 text-success">{{ $activeUsers }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">{{ __('page.user.index.stats_on_leave') }}</div>
                        <div class="h4 mb-0 text-warning">{{ $onLeaveUsers }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">{{ __('page.user.index.stats_with_photo') }}</div>
                        <div class="h4 mb-0">{{ $withAvatar }} <span class="fs-6 text-muted fw-normal">/ {{ $totalUsers }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <span class="fw-semibold">{{ __('page.user.index.recent_heading') }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @forelse($recentUsers as $u)
                        @php
                            $hue = ($u->id * 37) % 360;
                            $ini1 = mb_substr((string) $u->first_name, 0, 1);
                            $ini2 = mb_substr((string) $u->last_name, 0, 1);
                            $initials = mb_strtoupper($ini1 . $ini2);
                            if ($initials === '') {
                                $initials = mb_strtoupper(mb_substr((string) ($u->email ?? '?'), 0, 2));
                            }
                        @endphp
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="{{ route('dashboard.users.show', $u) }}"
                               class="text-decoration-none text-body d-block p-3 rounded border text-center h-100 user-recent-card">
                                @if($u->avatar_url)
                                    <img src="{{ $u->avatar_url }}" alt="" width="56" height="56"
                                         class="rounded-circle border mb-2 object-fit-cover">
                                @else
                                    <span
                                        class="d-inline-flex align-items-center justify-content-center rounded-circle text-white fw-semibold mb-2 user-avatar-initials"
                                        style="width:56px;height:56px;font-size:1.1rem;background:hsl({{ $hue }}, 52%, 42%)">{{ $initials }}</span>
                                @endif
                                <div class="small fw-semibold text-truncate" title="{{ $u->name }}">{{ $u->name }}</div>
                                <div class="small text-muted text-truncate"
                                     title="{{ $u->department?->name ?? '' }}">{{ $u->department?->name ?? '—' }}</div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-muted small">{{ __('page.user.index.recent_empty') }}</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card mb-4">
            @isset($createRoute)
                <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>
            @else
                <x-dashboard.layouts.partials.card-header/>
            @endisset
            @if($userExportsEnabled ?? false)
            <div class="px-4 pt-3 d-flex flex-wrap gap-2 align-items-center">
                <span class="text-muted small me-1">{{ __('page.user.index.quick_exports') }}:</span>
                <a href="{{ route('dashboard.users.exportCsv') }}" class="btn btn-outline-primary btn-sm">CSV</a>
                <a href="{{ route('dashboard.users.exportExcel') }}" class="btn btn-outline-primary btn-sm">Excel</a>
            </div>
            @endif

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="first_name"/>
                    </div>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="last_name"/>
                    </div>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="email"/>
                    </div>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="employment_status"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                    <th data-key="avatar_url" data-orderable="false" class="text-center"
                        style="width:56px">{{ __('label.avatar') }}</th>
                    <th data-key="id">{{ __('label.id') }}</th>
                    <th data-key="first_name">{{ __('label.first_name') }}</th>
                    <th data-key="last_name">{{ __('label.last_name') }}</th>
                    <th data-key="email">{{ __('label.email') }}</th>
                    <th data-key="department" data-orderable="false">{{ __('label.department_id') }}</th>
                    <th data-key="position" data-orderable="false">{{ __('label.position_id') }}</th>
                    <th data-key="employment_status_display" data-orderable="false">{{ __('label.employment_status') }}</th>
                    <th data-key="roles" data-orderable="false">{{ __('label.roles') }}</th>
                    <th data-key="created_at">{{ __('label.created_at') }}</th>
                    <th class="text-center" style="width: 90px">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/user/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
