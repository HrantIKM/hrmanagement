const options = {
  pathOptions: {
    searchPath: route('dashboard.reviews.getListData'),
    showPath: route('dashboard.reviews.show', ':id'),
  },

  relations: {
    reviewer: 'name',
  },

  actions: {
    show: true,
    edit: false,
    delete: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
