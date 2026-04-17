/* eslint-disable global-require */
/* eslint-disable no-undef */

const Pusher = require('pusher-js');
window.Pusher = Pusher;

const EchoModule = require('laravel-echo');
const EchoClass = EchoModule.default || EchoModule;

const root = document.getElementById('messages-app');
if (!root) {
  // no-op
} else {
  let broadcast = {};
  try {
    broadcast = JSON.parse(root.dataset.broadcast || '{}');
  } catch (e) {
    broadcast = {};
  }

  if (broadcast.key) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const auth = {
      headers: {
        'X-CSRF-TOKEN': csrf,
      },
    };

    const echoOptions =
      broadcast.driver === 'reverb'
        ? {
            broadcaster: 'reverb',
            key: broadcast.key,
            wsHost: broadcast.wsHost || 'localhost',
            wsPort: broadcast.wsPort ?? 80,
            wssPort: broadcast.wssPort ?? 443,
            forceTLS: !!broadcast.forceTLS,
            enabledTransports: ['ws', 'wss'],
            authEndpoint: broadcast.authEndpoint,
            auth,
            namespace: false,
          }
        : {
            broadcaster: 'pusher',
            key: broadcast.key,
            cluster: broadcast.cluster || 'mt1',
            forceTLS: true,
            encrypted: true,
            authEndpoint: broadcast.authEndpoint,
            auth,
            namespace: false,
          };

    window.Echo = new EchoClass(echoOptions);
  }

  if (!root.dataset.peerId) {
    // Inbox only (no thread script needed for Echo)
  } else {
    const http = window.axios;
    if (!http) {
      // eslint-disable-next-line no-console
      console.error('Chat: window.axios is missing. Ensure dashboard-app.js loads before chat.js.');
    }

    /**
     * Resolve /{locale}/dashboard/messages/{id} so store/history URLs always match the open page
     * (route() in Blade can omit locale or host and break POST/history).
     */
    function messagesPathsFromLocation() {
      const pathname = (window.location.pathname || '/').replace(/\/+$/, '') || '/';
      const m = pathname.match(/^(.*\/messages)\/(\d+)$/);
      if (!m) {
        return { storeUrl: null, peerFromPath: null };
      }
      return {
        storeUrl: m[1],
        peerFromPath: parseInt(m[2], 10),
      };
    }

    const paths = messagesPathsFromLocation();
    const authId = parseInt(root.dataset.authId, 10);
    const peerId = Number.isFinite(paths.peerFromPath)
      ? paths.peerFromPath
      : parseInt(root.dataset.peerId, 10);
    const storeUrl = paths.storeUrl || root.dataset.storeUrl;
    const deleteUrlTemplate = root.dataset.deleteUrlTemplate || '';
    const deleteThreadUrlTemplate = root.dataset.deleteThreadUrlTemplate || '';
    const historyTemplate = root.dataset.historyTemplate || '';

    const threadEl = document.getElementById('messages-thread');
    const form = document.getElementById('messages-composer-form');
    const input = document.getElementById('messages-body-input');
    const sendBtn = document.getElementById('messages-send-btn');
    const deleteThreadBtn = document.getElementById('messages-delete-thread-btn');
    const btnLabel = sendBtn?.querySelector('.btn-label');
    const btnSending = sendBtn?.querySelector('.btn-sending');

    const seenIds = new Set();

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function formatTime(iso) {
      if (!iso) return '';
      const d = new Date(iso);
      return d.toLocaleString(undefined, {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      });
    }

    function appendBubble(msg) {
      if (seenIds.has(msg.id)) return;
      seenIds.add(msg.id);
      const mine = !!msg.is_mine;
      const wrap = document.createElement('div');
      wrap.className = `messages-bubble ${mine ? 'is-mine' : 'is-theirs'}`;
      wrap.dataset.messageId = String(msg.id);
      wrap.innerHTML = `${mine ? `<button type="button" class="messages-bubble__delete" data-delete-message-id="${msg.id}" title="Delete message"><i class="fas fa-trash-alt"></i></button>` : ''}<div class="messages-bubble__body">${escapeHtml(msg.body)}</div><div class="messages-bubble__time">${formatTime(msg.created_at)}</div>`;
      threadEl.appendChild(wrap);
      threadEl.scrollTop = threadEl.scrollHeight;
    }

    function bindDeleteHandlers() {
      if (!threadEl || !http) return;
      threadEl.querySelectorAll('[data-delete-message-id]').forEach((btn) => {
        if (btn.dataset.bound === '1') return;
        btn.dataset.bound = '1';
        btn.addEventListener('click', (e) => {
          e.stopPropagation();
          const msgId = btn.getAttribute('data-delete-message-id');
          if (!msgId || !deleteUrlTemplate) return;
          // eslint-disable-next-line no-alert
          if (!window.confirm('Delete this message?')) return;
          const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
          http.delete(deleteUrlTemplate.replace('__MESSAGE_ID__', String(msgId)), {
            headers: {
              'X-CSRF-TOKEN': token,
              'X-Requested-With': 'XMLHttpRequest',
              Accept: 'application/json',
            },
          }).then(() => {
            const bubble = btn.closest('.messages-bubble');
            if (bubble) {
              const id = Number(bubble.dataset.messageId);
              if (Number.isFinite(id)) seenIds.delete(id);
              bubble.remove();
            }
          }).catch(() => {
            // eslint-disable-next-line no-alert
            alert('Could not delete message.');
          });
        });
      });
    }

    function getHistoryUrl() {
      if (paths.storeUrl) {
        return `${paths.storeUrl}/history/${peerId}`;
      }
      if (historyTemplate) {
        return historyTemplate.replace('__PEER_ID__', String(peerId));
      }
      return '';
    }

    function normalizeBroadcastPayload(raw) {
      let data = raw;
      if (typeof data === 'string') {
        try {
          data = JSON.parse(data);
        } catch (e) {
          return null;
        }
      }
      if (data && typeof data === 'object' && data.data !== undefined) {
        const inner = data.data;
        if (typeof inner === 'string') {
          try {
            data = JSON.parse(inner);
          } catch (e) {
            return data;
          }
        } else if (inner && typeof inner === 'object') {
          data = inner;
        }
      }
      return data;
    }

    function tryAppendIncomingFromBroadcast(raw) {
      const data = normalizeBroadcastPayload(raw);
      if (!data || typeof data !== 'object') return;
      const m = data.message;
      if (!m) return;
      const sid = Number(m.sender_id);
      const rid = Number(m.receiver_id);
      if (sid !== Number(peerId) || rid !== Number(authId)) {
        return;
      }
      appendBubble({
        id: m.id,
        body: m.body,
        created_at: m.created_at,
        is_mine: false,
      });
    }

    function loadHistory() {
      if (!threadEl || !http) return;
      if (!Number.isFinite(peerId)) return;
      const url = getHistoryUrl();
      if (!url) return;
      http
        .get(url)
        .then((res) => {
          threadEl.innerHTML = '';
          seenIds.clear();
          (res.data.messages || []).forEach(appendBubble);
          bindDeleteHandlers();
          threadEl.scrollTop = threadEl.scrollHeight;
        })
        .catch(() => {
          threadEl.innerHTML = '<p class="text-danger small p-2">Could not load messages.</p>';
        });
    }

    /** Polling fallback when WebSockets / private auth fail (new messages without refresh). */
    let messagePollTimer = null;
    function startMessagePoll() {
      const url = getHistoryUrl();
      if (!url || !http) return;
      if (messagePollTimer) {
        clearInterval(messagePollTimer);
      }
      messagePollTimer = setInterval(() => {
        if (document.visibilityState !== 'visible') return;
        http
          .get(url, {
            headers: {
              Accept: 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
            },
          })
          .then((res) => {
            (res.data.messages || []).forEach((msg) => {
              if (!seenIds.has(msg.id)) appendBubble(msg);
            });
          })
          .catch(() => {});
      }, 3500);
    }

    function setSending(is) {
      if (!sendBtn || !input) return;
      sendBtn.disabled = is;
      input.disabled = is;
      if (btnLabel) btnLabel.classList.toggle('d-none', is);
      if (btnSending) btnSending.classList.toggle('d-none', !is);
    }

    if (form && input) {
      input.addEventListener('keydown', (e) => {
        if (e.isComposing) return;
        if (e.key !== 'Enter' || e.shiftKey) return;
        e.preventDefault();
        const body = (input.value || '').trim();
        if (!body) return;
        if (typeof form.requestSubmit === 'function') {
          form.requestSubmit();
        } else {
          form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
        }
      });

      form.addEventListener('submit', (e) => {
        e.preventDefault();
        const body = (input.value || '').trim();
        if (!body) return;
        if (!http) return;
        if (!Number.isFinite(peerId)) {
          // eslint-disable-next-line no-alert
          alert('Invalid conversation.');
          return;
        }
        if (!storeUrl) {
          // eslint-disable-next-line no-alert
          alert('Message endpoint missing. Reload the page.');
          return;
        }
        setSending(true);
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const fd = new FormData();
        fd.append('_token', token);
        fd.append('receiver_id', String(peerId));
        fd.append('body', body);
        http
          .post(storeUrl, fd, {
            headers: {
              'X-CSRF-TOKEN': token,
              'X-Requested-With': 'XMLHttpRequest',
              Accept: 'application/json',
            },
          })
          .then((res) => {
            if (res.data.message) appendBubble(res.data.message);
            bindDeleteHandlers();
            input.value = '';
            input.focus();
          })
          .catch((err) => {
            const msg = err.response?.data?.message
              || err.response?.data?.errors?.body?.[0]
              || err.response?.data?.errors?.receiver_id?.[0]
              || 'Send failed.';
            // eslint-disable-next-line no-alert
            alert(msg);
          })
          .finally(() => setSending(false));
      });
    }

    loadHistory();
    startMessagePoll();
    bindDeleteHandlers();

    if (deleteThreadBtn && deleteThreadUrlTemplate && Number.isFinite(peerId) && http) {
      deleteThreadBtn.addEventListener('click', () => {
        // eslint-disable-next-line no-alert
        if (!window.confirm('Delete all your messages in this chat?')) return;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        http.delete(deleteThreadUrlTemplate.replace('__PEER_ID__', String(peerId)), {
          headers: {
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
          },
        }).then(() => {
          threadEl.innerHTML = '';
          seenIds.clear();
        }).catch(() => {
          // eslint-disable-next-line no-alert
          alert('Could not delete chat.');
        });
      });
    }

    if (broadcast.key && window.Echo) {
      const channel = window.Echo.private(`chat.${authId}`);
      const onIncoming = (payload) => tryAppendIncomingFromBroadcast(payload);
      channel.listen('.message.sent', onIncoming);
      channel.listen('message.sent', onIncoming);
    }
  }
}
