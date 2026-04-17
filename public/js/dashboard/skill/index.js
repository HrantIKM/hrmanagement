const isAdmin = typeof window.$is === 'function' && window.$is('admin');

const options = {
  pathOptions: {
    searchPath: route('dashboard.skills.getListData'),
    deletePath: route('dashboard.skills.destroy', ':id'),
    editPath: route('dashboard.skills.edit', ':id'),
    showPath: route('dashboard.skills.show', ':id'),
  },

  relations: {},

  columnsRender: {
    name: {
      render(data) {
        const safe = data === null || data === undefined ? '' : String(data).replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return `<span class="skill-name-cell">${safe}</span>`;
      },
    },
    department: {
      render(data, type, row) {
        const name = row.department?.name ?? (typeof data === 'string' ? data : '');
        if (!name) {
          return '<span class="skill-dept-pill skill-dept-pill--none">—</span>';
        }
        const safe = String(name).replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return `<span class="skill-dept-pill" title="${safe}">${safe}</span>`;
      },
    },
    category_label: {
      render(data, type, row) {
        const k = (row.category || '').toString().replace(/_/g, '-');
        const cls = k ? `status-pill status-pill--skill-${k}` : 'status-pill';
        return `<span class="${cls}">${data || ''}</span>`;
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
