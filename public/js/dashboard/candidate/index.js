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

  columnsRender: {
    match_score: {
      render(score) {
        const val = Number(score || 0);
        let cls = 'status-pill--low';
        if (val >= 80) cls = 'status-pill--active';
        else if (val >= 60) cls = 'status-pill--pending';
        else if (val >= 40) cls = 'status-pill--in-progress';
        return `<span class="status-pill ${cls}">${val}%</span>`;
      },
    },
    resume_path: {
      render(path, type, row) {
        if (!path) return '<span class="text-muted small">No resume</span>';
        const url = route('dashboard.candidates.resume', row.id);
        return `<a href="${url}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">Open CV</a>`;
      },
    },
    skills: {
      render(skills) {
        if (!Array.isArray(skills) || !skills.length) return '<span class="text-muted small">—</span>';
        const preview = skills.slice(0, 3).map((s) => `<span class="status-pill status-pill--in-progress">${s.name}</span>`).join(' ');
        const more = skills.length > 3 ? `<span class="text-muted small ms-1">+${skills.length - 3}</span>` : '';
        return `<div class="d-flex flex-wrap gap-1">${preview}${more}</div>`;
      },
    },
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
