const options = {
  pathOptions: {
    searchPath: route('dashboard.leave-requests.getListData'),
    deletePath: route('dashboard.leave-requests.destroy', ':id'),
    editPath: route('dashboard.leave-requests.edit', ':id'),
    showPath: route('dashboard.leave-requests.show', ':id'),
  },

  relations: {},

  actions: {
      show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
