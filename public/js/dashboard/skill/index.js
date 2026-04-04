const options = {
  pathOptions: {
    searchPath: route('dashboard.skills.getListData'),
    deletePath: route('dashboard.skills.destroy', ':id'),
    editPath: route('dashboard.skills.edit', ':id'),
    showPath: route('dashboard.skills.show', ':id'),
  },

  relations: {},

  actions: {
      show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
