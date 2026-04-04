const options = {
  pathOptions: {
    searchPath: route('dashboard.tasks.getListData'),
    deletePath: route('dashboard.tasks.destroy', ':id'),
    editPath: route('dashboard.tasks.edit', ':id'),
    showPath: route('dashboard.tasks.show', ':id'),
  },

  relations: {
    project: 'name',
    user: 'name',
  },

  actions: {
      show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
