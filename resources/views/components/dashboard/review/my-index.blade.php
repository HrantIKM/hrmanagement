@php
    use App\Models\RoleAndPermission\Enums\RoleType;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid review-mine-page">
        @if(auth()->user()?->hasRole(RoleType::ADMIN))
            <div class="mb-3">
                <a href="{{ route('dashboard.reviews.index') }}" class="btn btn-sm btn-outline-secondary">
                    {{ __('review.my.hr_manage_link') }}
                </a>
            </div>
        @endif

        @if($reviews->isEmpty())
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-3 text-primary" style="font-size: 3rem; line-height: 1;">
                        <i class="far fa-comment-dots"></i>
                    </div>
                    <h4 class="mb-2">{{ __('review.my.empty_title') }}</h4>
                    <p class="text-muted mb-0 col-lg-8 mx-auto">{{ __('review.my.empty_body') }}</p>
                </div>
            </div>
        @else
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm bg-primary text-white">
                        <div class="card-body">
                            <div class="small text-white-50 text-uppercase fw-semibold">{{ __('review.my.stat_total') }}</div>
                            <div class="display-6 fw-bold">{{ $stats['count'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="small text-muted text-uppercase fw-semibold">{{ __('review.my.stat_avg') }}</div>
                            <div class="d-flex align-items-baseline gap-2">
                                @if($stats['avg_rating'] !== null)
                                    <span class="display-6 fw-bold text-dark">{{ $stats['avg_rating'] }}</span>
                                    <span class="text-warning fs-4">/ 5</span>
                                @else
                                    <span class="fs-3 text-muted">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="small text-muted text-uppercase fw-semibold">{{ __('review.my.stat_latest') }}</div>
                            <div class="fs-5 fw-semibold text-dark">
                                {{ $stats['latest_at']?->format('M j, Y') ?? __('review.my.stat_latest_none') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="mb-3 fw-semibold">{{ __('review.my.feedback_heading') }}</h5>
            <div class="timeline-like mb-5">
                @foreach($reviews as $r)
                    <div class="card mb-3 border-0 shadow-sm review-mine-card overflow-hidden">
                        <div class="row g-0">
                            <div class="col-md-3 bg-light border-end d-flex flex-column justify-content-center align-items-center py-4 px-3">
                                <div class="text-warning mb-1" style="font-size: 1.75rem; letter-spacing: 0.05em;" aria-hidden="true">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= (int) round((float) $r->rating))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="fs-2 fw-bold text-primary">{{ $r->rating }}</div>
                                <div class="small text-muted">/ 5</div>
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <span class="badge bg-secondary">{{ $r->review_period_display }}</span>
                                        <span class="badge bg-info text-dark">{{ $r->review_perspective_display }}</span>
                                        @if($r->created_at)
                                            <span class="small text-muted ms-md-auto">{{ $r->created_at->format('Y-m-d H:i') }}</span>
                                        @endif
                                    </div>
                                    <div class="fw-semibold mb-2">
                                        {{ __('review.my.from_reviewer', ['name' => $r->reviewer?->name ?? __('label.user')]) }}
                                    </div>
                                    @if($r->feedback_text)
                                        <div class="review-feedback-body text-secondary" style="line-height: 1.65;">
                                            {!! nl2br(e($r->feedback_text)) !!}
                                        </div>
                                    @else
                                        <p class="text-muted fst-italic mb-0">{{ __('label.feedback_text') }}: —</p>
                                    @endif
                                    <div class="mt-3 pt-2 border-top">
                                        <a href="{{ route('dashboard.reviews.show', $r) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="flaticon-eye me-1"></i> {{ __('review.my.view_detail') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($reviews->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header fw-semibold">{{ __('review.my.table_heading') }}</div>
                <div class="card-body">
                    <x-dashboard.datatable._filters_form>
                        <div class="col-md-4 col-lg-2 form-group">
                            <x-dashboard.form._input name="id" type="number"/>
                        </div>
                        <div class="col-md-4 col-lg-2 form-group">
                            <x-dashboard.form._select name="reviewer_id" allowClear defaultOption
                                                      :data="$users" class="select2"/>
                        </div>
                        <div class="col-md-4 col-lg-2 form-group">
                            <x-dashboard.form._select name="review_period" allowClear defaultOption
                                                      :data="$reviewPeriodOptions" class="select2"/>
                        </div>
                        <div class="col-md-4 col-lg-2 form-group">
                            <x-dashboard.form._select name="review_perspective" allowClear defaultOption
                                                      :data="$reviewPerspectiveOptions" class="select2"/>
                        </div>
                    </x-dashboard.datatable._filters_form>

                    <x-dashboard.datatable._table>
                        <th data-key="id">{{ __('label.id') }}</th>
                        <th data-key="reviewer" data-orderable="false">{{ __('label.reviewer_id') }}</th>
                        <th data-key="review_period_display" data-orderable="false">{{ __('label.review_period') }}</th>
                        <th data-key="review_perspective_display" data-orderable="false">{{ __('label.review_perspective') }}</th>
                        <th data-key="rating">{{ __('label.rating') }}</th>
                        <th class="text-center">{{ __('label.actions') }}</th>
                    </x-dashboard.datatable._table>
                </div>
            </div>
        @endif
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/review/my-index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
