<x-dashboard.layouts.app>
    <x-slot name="head">
        <link rel="stylesheet" href="{{ asset('/plugins/croppie/croppie.min.css') }}" />
    </x-slot>

    <div class="container-fluid">
        <div class="card">
            <x-dashboard.form._form
                :action="$viewMode === 'add' ? route('dashboard.users.store') : route('dashboard.users.update', $user->id)"
                :indexUrl="route('dashboard.users.index')"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :viewMode="$viewMode"
            >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form.uploader._file
                                name="avatar"
                                :crop="true"
                                :configKey="$user->getFileConfigName()"
                                :value="$user->avatar"
                            />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="first_name" value="{{ $user->first_name}}"/>
                        </div>

                        <div class="form-group required">
                            <x-dashboard.form._input name="email" autocomplete type="email" value="{{ $user->email}}"/>
                        </div>

                        <div class="form-group required">
                            <x-dashboard.form._input name="password" autocomplete type="password"/>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group required">
                            <x-dashboard.form._input name="last_name" value="{{ $user->last_name}}"/>
                        </div>

                        <div class="form-group required">
                            <x-dashboard.form._select name="role_ids[]" :data="$roles" :value="$userRoleIds ?? ''"
                                                      multiple class="select2"/>
                        </div>

                        <div class="form-group required">
                            <x-dashboard.form._input name="password_confirmation" type="password"/>
                        </div>
                    </div>
                </div>
            </x-dashboard.form._form>
        </div>
    </div>

    {{--  Crop Modal  --}}
    <x-dashboard.form.modals._crop id="cropImage" static />

    <x-slot name="scripts">
        <script src="{{ asset('/plugins/croppie/croppie.min.js') }}"></script>
        <script src="{{ asset('/js/dashboard/user/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
