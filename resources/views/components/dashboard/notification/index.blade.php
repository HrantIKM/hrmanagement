<x-dashboard.layouts.app>
    <div class="container-fluid notifications-page">
        <section class="notifications-hero mb-4">
            <div>
                <h2 class="notifications-hero__title mb-1">{{ __('page.notification.index.hero_title') }}</h2>
                <p class="notifications-hero__subtitle mb-0">{{ __('page.notification.index.hero_subtitle') }}</p>
            </div>
            <div class="notifications-hero__stats">
                <div class="notifications-hero__stat">
                    <span class="label">{{ __('page.notification.index.stat_total') }}</span>
                    <strong>{{ $notificationStats['total'] }}</strong>
                </div>
                <div class="notifications-hero__stat">
                    <span class="label">{{ __('page.notification.index.stat_unread') }}</span>
                    <strong>{{ $notificationStats['unread'] }}</strong>
                </div>
                <div class="notifications-hero__stat">
                    <span class="label">{{ __('page.notification.index.stat_read') }}</span>
                    <strong>{{ $notificationStats['read'] }}</strong>
                </div>
            </div>
        </section>

        <div class="card mb-4 notifications-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <span class="notifications-card__title">{{ __('page.notification.index.title') }}</span>
                <form method="post" action="{{ route('dashboard.notifications.readAll') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary btn-mark-all">
                        {{ __('page.notification.index.mark_all_read') }}
                    </button>
                </form>
            </div>
            <div class="card-body p-0">
                @if($notifications->isEmpty())
                    <div class="notifications-empty">
                        <div class="notifications-empty__icon" aria-hidden="true">
                            <i class="flaticon2-notification"></i>
                        </div>
                        <div class="notifications-empty__title">{{ __('page.notification.index.empty') }}</div>
                        <p class="notifications-empty__hint mb-0">{{ __('page.notification.index.empty_hint') }}</p>
                    </div>
                @else
                    <div class="notifications-feed">
                        @foreach($notifications as $n)
                            @php
                                $data = $n->data;
                                $url = $data['url'] ?? '#';
                                $title = $data['title'] ?? \Illuminate\Support\Str::limit($data['message'] ?? '', 80);
                                $isUnread = $n->read_at === null;
                            @endphp
                            <div class="notifications-feed__item {{ $isUnread ? 'notifications-feed__item--unread' : '' }}">
                                <div class="notifications-feed__main">
                                    <span class="notifications-feed__dot" aria-hidden="true"></span>
                                    <div class="min-w-0">
                                        <a href="{{ $url }}" class="notifications-feed__title">{{ $title }}</a>
                                        @if(!empty($data['message']) && ($data['message'] ?? '') !== $title)
                                            <div class="notifications-feed__message">{{ $data['message'] }}</div>
                                        @endif
                                        <div class="notifications-feed__time">{{ $n->created_at?->diffForHumans() }}</div>
                                    </div>
                                </div>
                                @if($isUnread)
                                    <div class="notifications-feed__actions">
                                        <form method="post" action="{{ route('dashboard.notifications.read', $n->id) }}" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary btn-mark-one">
                                                {{ __('page.notification.index.mark_read') }}
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @if($notifications->hasPages())
                <div class="card-footer">{{ $notifications->links() }}</div>
            @endif
        </div>
    </div>
</x-dashboard.layouts.app>
