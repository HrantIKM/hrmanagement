const options = {
  pathOptions: {
    searchPath: route('dashboard.leave-requests.getListData'),
    deletePath: route('dashboard.leave-requests.destroy', ':id'),
    editPath: route('dashboard.leave-requests.edit', ':id'),
    showPath: route('dashboard.leave-requests.show', ':id'),
  },

  relations: {},

  columnsRender: {
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
