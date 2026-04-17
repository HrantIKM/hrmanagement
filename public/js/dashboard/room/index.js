const isAdmin = typeof window.$is === 'function' && window.$is('admin');

const options = {
  pathOptions: {
    searchPath: route('dashboard.rooms.getListData'),
    deletePath: route('dashboard.rooms.destroy', ':id'),
    editPath: route('dashboard.rooms.edit', ':id'),
    showPath: route('dashboard.rooms.show', ':id'),
  },

  relations: {},

  columnsRender: {
    name: {
      render(data) {
        const safe = data === null || data === undefined ? '' : String(data).replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return `<span class="room-name-cell">${safe}</span>`;
      },
    },
  },

  actions: isAdmin
    ? {}
    : {
        show: true,
        edit: false,
        delete: false,
        clone: false,
      },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
