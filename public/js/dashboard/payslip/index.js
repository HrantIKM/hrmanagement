const options = {
  pathOptions: {
    searchPath: route('dashboard.payslips.getListData'),
    deletePath: route('dashboard.payslips.destroy', ':id'),
    editPath: route('dashboard.payslips.edit', ':id'),
    showPath: route('dashboard.payslips.show', ':id'),
  },

  columnsRender: {
    user: {
      render(data) {
        if (!data) return '';
        const fullName = [data.first_name, data.last_name].filter(Boolean).join(' ').trim();
        return fullName || data.email || '';
      },
    },
    pdf_path: {
      render(data, type, row) {
        const downloadUrl = route('dashboard.payslips.download', row.id);
        const parts = [`<a href="${downloadUrl}" class="btn btn-sm btn-outline-primary py-0" rel="noopener">PDF</a>`];
        if (data) {
          parts.push(
            `<a href="${window.location.origin}/storage/${data}" class="btn btn-sm btn-link py-0" target="_blank" rel="noopener">Upload</a>`
          );
        }
        return `<div class="d-flex flex-wrap gap-1">${parts.join('')}</div>`;
      },
    },
  },

  // Keep default DataTable actions (edit/show/delete).
  // Setting show: true overrides function with boolean and hides icon.
  actions: {},
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
