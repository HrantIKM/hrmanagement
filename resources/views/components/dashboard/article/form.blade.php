<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.form._form_ml
                :action="$viewMode === 'add' ? route('dashboard.articles.store') : route('dashboard.articles.update', $article->id)"
                :indexUrl="route('dashboard.articles.index')"
                :method="$viewMode === 'add' ? 'post' : 'put'"
                :viewMode="$viewMode"

                :mlData="$articleMl ?? []"
                :showStatus="$article->show_status ?? ''"
            >

                {{-- General Data --}}
                <x-slot:generalTabData>

                    <!-- Modal Info -->
                    <x-dashboard.form.modals._modal id="info" size="lg" headerText="info">
                        <x-slot name="body">
                            <p class="mb-0">Hi I'm Core Modal :)</p>
                        </x-slot>
                    </x-dashboard.form.modals._modal>

                    <!-- Button trigger info modal -->
                    <button type="button" class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#infoModal">
                        Launch info modal
                    </button>

                    <!-- Confirm Modal -->
                    {{-- <x-dashboard.form.modals._confirm static deleteType></x-dashboard.form.modals._confirm>--}}

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group required">
                                <x-dashboard.form._input name="slug" :value="$article->slug"/>
                            </div>

                            <div class="form-group required">
                                <x-dashboard.form.uploader._file
                                        name="photo"
                                        :configKey="$article->getFileConfigName()"
                                        :value="$article->photo"
                                />
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group required">
                                <x-dashboard.form._date name="publish_date" class="datepicker" :value="$article->publish_date"/>
                            </div>

                            <div class="form-group required">
                                <x-dashboard.form._date dateTime name="release_date_time" class="datetimepicker" :value="$article->release_date_time"/>
                            </div>
                        </div>
                    </div>

                    {{-- Multiple Group --}}
                    <x-dashboard.form.multiple-group class="grouped" :multipleData="$article->multiple_group_data ?? []">

                        <div class="form-group col-lg-12">
                            <x-dashboard.form._input_multiple title="grouped_title" noLabel dataName="title" name="multiple_group_data[0][title]"/>
                        </div>

                        <div class="form-group col-lg-12">
                            <x-dashboard.form._input_multiple title="grouped_link" noLabel dataName="link" name="multiple_group_data[0][link]"/>
                        </div>

                        <div class="form-group col-lg-12">
                            <x-dashboard.form._textarea rows="2" title="grouped_desc" noLabel dataName="desc" name="multiple_group_data[0][desc]"/>
                        </div>

                        <div class="form-group col-lg-12">
                            <x-dashboard.form._select title="grouped_user" allowClear :data="$users" noLabel dataName="user" class="select2"
                                                      defaultOption
                                                      name="multiple_group_data[0][user]"/>
                        </div>

                    </x-dashboard.form.multiple-group>

                    {{-- Multiple --}}
                    <x-dashboard.form.multiple-group class="content-group" :multipleData="$article->multiple_author ?? []">

                        <div class="form-group col-lg-12">
                            <x-dashboard.form._input_multiple title="multiple_author" noLabel name="multiple_author[0]"/>
                        </div>

                    </x-dashboard.form.multiple-group>

                </x-slot:generalTabData>

                {{-- ML Data --}}
                <x-slot:mlTabsData>
                    <div class="form-group required">
                        <x-dashboard.form._input name="title"/>
                    </div>

                    <div class="form-group required">
                        <x-dashboard.form._input name="short_description"/>
                    </div>

                    <div class="form-group required">
                        <x-dashboard.form._textarea name="description" class="ckeditor5"/>
                    </div>

                    <x-dashboard.form._meta_data></x-dashboard.form._meta_data>
                </x-slot:mlTabsData>

            </x-dashboard.form._form_ml>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('/js/dashboard/article/main.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>






