<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header :createRoute="route('dashboard.roles.create')"/>
            <div class="card-body">
                <form class="ms-auto row d-flex  justify-content-between flex-wrap"
                      id="dataTable__search__form">
                    <div class="row col-md-11 p-0 m-0">
                        <div class="col-md-4 form-group">
                            <x-dashboard.form._input name="id" type="number"/>
                        </div>

                    </div>
                    <div class="col-md-1 form-group text-end d-flex flex-column justify-content-end">
                        <x-dashboard.form._loader_btn/>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table w-100 table-hover" id="__data__table">
                        <thead>
                        <tr>
                            <th data-key="id">{{ __('label.id') }}</th>
                            <th class="text-center">{{ __('label.actions') }}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/role/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>




