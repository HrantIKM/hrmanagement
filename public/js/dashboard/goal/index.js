const options = {
  pathOptions: {
    searchPath: route('dashboard.goals.getListData'),
    deletePath: route('dashboard.goals.destroy', ':id'),
    editPath: route('dashboard.goals.edit', ':id'),
    showPath: route('dashboard.goals.show', ':id'),
  },

  relations: {
    user: 'name',
  },

  columnsRender: {
    progress_percent: {
      render(data) {
        if (data === null || data === undefined || data === '') {
          return '—';
        }
        return `${data}%`;
      },
    },
  },

  actions: {
    show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
