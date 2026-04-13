const options = {
  pathOptions: {
    searchPath: route('dashboard.payslips.getListData'),
    showPath: route('dashboard.payslips.show', ':id'),
  },

  columnsRender: {
    pdf_path: {
      render(data, type, row) {
        const downloadUrl = route('dashboard.payslips.download', row.id);
        const parts = [`<a href="${downloadUrl}" class="btn btn-sm btn-outline-primary py-0" rel="noopener">PDF</a>`];
        if (data) {
          parts.push(
            `<a href="${window.location.origin}/storage/${data}" class="btn btn-sm btn-link py-0" target="_blank" rel="noopener">Upload</a>`
          );
        }
        return `<div class="d-flex flex-wrap gap-1 justify-content-center">${parts.join('')}</div>`;
      },
    },
  },

  actions: {
    show(row) {
      return `<a href="${route('dashboard.payslips.show', row.id)}" class="btn" title="Show">
                   <i class="flaticon-eye"></i>
                </a>`;
    },
    edit: false,
    delete: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
