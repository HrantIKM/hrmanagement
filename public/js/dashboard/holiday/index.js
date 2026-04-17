function holidayTrans(key) {
  // eslint-disable-next-line no-undef
  return typeof $trans === 'function' ? $trans(key) : key;
}

function formatHolidayDate(raw) {
  if (raw === null || raw === undefined || raw === '') {
    return '<span class="text-muted">—</span>';
  }
  const s = String(raw);
  const datePart = s.length >= 10 ? s.slice(0, 10) : s;
  const d = new Date(`${datePart}T12:00:00`);
  if (Number.isNaN(d.getTime())) {
    return s;
  }
  const dow = new Intl.DateTimeFormat(undefined, { weekday: 'short' }).format(d);
  const main = new Intl.DateTimeFormat(undefined, {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  }).format(d);
  return `<span class="holiday-date-cell"><span class="holiday-date-cell__dow">${dow}</span><span class="holiday-date-cell__main">${main}</span></span>`;
}

const isAdmin = typeof window.$is === 'function' && window.$is('admin');

const options = {
  pathOptions: {
    searchPath: route('dashboard.holidays.getListData'),
    deletePath: route('dashboard.holidays.destroy', ':id'),
    editPath: route('dashboard.holidays.edit', ':id'),
    showPath: route('dashboard.holidays.show', ':id'),
  },

  relations: {},

  columnsRender: {
    date: {
      render(data) {
        return formatHolidayDate(data);
      },
    },
    is_public: {
      render(data) {
        const isPublic = data === true || data === 1 || data === '1';
        const label = isPublic
          ? holidayTrans('holiday.index.public_yes')
          : holidayTrans('holiday.index.public_no');
        const cls = isPublic ? 'status-pill status-pill--holiday-public' : 'status-pill status-pill--holiday-internal';
        return `<span class="${cls}">${label}</span>`;
      },
    },
  },

  actions: isAdmin
    ? { show: true }
    : {
        show: true,
        edit: false,
        delete: false,
        clone: false,
      },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
