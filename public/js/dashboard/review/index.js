const options = {
  pathOptions: {
    searchPath: route('dashboard.reviews.getListData'),
    deletePath: route('dashboard.reviews.destroy', ':id'),
    editPath: route('dashboard.reviews.edit', ':id'),
    showPath: route('dashboard.reviews.show', ':id'),
  },

  relations: {
    user: 'name',
    reviewer: 'name',
  },

  actions: {
    show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
