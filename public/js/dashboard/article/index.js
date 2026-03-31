const options = {
  pathOptions: {
    searchPath: route('dashboard.articles.getListData'),
    deletePath: route('dashboard.articles.destroy', ':id'),
    editPath: route('dashboard.articles.edit', ':id'),
    // showPath: route('dashboard.articles.show', ':id'),
  },

  actions: {
    show: false,

    // delete(row) {
    //   return 'Custom Delete'
    // }
  },

  // Need to check
  // order: [[2, 'desc']],

  // Search input hide
  // searching: false

  // If don't want to save search data
  // storeSearchData: false,

  /*afterStoreSearchedData(storedData) {
    console.log(storedData);
  },

  afterSetSearchStoredData(storedData) {
    console.log(storedData);
  },*/

  /* relations: {
    relationName: 'columnName',
  }, */

  // It's an Example for show column render data type (don't open comment!!)
  /* columnsRender: {
    show_status: {
      render(showStatus,display,row) {
        return `<span class="show-status-${showStatus}">${$trans(`__dashboard.select.option.show_status_${showStatus}`)}</span>`;
      },
    },
  }, */

};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
