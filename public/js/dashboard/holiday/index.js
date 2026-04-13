const options = {
  pathOptions: {
    searchPath: route('dashboard.holidays.getListData'),
    deletePath: route('dashboard.holidays.destroy', ':id'),
    editPath: route('dashboard.holidays.edit', ':id'),
    showPath: route('dashboard.holidays.show', ':id'),
  },

  relations: {},

  actions: {
      show: true,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
