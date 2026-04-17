function userTableAvatarCell(row) {
  const url = row.avatar_url;
  const showHref = route('dashboard.users.show', row.id);
  const size = 40;
  const first = (row.first_name || '').charAt(0);
  const last = (row.last_name || '').charAt(0);
  let initials = (first + last).toUpperCase();
  if (!initials) {
    initials = (row.email || '?').slice(0, 2).toUpperCase();
  }
  const name = [row.first_name, row.last_name].filter(Boolean).join(' ').trim() || row.email || '';
  const hue = (Number(row.id) || 0) * 37 % 360;
  const titleAttr = name.replace(/"/g, '&quot;');

  if (url) {
    return `<a href="${showHref}" class="d-inline-block" title="${titleAttr}"><img src="${url}" alt="" width="${size}" height="${size}" class="rounded-circle border" style="object-fit:cover"/></a>`;
  }

  return `<a href="${showHref}" class="d-inline-flex align-items-center justify-content-center rounded-circle text-white text-decoration-none" style="width:${size}px;height:${size}px;background:hsl(${hue},52%,42%);font-size:${Math.round(size * 0.38)}px;font-weight:600" title="${titleAttr}">${initials}</a>`;
}

const isAdmin = typeof window.$is === 'function' && window.$is('admin');

const options = {
  order: [[1, 'desc']],
  pathOptions: {
    searchPath: route('dashboard.users.getListData'),
    deletePath: route('dashboard.users.destroy', ':id'),
    editPath: route('dashboard.users.edit', ':id'),
    showPath: route('dashboard.users.show', ':id'),
  },

  relations: {
    roles: 'name',
    department: 'name',
    position: 'title',
  },

  columnsRender: {
    avatar_url: {
      render(data, type, row) {
        return userTableAvatarCell(row);
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
