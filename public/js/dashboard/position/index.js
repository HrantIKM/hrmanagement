function formatSalaryCell(value) {
  if (value === null || value === undefined || value === '') {
    return '<span class="text-muted">—</span>';
  }
  const n = Number(value);
  if (Number.isNaN(n)) {
    return String(value);
  }
  const formatted = new Intl.NumberFormat(undefined, {
    maximumFractionDigits: 0,
  }).format(n);
  return `<span class="position-salary-cell">${formatted}</span>`;
}

const options = {
  pathOptions: {
    searchPath: route('dashboard.positions.getListData'),
    deletePath: route('dashboard.positions.destroy', ':id'),
    editPath: route('dashboard.positions.edit', ':id'),
    showPath: route('dashboard.positions.show', ':id'),
  },

  relations: {},

  columnsRender: {
    department: {
      render(data, type, row) {
        const name = row.department?.name ?? (typeof data === 'string' ? data : '');
        if (!name) {
          return '<span class="position-dept-pill position-dept-pill--none">—</span>';
        }
        const safe = String(name).replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return `<span class="position-dept-pill" title="${safe}">${safe}</span>`;
      },
    },
    min_salary: {
      render(data) {
        return formatSalaryCell(data);
      },
    },
    max_salary: {
      render(data) {
        return formatSalaryCell(data);
      },
    },
    grade_level: {
      render(data) {
        if (data === null || data === undefined || data === '') {
          return '<span class="text-muted">—</span>';
        }
        const safe = String(data).replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return `<span class="position-grade">${safe}</span>`;
      },
    },
  },

  actions: {
    show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
