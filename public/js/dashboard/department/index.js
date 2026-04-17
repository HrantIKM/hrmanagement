const isAdmin = typeof window.$is === 'function' && window.$is('admin');

const options = {
  pathOptions: {
    searchPath: route('dashboard.departments.getListData'),
    deletePath: route('dashboard.departments.destroy', ':id'),
    editPath: route('dashboard.departments.edit', ':id'),
    showPath: route('dashboard.departments.show', ':id'),
  },

  relations: {},

  actions: isAdmin
    ? { show: false }
    : {
        show: true,
        edit: false,
        delete: false,
        clone: false,
      },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
