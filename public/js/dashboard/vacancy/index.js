const options = {
  pathOptions: {
    searchPath: route('dashboard.vacancies.getListData'),
    deletePath: route('dashboard.vacancies.destroy', ':id'),
    editPath: route('dashboard.vacancies.edit', ':id'),
    showPath: route('dashboard.vacancies.show', ':id'),
  },

  relations: {
    position: 'title',
    skills: 'name',
  },

  actions: {
      show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
