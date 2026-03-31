<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card">
            <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.roles.store') : route('dashboard.roles.update', $role->id)"
                :indexUrl="route('dashboard.roles.index')"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :viewMode="$viewMode"
            >

            </x-dashboard.form._form>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/role/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
