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

  actions: {
    show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
