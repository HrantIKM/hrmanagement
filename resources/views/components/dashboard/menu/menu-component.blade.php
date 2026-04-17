@foreach($groupName as $key => $menus)
    @if($key)
        <li class="menu-item-group">{{ $key }}</li>
    @endif
    @foreach($menus as $menu)
        @if(in_array($menu->slug, ['reviews', 'payslips', 'candidates', 'vacancies', 'positions'], true) && !(auth()->user()?->hasRole(\App\Models\RoleAndPermission\Enums\RoleType::ADMIN) ?? false))
            @continue
        @endif
        @if($menu->slug === 'messages')
            @continue
        @endif
        @if($menu->subMenu->count())
            @php
                $hasActiveSubMenu = $menu->subMenu->contains(fn ($menu) => Str::is('*'.$menu->url.'*', request()->path()));
            @endphp

            <li class="menu-item {{ $hasActiveSubMenu ? 'active' : '' }}">
                <a class="menu-link" data-bs-toggle="collapse" href="#collapseMenu_{{$menu->id}}" role="button" aria-expanded="{{$hasActiveSubMenu ? 'true' : 'false'}}"
                   aria-controls="collapseMenu_{{$menu->id}}">
                        <span class="svg-icon">
                            <i class="{{ $menu->icon }}"></i>
                        </span>
                    <span class="menu-text">{{ __('menu.'.$menu->slug) }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{$hasActiveSubMenu ? 'show' : ''}}" data-bs-parent="#menu-nav" id="collapseMenu_{{$menu->id}}">
                    <ul class="menu-nav">
                        @foreach($menu->subMenu as $subMenu)
                            <li class="menu-item">
                                <a href="{{ urlWithLng($subMenu->url) }}" class="menu-link {{(request()->is('*'.$subMenu->url.'*')) ? 'active-submenu' : ''}}">
                                    <i class="menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{ __('menu.'.$subMenu->slug) }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @elseif($menu->url)
            @php
                $activeMenu = match ($menu->slug) {
                    'reviews' => routeIs('dashboard.reviews.*') && !routeIs('dashboard.reviews.mine'),
                    'reviews-mine' => routeIs('dashboard.reviews.mine'),
                    'payslips' => routeIs('dashboard.payslips.*') && !routeIs('dashboard.payslips.mine'),
                    'payslips-mine' => routeIs('dashboard.payslips.mine'),
                    default => request()->is('*'.$menu->url) || request()->is('*'.$menu->url.'/*'),
                };
            @endphp
            <li class="menu-item ">
                <a href="{{ urlWithLng($menu->url) }}" class="menu-link {{ $activeMenu ? 'active' : '' }}">
                <span class="svg-icon">
                    <i class="{{ $menu->icon }}"></i>
                </span>
                    <span class="menu-text">{{ __('menu.'.$menu->slug) }}</span>
                </a>
            </li>
        @endif
    @endforeach
@endforeach

