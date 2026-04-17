@php
    use App\Models\RoleAndPermission\Enums\RoleType;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid review-mine-page">
        @if($reviews->isEmpty())
            <div class="card mb-4 review-mine-empty">
                <div class="card-body text-center py-5 px-4">
                    <div class="review-mine-empty__icon" aria-hidden="true">
                        <i class="far fa-comment-dots"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-dark">{{ __('review.my.empty_title') }}</h4>
                    <p class="text-muted mb-4 col-lg-8 mx-auto">{{ __('review.my.empty_body') }}</p>
                    @if(auth()->user()?->hasRole(RoleType::ADMIN))
                        <a href="{{ route('dashboard.reviews.index') }}" class="btn btn-outline-secondary btn-sm">
                            {{ __('review.my.hr_manage_link') }}
                        </a>
                    @endif
                </div>
            </div>
        @else
            <section class="review-mine-hero">
                <div class="flex-grow-1">
                    <div class="review-mine-hero__title mb-1">{{ __('review.my.hero_title') }}</div>
                    <p class="review-mine-hero__subtitle mb-2 mb-md-0">{{ __('review.my.hero_subtitle') }}</p>
                    @if(auth()->user()?->hasRole(RoleType::ADMIN))
                        <a href="{{ route('dashboard.reviews.index') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
                            {{ __('review.my.hr_manage_link') }}
                        </a>
                    @endif
                </div>
                <div class="review-mine-hero__stats">
                    <div class="review-mine-hero__stat">
                        <span class="label">{{ __('review.my.stat_total') }}</span>
                        <strong>{{ $stats['count'] }}</strong>
                    </div>
                    <div class="review-mine-hero__stat">
                        <span class="label">{{ __('review.my.stat_avg') }}</span>
                        <strong>{{ $stats['avg_rating'] !== null ? $stats['avg_rating'] : '—' }}</strong>
                    </div>
                    <div class="review-mine-hero__stat">
                        <span class="label">{{ __('review.my.stat_this_year') }}</span>
                        <strong>{{ $stats['this_year'] }}</strong>
                    </div>
                    <div class="review-mine-hero__stat">
                        <span class="label">{{ __('review.my.stat_latest') }}</span>
                        <strong class="small">{{ $stats['latest_at']?->format('M j, Y') ?? __('review.my.stat_latest_none') }}</strong>
                    </div>
                </div>
            </section>

            <h5 class="mb-3 fw-bold text-dark">{{ __('review.my.feedback_heading') }}</h5>
            <div class="timeline-like mb-5">
                @foreach($reviews as $r)
                    <div class="card mb-3 review-mine-card overflow-hidden">
                        <div class="row g-0">
                            <div class="col-md-3 review-mine-card__sidebar border-end d-flex flex-column justify-content-center align-items-center py-4 px-3">
                                <div class="review-mine-card__stars mb-1" aria-hidden="true">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= (int) round((float) $r->rating))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="review-mine-card__score">{{ $r->rating }}</div>
                                <div class="small text-muted fw-semibold">/ 5</div>
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ $r->review_period_display }}</span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border">{{ $r->review_perspective_display }}</span>
                                        @if($r->created_at)
                                            <span class="small text-muted ms-md-auto">{{ $r->created_at->format('Y-m-d H:i') }}</span>
                                        @endif
                                    </div>
                                    <div class="fw-semibold mb-2 text-dark">
                                        {{ __('review.my.from_reviewer', ['name' => $r->reviewer?->name ?? __('label.user')]) }}
                                    </div>
                                    @if($r->feedback_text)
                                        <div class="text-secondary" style="line-height: 1.65;">
                                            {!! nl2br(e($r->feedback_text)) !!}
                                        </div>
                                    @else
                                        <p class="text-muted fst-italic mb-0">{{ __('label.feedback_text') }}: —</p>
                                    @endif
                                    <div class="mt-3 pt-2 border-top border-opacity-50">
                                        <a href="{{ route('dashboard.reviews.show', $r) }}" class="btn btn-sm btn-primary">
                                            <i class="flaticon-eye me-1"></i> {{ __('review.my.view_detail') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card mb-4 review-mine-table-card">
                <div class="card-header fw-bold border-0 bg-transparent pt-3 px-3 pb-0">{{ __('review.my.table_heading') }}</div>
                <div class="card-body pt-3">
                    <x-dashboard.datatable._filters_form>
                        <div class="col-md-6 col-lg-3 form-group">
                            <x-dashboard.form._input name="id" type="number"/>
                        </div>
                        <div class="col-md-6 col-lg-3 form-group">
                            <x-dashboard.form._select name="reviewer_id" allowClear defaultOption
                                                      :data="$users" class="select2"/>
                        </div>
                        <div class="col-md-6 col-lg-3 form-group">
                            <x-dashboard.form._select name="review_period" allowClear defaultOption
                                                      :data="$reviewPeriodOptions" class="select2"/>
                        </div>
                        <div class="col-md-6 col-lg-3 form-group">
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
        @if($reviews->isNotEmpty())
            <script src="{{ asset('/js/dashboard/review/my-index.js') }}"></script>
        @endif
    </x-slot>
</x-dashboard.layouts.app>
