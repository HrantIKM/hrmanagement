<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <span class="fw-semibold">{{ __('page.notification.index.title') }}</span>
                <form method="post" action="{{ route('dashboard.notifications.readAll') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        {{ __('page.notification.index.mark_all_read') }}
                    </button>
                </form>
            </div>
            <div class="card-body p-0">
                @if($notifications->isEmpty())
                    <div class="p-4 text-muted text-center">{{ __('page.notification.index.empty') }}</div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $n)
                            @php
                                $data = $n->data;
                                $url = $data['url'] ?? '#';
                                $title = $data['title'] ?? \Illuminate\Support\Str::limit($data['message'] ?? '', 80);
                                $isUnread = $n->read_at === null;
                            @endphp
                            <div class="list-group-item d-flex flex-wrap align-items-center justify-content-between gap-2 {{ $isUnread ? 'bg-light' : '' }}">
                                <div class="flex-grow-1">
                                    <a href="{{ $url }}" class="fw-semibold text-decoration-none {{ $isUnread ? 'text-dark' : 'text-muted' }}">
                                        {{ $title }}
                                    </a>
                                    @if(!empty($data['message']) && ($data['message'] ?? '') !== $title)
                                        <div class="small text-muted mt-1">{{ $data['message'] }}</div>
                                    @endif
                                    <div class="small text-muted mt-1">{{ $n->created_at?->diffForHumans() }}</div>
                                </div>
                                @if($isUnread)
                                    <form method="post" action="{{ route('dashboard.notifications.read', $n->id) }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            {{ __('page.notification.index.mark_read') }}
                                        </button>
                                    </form>
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
