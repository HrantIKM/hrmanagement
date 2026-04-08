const options = {
  pathOptions: {
    searchPath: route('dashboard.meetings.getListData'),
    deletePath: route('dashboard.meetings.destroy', ':id'),
    editPath: route('dashboard.meetings.edit', ':id'),
    showPath: route('dashboard.meetings.show', ':id'),
  },

  relations: {},

  actions: {
      show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
