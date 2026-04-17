function formatLeaveDate(raw) {
  if (raw === null || raw === undefined || raw === '') {
    return '<span class="text-muted">—</span>';
  }
  const s = String(raw);
  const datePart = s.length >= 10 ? s.slice(0, 10) : s;
  const d = new Date(`${datePart}T12:00:00`);
  if (Number.isNaN(d.getTime())) {
    return s;
  }
  const dow = new Intl.DateTimeFormat(undefined, { weekday: 'short' }).format(d);
  const main = new Intl.DateTimeFormat(undefined, {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  }).format(d);
  return `<span class="leave-date-cell"><span class="leave-date-cell__dow">${dow}</span><span class="leave-date-cell__main">${main}</span></span>`;
}

const options = {
  pathOptions: {
    searchPath: route('dashboard.leave-requests.getListData'),
    deletePath: route('dashboard.leave-requests.destroy', ':id'),
    editPath: route('dashboard.leave-requests.edit', ':id'),
    showPath: route('dashboard.leave-requests.show', ':id'),
  },

  relations: {},

  columnsRender: {
    type_display: {
      render(data, type, row) {
        const k = (row.type || '').toString().replace(/_/g, '-');
        const cls = k ? `status-pill status-pill--leave-${k}` : 'status-pill';
        return `<span class="${cls}">${data || ''}</span>`;
      },
    },
    start_date: {
      render(data) {
        return formatLeaveDate(data);
      },
    },
    end_date: {
      render(data) {
        return formatLeaveDate(data);
      },
    },
    user_id: {
      render(data, type, row) {
        if (row.user) {
          const name = [row.user.first_name, row.user.last_name].filter(Boolean).join(' ').trim();
          return name || String(data ?? '');
        }
        return data ?? '';
      },
    },
  },

  actions: {
    show: false,
    edit(row) {
      const locked = row.can_edit_leave_request === false;
      const href = locked
        ? route('dashboard.leave-requests.show', row.id)
        : route('dashboard.leave-requests.edit', row.id);
      const title = locked ? 'View' : 'Edit';
      const icon = locked ? 'flaticon-eye' : 'flaticon-edit';
      return `<a href="${href}" class="btn" title="${title}">
                    <i class="${icon}"></i>
                </a>`;
    },
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
