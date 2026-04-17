@php
    use Illuminate\Support\Str;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid py-3 messages-page">
        @if($broadcastConfig['driver'] === 'null' || empty($broadcastConfig['key']))
            <div class="messages-broadcast-hint mb-3">
                <i class="fas fa-info-circle me-1"></i> {{ __('message.broadcast_unconfigured') }}
            </div>
        @endif

        <div id="messages-app"
             class="messages-layout messages-layout--fill"
             data-auth-id="{{ auth()->id() }}"
             data-peer-id="{{ $peerId ?? '' }}"
             data-store-url="{{ $messageStoreUrl }}"
             data-delete-url-template="{{ route('dashboard.messages.destroy', ['message' => '__MESSAGE_ID__'], false) }}"
             data-delete-thread-url-template="{{ route('dashboard.messages.destroyThread', ['user' => '__PEER_ID__'], false) }}"
             data-history-template="{{ $historyUrlTemplate }}"
             data-thread-template="{{ $threadUrlTemplate }}"
             data-broadcast="{{ e(json_encode($broadcastConfig)) }}"
        >
            <aside class="messages-sidebar">
                <div class="messages-sidebar__head">
                    <h2 class="messages-sidebar__title">{{ __('menu.messages') }}</h2>
                    <label class="messages-sidebar__field-label" for="messages-new-peer">{{ __('message.new_message') }}</label>
                    <select class="form-select form-select-sm messages-new-peer-select" id="messages-new-peer">
                        <option value="">{{ __('message.select_person') }}</option>
                        @foreach($usersForChat as $u)
                            <option value="{{ $u->id }}" {{ (int) ($peerId ?? 0) === (int) $u->id ? 'selected' : '' }}>
                                {{ $u->name }} @if($u->email) — {{ $u->email }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="messages-sidebar__head pt-0">
                    <input type="search" class="messages-sidebar__search" id="messages-filter-conv"
                           placeholder="{{ __('message.search_people') }}" autocomplete="off">
                </div>
                <div class="messages-conversation-list" id="messages-conv-list">
                    @forelse($conversations as $row)
                        @php
                            $p = $row->peer;
                            $lm = $row->last_message;
                            $active = $peerId && (int) $peerId === (int) $p->id;
                        @endphp
                        <a href="{{ route('dashboard.messages.thread', $p) }}"
                           class="messages-conv-item @if($active) is-active @endif"
                           data-name="{{ Str::lower($p->name) }}"
                           data-email="{{ Str::lower((string) $p->email) }}">
                            @if($p->avatar_url)
                                <img src="{{ $p->avatar_url }}" alt="" class="messages-avatar">
                            @else
                                <span class="messages-avatar">{{ Str::upper(Str::substr($p->first_name ?? '', 0, 1) . Str::substr($p->last_name ?? '', 0, 1)) }}</span>
                            @endif
                            <div class="messages-conv-meta">
                                <div class="d-flex justify-content-between align-items-start gap-1">
                                    <span class="messages-conv-name">{{ $p->name }}</span>
                                    <span class="messages-conv-time">{{ $lm->created_at?->diffForHumans() }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="messages-conv-preview flex-grow-1">{{ Str::limit($lm->body, 48) }}</span>
                                    @if($row->unread_count > 0)
                                        <span class="messages-unread-badge">{{ $row->unread_count > 99 ? '99+' : $row->unread_count }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-3 py-4 text-muted small text-center">{{ __('message.no_conversations') }}</div>
                    @endforelse
                </div>
            </aside>

            <section class="messages-main">
                @if($peer)
                    <header class="messages-main__header">
                        @if($peer->avatar_url)
                            <img src="{{ $peer->avatar_url }}" alt="" class="messages-avatar messages-main__avatar">
                        @else
                            <span class="messages-avatar messages-main__avatar">
                                {{ Str::upper(Str::substr($peer->first_name ?? '', 0, 1) . Str::substr($peer->last_name ?? '', 0, 1)) }}
                            </span>
                        @endif
                        <div class="messages-main__peer">
                            <div class="messages-main__peer-name">{{ $peer->name }}</div>
                            <div class="messages-main__peer-email">{{ $peer->email }}</div>
                        </div>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-sm btn-outline-danger" id="messages-delete-thread-btn">
                                <i class="fas fa-trash-alt me-1"></i>{{ __('message.delete_chat') }}
                            </button>
                        </div>
                    </header>

                    <div class="messages-thread" id="messages-thread" aria-live="polite"></div>

                    <footer class="messages-composer">
                        <form id="messages-composer-form" class="messages-composer__inner">
                            @csrf
                            <textarea id="messages-body-input" class="form-control" rows="2"
                                      placeholder="{{ __('message.type_message') }}" maxlength="10000"></textarea>
                            <button type="submit" class="btn btn-primary btn-send" id="messages-send-btn" aria-label="{{ __('message.send') }}">
                                <span class="btn-label"><i class="fas fa-paper-plane" aria-hidden="true"></i><span class="visually-hidden">{{ __('message.send') }}</span></span>
                                <span class="btn-sending d-none">{{ __('message.sending') }}</span>
                            </button>
                        </form>
                    </footer>
                @else
                    <div class="messages-empty-state">
                        <div class="messages-empty-state__icon">
                            <i class="far fa-comment-dots"></i>
                        </div>
                        <h5 class="text-dark mb-2">{{ __('message.select_person') }}</h5>
                        <p class="mb-0 small">{{ __('message.select_person_hint') }}</p>
                    </div>
                @endif
            </section>
        </div>
    </div>

    <x-slot name="scripts">
        @if($peer)
            <script src="{{ mix('js/dashboard/chat.js') }}"></script>
        @endif
        <script>
            (function () {
                var sel = document.getElementById('messages-new-peer');
                var tpl = @json($threadUrlTemplate ?? '');
                if (sel && tpl) {
                    sel.addEventListener('change', function () {
                        var id = this.value;
                        if (!id) return;
                        window.location.href = tpl.replace('__PEER_ID__', id);
                    });
                }
                var q = document.getElementById('messages-filter-conv');
                if (q) {
                    q.addEventListener('input', function () {
                        var term = (this.value || '').toLowerCase().trim();
                        document.querySelectorAll('.messages-conv-item').forEach(function (el) {
                            var name = el.getAttribute('data-name') || '';
                            var email = el.getAttribute('data-email') || '';
                            el.style.display = !term || name.indexOf(term) !== -1 || email.indexOf(term) !== -1 ? '' : 'none';
                        });
                    });
                }
            })();
        </script>
    </x-slot>
</x-dashboard.layouts.app>
