const options = {
  pathOptions: {
    searchPath: route('dashboard.rooms.getListData'),
    deletePath: route('dashboard.rooms.destroy', ':id'),
    editPath: route('dashboard.rooms.edit', ':id'),
    showPath: route('dashboard.rooms.show', ':id'),
  },

  relations: {},

  actions: {},
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
