@php
    $hubDataUrl = route('dashboard.departments.hubData');
    $tableUrl = route('dashboard.departments.table');
    $canManageDepartments = $canManageDepartments ?? false;
    $createUrl = route('dashboard.departments.create');
    $ph = 999999998;
    $editUrlTemplate = str_replace((string) $ph, '__ID__', route('dashboard.departments.edit', ['department' => $ph], false));
    $hubI18n = [
        'rootBadge' => __('department.root_badge'),
        'statsLine' => __('department.stats_line'),
        'moreMembers' => __('department.more_members'),
        'noMembers' => __('department.no_members'),
        'noPositions' => __('department.no_positions'),
        'noSkills' => __('department.no_skills'),
        'childTeams' => __('department.child_teams'),
    ];
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid dept-hub">
        <div class="dept-hub__hero mb-4">
            <div class="dept-hub__hero-inner">
                <div>
                    <h1 class="dept-hub__title">{{ __('department.hub_title') }}</h1>
                    <p class="dept-hub__subtitle mb-0">{{ __('department.hub_subtitle') }}</p>
                </div>
                <div class="dept-hub__hero-actions">
                    <a href="{{ $tableUrl }}" class="btn btn-light btn-sm dept-hub__btn-outline">
                        <i class="fas fa-table me-1"></i> {{ __('department.table_view') }}
                    </a>
                    @if($canManageDepartments)
                    <a href="{{ $createUrl }}" class="btn btn-primary btn-sm">
                        <i class="flaticon2-plus me-1"></i> {{ __('page.department.index.create') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-3 dept-hub__layout">
            <div class="col-lg-5 col-xl-4">
                <div class="dept-hub__panel dept-hub__panel--tree">
                    <div class="dept-hub__toolbar">
                        <div class="input-group input-group-sm dept-hub__search-wrap">
                            <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="search" class="form-control border-start-0 dept-hub__search" id="dept-hub-search"
                                   placeholder="{{ __('department.tree_search') }}" autocomplete="off">
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary" id="dept-hub-expand">{{ __('department.expand_all') }}</button>
                            <button type="button" class="btn btn-outline-secondary" id="dept-hub-collapse">{{ __('department.collapse_all') }}</button>
                        </div>
                    </div>
                    <div class="dept-hub__tree-scroll">
                        <div id="dept-hub-tree" class="dept-hub__tree" role="tree" aria-label="{{ __('department.hub_title') }}"></div>
                        <p id="dept-hub-empty" class="text-muted small p-3 d-none">{{ __('department.no_departments') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-xl-8">
                <div class="dept-hub__panel dept-hub__panel--detail" id="dept-hub-detail">
                    <div class="dept-hub__detail-placeholder text-muted text-center py-5" id="dept-hub-placeholder">
                        <i class="fas fa-sitemap fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">{{ __('department.hub_subtitle') }}</p>
                    </div>
                    <div id="dept-hub-detail-body" class="d-none"></div>
                </div>
            </div>
        </div>
    </div>

    <template id="dept-hub-detail-template">
        <div class="dept-hub__detail-head">
            <div class="dept-hub__detail-icon-wrap">
                <img class="dept-hub__detail-icon d-none" alt="" data-field="icon">
                <span class="dept-hub__detail-fallback" data-field="fallback"></span>
            </div>
            <div class="flex-grow-1 min-w-0">
                <h2 class="dept-hub__detail-title mb-1" data-field="name"></h2>
                <p class="dept-hub__detail-desc text-muted small mb-2" data-field="description"></p>
                <div class="dept-hub__badges">
                    <span class="badge rounded-pill dept-hub__badge" data-field="badge-root"></span>
                    <span class="badge rounded-pill dept-hub__badge dept-hub__badge--stats" data-field="stats"></span>
                </div>
            </div>
            <div class="dept-hub__detail-actions">
                <a class="btn btn-sm btn-outline-primary" data-field="edit-link"><i class="fas fa-edit"></i></a>
            </div>
        </div>

        <div class="dept-hub__sections">
            <section class="dept-hub__section">
                <h3 class="dept-hub__section-title"><i class="fas fa-users me-2 text-primary"></i>{{ __('department.members') }}</h3>
                <div class="dept-hub__members" data-field="members"></div>
                <p class="dept-hub__more text-muted small mt-2 d-none" data-field="members-more"></p>
            </section>

            <section class="dept-hub__section">
                <h3 class="dept-hub__section-title"><i class="fas fa-id-badge me-2 text-primary"></i>{{ __('department.positions') }}</h3>
                <div class="dept-hub__chips" data-field="positions"></div>
            </section>

            <section class="dept-hub__section">
                <h3 class="dept-hub__section-title"><i class="fas fa-tags me-2 text-primary"></i>{{ __('department.skills') }}</h3>
                <div data-field="skills-dept"></div>
            </section>

            <section class="dept-hub__section">
                <h3 class="dept-hub__section-title"><i class="fas fa-user-graduate me-2 text-primary"></i>{{ __('department.skills_member') }}</h3>
                <div class="dept-hub__chips dept-hub__chips--soft" data-field="skills-members"></div>
            </section>
        </div>
    </template>

    <x-slot name="scripts">
        <script>
            window.__DEPT_HUB__ = {
                dataUrl: @json($hubDataUrl),
                editUrlTemplate: @json($editUrlTemplate),
                i18n: @json($hubI18n),
                canManage: @json($canManageDepartments),
            };
        </script>
        <script src="{{ mix('js/dashboard/department/hub.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
