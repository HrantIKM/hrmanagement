function formatTsDate(raw) {
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

function formatClock(raw) {
  if (raw === null || raw === undefined || raw === '') {
    return '<span class="text-muted">—</span>';
  }
  const s = String(raw);
  const m = s.match(/(\d{1,2}):(\d{2})(?::(\d{2}))?/);
  if (!m) {
    return `<span class="ts-time-cell">${s}</span>`;
  }
  const hh = m[1].padStart(2, '0');
  const mm = m[2];
  return `<span class="ts-time-cell">${hh}:${mm}</span>`;
}

function formatDurationMinutes(min) {
  if (min === null || min === undefined || min === '') {
    return '<span class="text-muted">—</span>';
  }
  const n = parseInt(min, 10);
  if (Number.isNaN(n)) {
    return String(min);
  }
  const h = Math.floor(n / 60);
  const m = n % 60;
  if (h <= 0) {
    return `<span class="ts-duration-cell">${m}m</span>`;
  }
  return `<span class="ts-duration-cell">${h}h ${m}m</span>`;
}

const options = {
  pathOptions: {
    searchPath: route('dashboard.timesheets.getListData'),
    deletePath: route('dashboard.timesheets.destroy', ':id'),
    editPath: route('dashboard.timesheets.edit', ':id'),
    showPath: route('dashboard.timesheets.show', ':id'),
  },

  relations: {
    user: 'name',
    task: 'title',
  },

  columnsRender: {
    date: {
      render(data) {
        return formatTsDate(data);
      },
    },
    start_time: {
      render(data) {
        return formatClock(data);
      },
    },
    end_time: {
      render(data) {
        return formatClock(data);
      },
    },
    duration_minutes: {
      render(data) {
        return formatDurationMinutes(data);
      },
    },
    note: {
      render(data) {
        if (data === null || data === undefined || data === '') {
          return '<span class="text-muted">—</span>';
        }
        const t = String(data).replace(/</g, '&lt;').replace(/>/g, '&gt;');
        const short = t.length > 72 ? `${t.slice(0, 72)}…` : t;
        return `<span class="small text-body" title="${t}">${short}</span>`;
      },
    },
  },

  actions: {
    show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
