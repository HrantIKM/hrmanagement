function formatClosingDate(raw) {
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
    searchPath: route('dashboard.vacancies.getListData'),
    deletePath: route('dashboard.vacancies.destroy', ':id'),
    editPath: route('dashboard.vacancies.edit', ':id'),
    showPath: route('dashboard.vacancies.show', ':id'),
  },

  relations: {},

  columnsRender: {
    position: {
      render(data, type, row) {
        const title = row.position?.title ?? (typeof data === 'string' ? data : '');
        if (!title) {
          return '<span class="vacancy-position-pill vacancy-position-pill--none">—</span>';
        }
        const safe = String(title).replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return `<span class="vacancy-position-pill" title="${safe}">${safe}</span>`;
      },
    },
    closing_date: {
      render(data) {
        return formatClosingDate(data);
      },
    },
    skills: {
      render(data) {
        if (!data || !Array.isArray(data) || data.length === 0) {
          return '<span class="text-muted">—</span>';
        }
        const names = data.map((s) => s?.name).filter(Boolean);
        const joined = names.join(', ');
        const safe = joined.replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return `<span class="vacancy-skills-preview" title="${safe}">${safe}</span>`;
      },
    },
  },

  actions: {
    show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
