<x-dashboard.layouts.app>
    <x-slot name="scripts">
        <script src="{{ mix('/js/dashboard/dashboard-app-vue.js') }}"></script>
    </x-slot>

    <div class="container-fluid">
        <div id="app">
            <vue-example/>
        </div>
    </div>
</x-dashboard.layouts.app>




