/**
 * Mark dashboard notifications as read when the user follows a notification link.
 */
$(function () {
  $(document).on('click', 'a.js-notification-item', function (e) {
    const $a = $(this);
    const readUrl = $a.data('readUrl');
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const href = $a.attr('href') || '#';

    if (!readUrl || !token || !$a.hasClass('is-unread')) {
      return;
    }

    e.preventDefault();

    fetch(readUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({}),
    })
      .then((r) => r.json())
      .then((data) => {
        if (typeof data.unread_count === 'number') {
          const $badge = $('.js-notification-badge');
          if (data.unread_count > 0) {
            $badge.text(data.unread_count).show();
          } else {
            $badge.hide().text('0');
          }
        }
        $a.removeClass('is-unread fw-semibold').removeAttr('data-read-url');
      })
      .catch(() => {})
      .finally(() => {
        if (href && href !== '#') {
          window.location.assign(href);
        }
      });
  });

  $(document).on('click', '.js-mark-all-notifications-read', function (e) {
    e.preventDefault();
    const url = $(this).data('url');
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!url || !token) {
      return;
    }
    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({}),
    })
      .then((r) => r.json())
      .then(() => {
        $('.js-notification-badge').hide().text('0');
        $('a.js-notification-item').removeClass('is-unread fw-semibold');
      })
      .catch(() => {});
  });
});
