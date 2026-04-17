<x-dashboard.layouts.app>
    <div class="container-fluid rooms-page">
        <section class="rooms-hero mb-4">
            <div>
                <h2 class="rooms-hero__title mb-1">{{ __('room.index.hero_title') }}</h2>
                <p class="rooms-hero__subtitle mb-0">{{ __('room.index.hero_subtitle') }}</p>
            </div>
            <div class="rooms-hero__stats">
                <div class="rooms-hero__stat">
                    <span class="label">{{ __('room.index.stat_total') }}</span>
                    <strong>{{ $roomStats['total'] }}</strong>
                </div>
                <div class="rooms-hero__stat">
                    <span class="label">{{ __('room.index.stat_booked') }}</span>
                    <strong>{{ $roomStats['with_meetings'] }}</strong>
                </div>
                <div class="rooms-hero__stat">
                    <span class="label">{{ __('room.index.stat_unused') }}</span>
                    <strong>{{ $roomStats['unused'] }}</strong>
                </div>
                <div class="rooms-hero__stat">
                    <span class="label">{{ __('room.index.stat_meetings') }}</span>
                    <strong>{{ $roomStats['meeting_bookings'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 rooms-card">
            <x-dashboard.layouts.partials.card-header :createRoute="$createRoute"/>

            <div class="card-body">
                <x-dashboard.datatable._filters_form>
                    <div class="col-md-6 form-group">
                        <x-dashboard.form._input name="id" type="number"/>
                    </div>

                    <div class="col-md-6 form-group">
                        <x-dashboard.form._input name="name"/>
                    </div>
                </x-dashboard.datatable._filters_form>

                <x-dashboard.datatable._table>
                   <th data-key="id">{{ __('label.id') }}</th>
                   <th data-key="name">{{ __('label.name') }}</th>
                   <th class="text-center">{{ __('label.actions') }}</th>
                </x-dashboard.datatable._table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/room/index.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
