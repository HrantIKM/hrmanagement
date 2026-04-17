function formatProjectDate(raw) {
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
  return `<span class="project-date-cell"><span class="project-date-cell__dow">${dow}</span><span class="project-date-cell__main">${main}</span></span>`;
}

const options = {
  pathOptions: {
    searchPath: route('dashboard.projects.getListData'),
    deletePath: route('dashboard.projects.destroy', ':id'),
    editPath: route('dashboard.projects.edit', ':id'),
    showPath: route('dashboard.projects.show', ':id'),
  },

  relations: {},

  columnsRender: {
    start_date: {
      render(data) {
        return formatProjectDate(data);
      },
    },
    end_date: {
      render(data) {
        return formatProjectDate(data);
      },
    },
  },

  actions: {
    show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
