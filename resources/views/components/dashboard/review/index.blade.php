<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :createRoute="route('dashboard.reviews.create')"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._select name="user_id" allowClear defaultOption
                                                  :data="$users" class="select2"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._select name="reviewer_id" allowClear defaultOption
                                                  :data="$users" class="select2"/>
                    </div>

                    <div class="col-md-4 col-lg-2 form-group">
                        <x-dashboard.form._select name="review_period" allowClear defaultOption
                                                  :data="$reviewPeriodOptions" class="select2"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="user" data-orderable="false">{{ __('label.user') }}</th>
                   <th data-key="reviewer" data-orderable="false">{{ __('label.reviewer_id') }}</th>
                   <th data-key="review_period_display" data-orderable="false">{{ __('label.review_period') }}</th>
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
