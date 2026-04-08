<x-dashboard.layouts.app>

    <x-slot name="head">
        <link rel="stylesheet" href="{{ asset('/plugins/croppie/croppie.min.css') }}" />
    </x-slot>

    <div class="container-fluid">
        <div class="card mb-4">

            <x-dashboard.form._form
                :action="route('dashboard.profile.update', auth()->id())"
                :indexUrl="route('dashboard.index')"
                method="put"
                viewMode="edit"
            >

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <x-dashboard.form.uploader._file
                                name="avatar"
                                :value="$user->avatar"
                                :crop="true"
                                :configKey="$user->getFileConfigName()"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">

                    <div class="form-group required">
                        <x-dashboard.form._input name="first_name" :value="$user->first_name"/>
                    </div>

                    <div class="form-group required">
                        <x-dashboard.form._input name="last_name" :value="$user->last_name"/>
                    </div>

                    <div class="form-group">
                        <x-dashboard.form._input name="email" disabled :value="$user->email"/>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <x-dashboard.form._input name="current_password" autocomplete type="password"/>
                    </div>

                    <div class="form-group">
                        <x-dashboard.form._input name="new_password" autocomplete type="password"/>
                    </div>

                    <div class="form-group mb-0">
                        <x-dashboard.form._input name="new_password_confirmation" autocomplete="off" type="password"/>
                    </div>
                </div>
            </div>

            </x-dashboard.form._form>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('page.profile.work_card_title') }}</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">{{ __('page.profile.work_card_hint') }}</p>

                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="fw-semibold text-muted small">{{ __('label.department_id') }}</div>
                        <div>{{ $user->department?->name ?? '—' }}</div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="fw-semibold text-muted small">{{ __('label.position_id') }}</div>
                        <div>{{ $user->position?->title ?? '—' }}</div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="fw-semibold text-muted small">{{ __('label.salary') }}</div>
                        <div>
                            @if($user->salary !== null)
                                {{ number_format((float) $user->salary, 2, '.', ',') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="fw-semibold text-muted small">{{ __('label.hire_date') }}</div>
                        <div>{{ $user->hire_date?->format('Y-m-d') ?? '—' }}</div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="fw-semibold text-muted small">{{ __('label.employment_status') }}</div>
                        <div>{{ $user->employment_status_display }}</div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="fw-semibold text-muted small mb-2">{{ __('page.profile.skills') }}</div>
                @if($user->skills->isNotEmpty())
                    <ul class="list-unstyled mb-0 row g-2">
                        @foreach($user->skills->sortBy('name') as $skill)
                            <li class="col-md-6 col-lg-4">
                                <span class="d-inline-block border rounded px-2 py-1 bg-light small w-100">
                                    {{ $skill->name }}
                                    <span class="text-muted">· {{ $skill->category_label }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">{{ __('page.profile.no_skills') }}</p>
                @endif
            </div>
        </div>
    </div>

    {{--  Crop Modal  --}}
    <x-dashboard.form.modals._crop id="cropImage" static />

    <x-slot name="scripts">
        <script src="{{ asset('/plugins/croppie/croppie.min.js') }}"></script>
        <script src="{{ asset('/js/dashboard/profile/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
