const options = {
  pathOptions: {
    searchPath: route('dashboard.leave-balances.getListData'),
    deletePath: route('dashboard.leave-balances.destroy', ':id'),
    editPath: route('dashboard.leave-balances.edit', ':id'),
    showPath: route('dashboard.leave-balances.show', ':id'),
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
      show: true,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
