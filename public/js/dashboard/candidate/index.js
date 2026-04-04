const options = {
  pathOptions: {
    searchPath: route('dashboard.candidates.getListData'),
    deletePath: route('dashboard.candidates.destroy', ':id'),
    editPath: route('dashboard.candidates.edit', ':id'),
    showPath: route('dashboard.candidates.show', ':id'),
  },

  relations: {
    vacancy: 'title',
    skills: 'name',
  },

  actions: {
      show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
