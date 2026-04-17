function formatSalaryAmount(value) {
  if (value === null || value === undefined || value === '') {
    return '<span class="text-muted">—</span>';
  }
  const n = Number(value);
  if (Number.isNaN(n)) {
    return String(value);
  }
  const formatted = new Intl.NumberFormat(undefined, {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(n);
  return `<span class="salary-amount-cell">${formatted}</span>`;
}

function formatEffectiveDate(raw) {
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

const isAdmin = typeof window.$is === 'function' && window.$is('admin');

const options = {
  pathOptions: {
    searchPath: route('dashboard.salaries.getListData'),
    deletePath: route('dashboard.salaries.destroy', ':id'),
    editPath: route('dashboard.salaries.edit', ':id'),
    showPath: route('dashboard.salaries.show', ':id'),
  },

  relations: {
    user: 'name',
  },

  columnsRender: {
    amount: {
      render(data) {
        return formatSalaryAmount(data);
      },
    },
    effective_date: {
      render(data) {
        return formatEffectiveDate(data);
      },
    },
    change_reason_display: {
      render(data, type, row) {
        const key = (row.change_reason || '').toString().replace(/_/g, '-');
        const cls = key ? `status-pill status-pill--salary-${key}` : 'status-pill';
        const label = data || '';
        return `<span class="${cls}">${label}</span>`;
      },
    },
  },

  actions: isAdmin
    ? { show: false }
    : {
        show: true,
        edit: false,
        delete: false,
        clone: false,
      },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
