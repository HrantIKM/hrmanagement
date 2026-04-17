<header class="header">
    <div class="d-flex justify-content-between align-items-center">
        <a href="/" class="brand-logo d-xl-none">
            {{--            <img src="/img/logo.svg" width="100" alt="Logo">--}}
        </a>

        <div class="header-actions ms-auto">
            {{-- Notification Block: Laravel database notifications for the signed-in user only --}}
            @if(config('dashboard.show_notification') && auth()->check())
                @php
                    $headerNotifications = auth()->user()->notifications()->limit(15)->get();
                    $headerUnreadCount = auth()->user()->unreadNotifications()->count();
                @endphp
                <div class="dropdown">
                    <a href="#" class="notification-btn header-icon-btn position-relative dropdown-toggle" data-bs-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false" id="dashboard-notifications-toggle">
                        @if($headerUnreadCount > 0)
                            <span class="js-notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger small">{{ $headerUnreadCount }}</span>
                        @else
                            <span class="js-notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger small" style="display: none">0</span>
                        @endif
                        <span class="svg-icons">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="25" viewBox="0 0 20.635 26.035"><defs><style>.a {
                                           fill: currentColor;
                                       }</style></defs><g transform="translate(0)"><path class="a"
                                                                                         d="M184.9,465.044a3.933,3.933,0,0,0,7.2,0Z"
                                                                                         transform="translate(-178.186 -441.358)"/><path
                                           class="a"
                                           d="M199.3,2.487a8.846,8.846,0,0,1,3.021.529V2.9a2.9,2.9,0,0,0-2.9-2.9h-.24a2.9,2.9,0,0,0-2.9,2.9v.115A8.863,8.863,0,0,1,199.3,2.487Z"
                                           transform="translate(-188.98 0)"/><path class="a"
                                                                                   d="M72.864,96.979H53.8a.777.777,0,0,1-.766-.6.741.741,0,0,1,.408-.843,4.046,4.046,0,0,0,1.231-1.674,19.34,19.34,0,0,0,1.284-7.653,7.376,7.376,0,0,1,14.752-.029q0,.015,0,.029a19.34,19.34,0,0,0,1.284,7.653,4.045,4.045,0,0,0,1.231,1.674.741.741,0,0,1,.408.843A.777.777,0,0,1,72.864,96.979Zm.367-1.434h0Z"
                                                                                   transform="translate(-53.013 -74.821)"/></g>
                           </svg>
                       </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="dashboard-notifications-toggle">
                        <div class="dropdown-notification simple-bar">
                            @forelse($headerNotifications as $n)
                                @php
                                    $d = $n->data;
                                    $notifUrl = $d['url'] ?? '#';
                                    $notifTitle = $d['title'] ?? \Illuminate\Support\Str::limit($d['message'] ?? __('notifications.notification'), 72);
                                    $notifUnread = $n->read_at === null;
                                @endphp
                                <a href="{{ $notifUrl }}"
                                   class="notification-link js-notification-item d-block text-decoration-none {{ $notifUnread ? 'is-unread fw-semibold' : '' }}"
                                   @if($notifUnread) data-read-url="{{ route('dashboard.notifications.read', $n->id) }}" @endif>
                                    <div class="notification-title">{{ $notifTitle }}</div>
                                    <div class="notification-time">{{ $n->created_at?->diffForHumans() }}</div>
                                </a>
                            @empty
                                <div class="p-3 text-muted small">{{ __('page.notification.index.empty') }}</div>
                            @endforelse
                        </div>
                        <div class="d-flex border-top align-items-stretch">
                            <a href="{{ route('dashboard.notifications.index') }}"
                               class="notification-link-all flex-grow-1 text-center py-2 text-decoration-none">{{ __('page.notification.index.title') }}</a>
                            @if($headerUnreadCount > 0)
                                <a href="#"
                                   class="notification-link-all flex-grow-1 text-center py-2 border-start text-decoration-none js-mark-all-notifications-read"
                                   data-url="{{ route('dashboard.notifications.readAll') }}">{{ __('page.notification.index.mark_all_read') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @php
                $headerMessagesUnread = \App\Models\Message\Message::query()
                    ->where('receiver_id', auth()->id())
                    ->whereNull('read_at')
                    ->count();
            @endphp
            <a href="{{ route('dashboard.messages.index') }}"
               class="notification-btn header-icon-btn header-chat-btn position-relative {{ routeIs('dashboard.messages.*') ? 'active' : '' }}"
               title="{{ __('menu.messages') }}">
                @if($headerMessagesUnread > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary small">{{ $headerMessagesUnread > 99 ? '99+' : $headerMessagesUnread }}</span>
                @endif
                <span class="svg-icons"><img src="{{ asset('/img/message-icon.svg') }}" alt="{{ __('menu.messages') }}" class="header-chat-btn__img"></span>
            </a>

            {{-- Lnaguage Block --}}
            <div class="btn-group lang-drop">
                <button type="button" class="btn dropdown-toggle header-icon-btn" data-bs-toggle="dropdown" aria-label="lang"
                        aria-haspopup="true" aria-expanded="false">
                    <img
                            src="{{langIconPath(LaravelLocalization::getCurrentLocale())}}"
                            alt="" width="20px" height="20px">
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localCode => $langItem)
                        @if(LaravelLocalization::getCurrentLocale() != $localCode)
                            <a class="dropdown-item"
                               href="{{ LaravelLocalization::getLocalizedURL($localCode, null, [], true) }}">
                                <img src="{{langIconPath($localCode)}}" alt="" width="20px" height="20px">
                                <span class="navi-text">{{__($localCode)}}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- User Block --}}
            <div class="btn-group">
                <button type="button" class="btn dropdown-toggle d-flex align-items-center header-user-btn" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <span class="d-none d-md-inline-block">{{auth()->user()->first_name}}</span>

                    @if(auth()->user()->avatar)
                        <img src="{{auth()->user()->avatar->file_url}}" class="ms-2" width="30px" alt="">
                    @else
                        <span class="flaticon-user ms-2"></span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{dashboardRoute('profile.index')}}"
                       class="dropdown-item {{routeIs('dashboard.profile.index') ? 'active' : ''}}" type="button">
                        <i class="flaticon2-user-1 me-2"></i>
                        {{__('page.profile.dropdown')}}
                    </a>

                    <button class="dropdown-item" type="button"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="flaticon-logout  me-2"></i>
                        {{__('__dashboard.global.log_out')}}
                    </button>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>

            <button class="btn open-menu" type="button">
                <i class="flaticon2-cross"></i>
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img"
                     focusable="false">
                    <title>Menu</title>
                    <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
                </svg>
            </button>
        </div>
    </div>
</header>
