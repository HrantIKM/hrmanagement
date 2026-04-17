function todayYmd() {
  const t = new Date();
  const y = t.getFullYear();
  const m = String(t.getMonth() + 1).padStart(2, '0');
  const d = String(t.getDate()).padStart(2, '0');
  return `${y}-${m}-${d}`;
}

function formatTaskDueDate(raw, row) {
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
  const done = row.status === 'done';
  const overdue = !done && datePart < todayYmd();
  const cls = overdue ? 'tasks-index-due tasks-index-due--overdue' : 'tasks-index-due';
  return `<span class="${cls}"><span class="project-date-cell__dow">${dow}</span> <span class="project-date-cell__main">${main}</span></span>`;
}

const pathOptions = {
  searchPath: route('dashboard.tasks.getListData'),
  deletePath: route('dashboard.tasks.destroy', ':id'),
  editPath: route('dashboard.tasks.edit', ':id'),
  showPath: route('dashboard.tasks.show', ':id'),
};

const options = {
  pathOptions,

  relations: {
    project: 'name',
    user: 'name',
  },

  columnsRender: {
    due_date: {
      render(data, type, row) {
        return formatTaskDueDate(data, row);
      },
    },
  },

  actions: {
    show: true,
    edit(row) {
      if (row.can_edit === false) {
        return '';
      }
      const href = pathOptions.editPath.replace(':id', row.id);
      return `<a href="${href}" class="btn" title="Edit"><i class="flaticon-edit"></i></a>`;
    },
    delete(row) {
      if (!row.can_delete) {
        return '';
      }
      const url = pathOptions.deletePath.replace(':id', row.id);
      return `<button type="button" data-url="${url}" data-event-name="deleteDataTableRow" class="btn __confirm__delete__btn" title="Delete"><i class="flaticon2-trash"></i></button>`;
    },
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
