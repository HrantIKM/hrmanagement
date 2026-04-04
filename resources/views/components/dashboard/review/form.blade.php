@php
    use App\Models\Review\Enums\ReviewPeriod;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
             <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.reviews.store') : route('dashboard.reviews.update', $review->id)"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :indexUrl="route('dashboard.reviews.index')"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="user_id" title="user" :data="$users" :value="$review->user_id ?? ''"
                                                      allowClear defaultOption class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="reviewer_id" :data="$users" :value="$review->reviewer_id ?? ''"
                                                      allowClear defaultOption class="select2"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._select name="review_period" :data="$reviewPeriodOptions"
                                                      :value="$review->review_period ?? ReviewPeriod::Q1" class="select2"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="rating" type="number" step="0.01" min="1" max="5" :value="$review->rating"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <x-dashboard.form._textarea name="feedback_text" :value="$review->feedback_text" rows="5"/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/review/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
