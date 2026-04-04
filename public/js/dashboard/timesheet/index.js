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

  actions: {
      show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
