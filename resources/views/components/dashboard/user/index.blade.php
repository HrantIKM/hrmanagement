<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :createRoute="route('dashboard.users.create')"/>

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
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                    <th data-key="id">{{ __('label.id') }}</th>
                    <th data-key="first_name">{{ __('label.first_name') }}</th>
                    <th data-key="last_name">{{ __('label.last_name') }}</th>
                    <th data-key="email">{{ __('label.email') }}</th>
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
