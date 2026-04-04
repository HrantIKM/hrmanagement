const options = {
  pathOptions: {
    searchPath: route('dashboard.projects.getListData'),
    deletePath: route('dashboard.projects.destroy', ':id'),
    editPath: route('dashboard.projects.edit', ':id'),
    showPath: route('dashboard.projects.show', ':id'),
  },

  relations: {},

  actions: {
      show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
