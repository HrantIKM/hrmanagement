const options = {
  pathOptions: {
    searchPath: route('dashboard.goals.getListData'),
    deletePath: route('dashboard.goals.destroy', ':id'),
    editPath: route('dashboard.goals.edit', ':id'),
    showPath: route('dashboard.goals.show', ':id'),
  },

  relations: {
    user: 'name',
  },

  columnsRender: {
    type_display: {
      render(data, type, row) {
        const key = (row.type || '').toString().replace(/_/g, '-');
        const pillClass = key ? `status-pill status-pill--goal-${key}` : 'status-pill';
        return `<span class="${pillClass}">${data || ''}</span>`;
      },
    },
    progress_percent: {
      render(data) {
        if (data === null || data === undefined || data === '') {
          return '<span class="goal-progress goal-progress--empty">—</span>';
        }
        const pct = Math.min(100, Math.max(0, Number(data)));
        let tone = 'low';
        if (pct >= 100) tone = 'complete';
        else if (pct >= 70) tone = 'good';
        else if (pct >= 40) tone = 'mid';
        return `<div class="goal-progress goal-progress--${tone}" title="${data}%">
          <div class="goal-progress__track">
            <div class="goal-progress__fill" style="width:${pct}%"></div>
          </div>
          <span class="goal-progress__label">${data}%</span>
        </div>`;
      },
    },
  },

  actions: {
    show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
