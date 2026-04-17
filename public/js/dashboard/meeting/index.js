function formatMeetingDateTime(raw) {
  if (raw === null || raw === undefined || raw === '') {
    return '<span class="text-muted">—</span>';
  }
  const d = new Date(raw);
  if (Number.isNaN(d.getTime())) {
    return String(raw);
  }
  return `<span class="meeting-dt-cell">${new Intl.DateTimeFormat(undefined, {
    dateStyle: 'medium',
    timeStyle: 'short',
  }).format(d)}</span>`;
}

const options = {
  pathOptions: {
    searchPath: route('dashboard.meetings.getListData'),
    deletePath: route('dashboard.meetings.destroy', ':id'),
    editPath: route('dashboard.meetings.edit', ':id'),
    showPath: route('dashboard.meetings.show', ':id'),
  },

  relations: {
    room: 'name',
  },

  columnsRender: {
    start_at: {
      render(data) {
        return formatMeetingDateTime(data);
      },
    },
    end_at: {
      render(data) {
        return formatMeetingDateTime(data);
      },
    },
  },

  actions: {},
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
