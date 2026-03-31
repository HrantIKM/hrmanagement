const options = {
  pathOptions: {
    searchPath: route('dashboard.users.getListData'),
    deletePath: route('dashboard.users.destroy', ':id'),
    editPath: route('dashboard.users.edit', ':id'),
    showPath: route('dashboard.users.show', ':id'),
  },

  relations: {
    roles: 'name',
  },

  actions: {
    show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
