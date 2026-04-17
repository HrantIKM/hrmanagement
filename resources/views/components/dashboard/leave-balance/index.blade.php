@php
    $currentCalendarYear = (int) now()->year;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :title="__('leaveBalance.summary_title')"/>
            <div class="card-body">
                <p class="text-muted small mb-4">{{ __('leaveBalance.summary_subtitle') }}</p>

                <div class="leave-balance-summary">
                    <div class="row g-4">
                        @forelse($balanceCards as $balance)
                            @php
                                $total = max(0.01, (float) $balance->total_days);
                                $used = (float) $balance->used_days;
                                $rem = (float) $balance->remaining_days;
                                $usedPct = min(100, round(($used / $total) * 100, 1));
                                $remRatio = $total > 0 ? $rem / $total : 0;
                                $ringColor = $remRatio > 0.33 ? '#198754' : ($remRatio > 0.1 ? '#fd7e14' : '#dc3545');
                                $barColor = $ringColor;
                            @endphp
                            <div class="col-md-6 col-xl-4">
                                <div class="leave-balance-card card border-0 shadow-sm h-100">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                                    <span class="leave-balance-card__year">{{ $balance->year }}</span>
                                                    @if((int) $balance->year === $currentCalendarYear)
                                                        <span class="badge bg-primary">{{ __('leaveBalance.current_year_badge') }}</span>
                                                    @endif
                                                </div>
                                                <p class="text-muted small mb-0">{{ __('leaveBalance.used_of_total', ['used' => rtrim(rtrim(number_format($used, 2, '.', ''), '0'), '.') ?: '0', 'total' => rtrim(rtrim(number_format((float) $balance->total_days, 2, '.', ''), '0'), '.') ?: '0']) }}</p>
                                            </div>
                                            <div class="leave-balance-ring"
                                                 style="--used-pct: {{ $usedPct }}; --ring-color: {{ $ringColor }};">
                                                <span class="leave-balance-ring__label">{{ rtrim(rtrim(number_format($rem, 2, '.', ''), '0'), '.') ?: '0' }}</span>
                                                <span class="leave-balance-ring__unit">{{ __('leaveBalance.days_short') }}</span>
                                            </div>
                                        </div>

                                        <div class="leave-balance-progress mb-4">
                                            <div class="leave-balance-progress__bar"
                                                 style="width: {{ $usedPct }}%; background: {{ $barColor }};"></div>
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-4">
                                                <div class="leave-balance-stat h-100">
                                                    <div class="leave-balance-stat__value">{{ rtrim(rtrim(number_format((float) $balance->total_days, 2, '.', ''), '0'), '.') ?: '0' }}</div>
                                                    <div class="leave-balance-stat__label">{{ __('label.total_days') }}</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="leave-balance-stat h-100">
                                                    <div class="leave-balance-stat__value">{{ rtrim(rtrim(number_format($used, 2, '.', ''), '0'), '.') ?: '0' }}</div>
                                                    <div class="leave-balance-stat__label">{{ __('label.used_days') }}</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="leave-balance-stat h-100">
                                                    <div class="leave-balance-stat__value text-success">{{ rtrim(rtrim(number_format($rem, 2, '.', ''), '0'), '.') ?: '0' }}</div>
                                                    <div class="leave-balance-stat__label">{{ __('label.remaining_days') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-light border text-muted mb-0">
                                    {{ __('leaveBalance.no_records') }}
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header
                :title="$leaveBalanceAdmin ? __('leaveBalance.admin_table_title') : __('leaveBalance.employee_table_title')"
                :createRoute="$createRoute"
            />

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    @if($leaveBalanceAdmin ?? false)
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="user_id" type="number"/>
                    </div>
                    @endif

                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="year" type="number"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                    <th data-key="id">{{ __('label.id') }}</th>
                    <th data-key="user_id" data-orderable="false">{{ __('label.user') }}</th>
                    <th data-key="year">{{ __('label.year') }}</th>
                    <th data-key="total_days">{{ __('label.total_days') }}</th>
                    <th data-key="used_days">{{ __('label.used_days') }}</th>
                    <th data-key="remaining_days" data-orderable="false">{{ __('label.remaining_days') }}</th>
                    <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/leave-balance/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
