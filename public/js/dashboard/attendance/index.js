const options = {
  pathOptions: {
    searchPath: route('dashboard.attendances.getListData'),
    deletePath: route('dashboard.attendances.destroy', ':id'),
    editPath: route('dashboard.attendances.edit', ':id'),
    showPath: route('dashboard.attendances.show', ':id'),
  },

  relations: {},

  actions: {
      show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
