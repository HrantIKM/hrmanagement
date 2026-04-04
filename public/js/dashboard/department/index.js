const options = {
  pathOptions: {
    searchPath: route('dashboard.departments.getListData'),
    deletePath: route('dashboard.departments.destroy', ':id'),
    editPath: route('dashboard.departments.edit', ':id'),
    showPath: route('dashboard.departments.show', ':id'),
  },

  relations: {},

  actions: {
      show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
