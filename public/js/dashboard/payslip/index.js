const options = {
  pathOptions: {
    searchPath: route('dashboard.payslips.getListData'),
    deletePath: route('dashboard.payslips.destroy', ':id'),
    editPath: route('dashboard.payslips.edit', ':id'),
    showPath: route('dashboard.payslips.show', ':id'),
  },

  relations: {
    user: 'name',
  },

  columnsRender: {
    pdf_path: {
      render(data) {
        if (!data) {
          return '';
        }
        return `<a href="${window.location.origin}/storage/${data}" target="_blank" rel="noopener">PDF</a>`;
      },
    },
  },

  actions: {
    show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
