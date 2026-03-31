<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :createRoute="route('dashboard.articles.create')"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="title"/>
                    </div>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._input name="description"/>
                    </div>
                    <div class="col-md-4 form-group">
                        <x-dashboard.form._show_status showAllOption/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                    <th data-key="id">{{ __('label.id') }}</th>
                    <th data-key="title">{{ __('label.title') }}</th>
                    <th data-key="description">{{ __('label.description') }}</th>
                    <th data-key="publish_date">{{ __('label.publish_date') }}</th>
                    <th data-key="created_at">{{ __('label.created_at') }}</th>
                    <th data-key="show_status" data-orderable="false">{{ __('label.show_status') }}</th>
                    <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script type="module" src="{{ asset('/js/dashboard/article/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>




