const options = {
  pathOptions: {
    searchPath: route('dashboard.positions.getListData'),
    deletePath: route('dashboard.positions.destroy', ':id'),
    editPath: route('dashboard.positions.edit', ':id'),
    showPath: route('dashboard.positions.show', ':id'),
  },

  relations: {
    department: 'name',
  },

  actions: {
      show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
