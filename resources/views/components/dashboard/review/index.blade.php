<x-dashboard.layouts.app>
    <div class="container-fluid reviews-page">
        <section class="reviews-hero mb-4">
            <div>
                <h2 class="reviews-hero__title mb-1">{{ __('review.index.hero_title') }}</h2>
                <p class="reviews-hero__subtitle mb-0">{{ __('review.index.hero_subtitle') }}</p>
            </div>
            <div class="reviews-hero__stats">
                <div class="reviews-hero__stat">
                    <span class="label">{{ __('review.index.stat_total') }}</span>
                    <strong>{{ $reviewStats['total'] }}</strong>
                </div>
                <div class="reviews-hero__stat">
                    <span class="label">{{ __('review.index.stat_avg') }}</span>
                    <strong>{{ $reviewStats['avg_rating'] !== null ? $reviewStats['avg_rating'] : '—' }}</strong>
                </div>
                <div class="reviews-hero__stat">
                    <span class="label">{{ __('review.index.stat_employees') }}</span>
                    <strong>{{ $reviewStats['employees'] }}</strong>
                </div>
                <div class="reviews-hero__stat">
                    <span class="label">{{ __('review.index.stat_recent') }}</span>
                    <strong>{{ $reviewStats['last_30d'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 reviews-card">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-6 col-lg-4 col-xl-2 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-6 col-lg-4 col-xl-2 form-group">
                        <x-dashboard.form._select name="user_id" allowClear defaultOption
                                                  :data="$users" class="select2"/>
                    </div>

                    <div class="col-md-6 col-lg-4 col-xl-2 form-group">
                        <x-dashboard.form._select name="reviewer_id" allowClear defaultOption
                                                  :data="$users" class="select2"/>
                    </div>

                    <div class="col-md-6 col-lg-4 col-xl-2 form-group">
                        <x-dashboard.form._select name="review_period" allowClear defaultOption
                                                  :data="$reviewPeriodOptions" class="select2"/>
                    </div>

                    <div class="col-md-6 col-lg-4 col-xl-2 form-group">
                        <x-dashboard.form._select name="review_perspective" allowClear defaultOption
                                                  :data="$reviewPerspectiveOptions" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="user" data-orderable="false">{{ __('label.user') }}</th>
                   <th data-key="reviewer" data-orderable="false">{{ __('label.reviewer_id') }}</th>
                   <th data-key="review_period_display" data-orderable="false">{{ __('label.review_period') }}</th>
                   <th data-key="review_perspective_display" data-orderable="false">{{ __('label.review_perspective') }}</th>
                   <th data-key="rating">{{ __('label.rating') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/review/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
