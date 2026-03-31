const SEARCH_LOCALSTORAGE_KEY = 'datatable_search_values';
const SEARCH_DATATABLE_STATE_KEY = 'datatable_search_state';

// eslint-disable-next-line no-unused-vars
class DataTable {
  constructor(options = {}, tableId = '#__data__table') {
    this.initVariables(options, tableId);
    this.setSearchStoredData();
    this.columnDefaultRender(options);
    this.init();
  }

  initVariables(options, tableId) {
    this.tableId = tableId;
    this.tableEl = $(tableId);
    this.options = options;
    this.searchFormId = options.searchFormId ?? 'dataTable__search__form';
    this.searchFormEl = $(`#${this.searchFormId}`);
    this.searchData = {};
    this.pathOptions = options.pathOptions;
  }

  columnDefaultRender(options) {
    const showStatusRender = {
      show_status: {
        render(showStatus) {
          return `<span class="show-status-${showStatus}">${$trans(`__dashboard.select.option.show_status_${showStatus}`)}</span>`;
        },
      },
    };

    options.columnsRender = { ...showStatusRender, ...options.columnsRender };
  }

  storeSearchData() {
    return this.options.storeSearchData ?? true;
  }

  actions() {
    const replaceId = (id, type) => this.pathOptions[`${type}Path`].replace(':id', id);

    const self = this;
    const defaultActions = {
      edit(row) {
        return `<a href="${replaceId(row.id, 'edit')}" class="btn" title="Edit">
                    <i class="flaticon-edit"></i>
                </a>`;
      },

      show(row) {
        return `<a href="${replaceId(row.id, 'show')}" class="btn" title="Show">
                   <i class="flaticon-eye"></i>
                </a>`;
      },

      clone(row) {
        if (self.pathOptions.clonePath) {
          return `<a href="${replaceId(row.id, 'clone')}" class="btn" title="Duplicate">
                   <i class="fas fa-copy fa-fw"></i>
                </a>`;
        }

        return '';
      },

      delete(row) {
        if (typeof row.canDelete !== 'undefined' && !row.canDelete) {
          return '';
        }

        return `<button type="button" data-url="${replaceId(row.id, 'delete')}" data-event-name="deleteDataTableRow" class="btn __confirm__delete__btn" title="Delete">
                    <i class="flaticon2-trash"></i>
                </button>`;
      },
    };

    this.options.actions = { ...defaultActions, ...this.options.actions };
  }

  renderActions(data, type, row, meta) {
    let actions = '';
    // eslint-disable-next-line no-restricted-syntax
    for (const [key, func] of Object.entries(this.options.actions)) {
      if (typeof func !== 'boolean') actions += func(row);
    }
    return `<div class="text-center table-buttons">${actions}</div>`;
  }

  columnRenderRelation(column) {
    const columnName = column.data;
    if (this.options.relations && this.options.relations[columnName]) {
      const relationName = this.options.relations[columnName];
      column.render = (data) => {
        let text = '';

        if (data && !Array.isArray(data)) {
          // if relation is objected
          if (typeof relationName !== 'string') {
            const relationKey = Object.keys(relationName)[0] || '';

            if (data[relationKey]) {
              return data[relationKey][relationName[relationKey]];
            }
          }

          return data[relationName] || '';
        }

        if (Array.isArray(data)) {
          data.map((item, index) => {
            if (typeof relationName !== 'string') {
              const relationKey = Object.keys(relationName)[0] || '';
              if (item[relationKey]) {
                const relVal = item[relationKey][relationName[relationKey]];
                text += index ? `, ${relVal}` : relVal;
              }
            } else {
              text += index ? `, ${item[relationName]}` : item[relationName];
            }
          });

          return text;
        }

        return '';
      };
    }
    return column;
  }

  actionsColumn() {
    return {
      data: null,
      orderable: false,
      sortable: false,
      render: this.renderActions.bind(this),
    };
  }

  mapTableColumns(columns = []) {
    const fields = this.tableEl.find('thead th');
    // eslint-disable-next-line array-callback-return
    fields.map((key, field) => {
      const columnName = $(field).data('key');
      let column = {
        data: columnName,
        name: columnName,
        orderable: $(field).data('orderable') ?? true,
      };

      const { columnsRender } = this.options;

      if (columnsRender && columnsRender[columnName]) {
        column = { ...column, ...columnsRender[column.data] };
      }

      if (columnName) {
        columns.push(this.columnRenderRelation(column));
      }
    });
    return columns;
  }

  getAndGenerateColumns() {
    const columns = this.mapTableColumns();
    if (typeof this.options.noActions === 'undefined' || !this.options.noActions) {
      columns.push(this.actionsColumn());
    }
    return columns;
  }

  // eslint-disable-next-line class-methods-use-this
  generateRequestData(data) {
    let orderColumn = data.order[0];
   /* if (this.options.order) {
      orderColumn = {
        column: this.options.order[0][0],
        dir: this.options.order[0][1],
      };
    }*/

    return {
      'order[sort_by]': data.columns[orderColumn.column].name,
      'order[sort_desc]': orderColumn.dir,
      perPage: data.length,
      start: data.start,
      ...this.searchData,
    };
  }

  async ajaxSend(data, callback) {
    this.resetForm();
    const searchInput = this.getDefaultSearchInputField();
    if (searchInput.val() && searchInput.val().trim()) {
      searchInput.prop('readOnly', true);
    }

    // eslint-disable-next-line no-undef
    await axios(this.pathOptions.searchPath, { params: this.generateRequestData(data) })
      .then((resp) => {
        callback(resp.data);

        if (this.options.beforeSendRequest) {
          this.options.beforeSendRequest(resp.data);
        }
      }).catch((err) => {
        this.errorHandler(err);
        callback({
          data: [],
          recordsFiltered: 0,
        });
      }).finally(() => {
        searchInput.prop('readOnly', false);
      });

    this.searchFromLoading();
  }

  errorHandler(error) {
    error = error.response.data;
    if (error) {
      const formEl = this.searchFormEl;

      formEl.find('.validation-error').removeClass('has-error');
      formEl.find('.form-group').removeClass('is-invalid');

      for (const [key, value] of Object.entries(error.errors)) {
        const nameKEy = key.substring(2);
        let hasErrorSpan = formEl.find(`.validation-error[data-name="${nameKEy}"]`);

        if (!hasErrorSpan.length) {
          hasErrorSpan = formEl.find(`.validation-error[data-name="${nameKEy}[]"]`);
        }

        hasErrorSpan.closest('.form-group').addClass('is-invalid');
        hasErrorSpan.html(value).addClass('has-error');
      }
    }
  }

  resetForm() {
    this.searchFormEl.find('.validation-error').html('');
    this.searchFormEl.find('.form-group').removeClass('is-invalid');
  }

  generateOptions() {
    const moduleName = this.getCurrentModuleName();

    const options = {
      processing: true,
      serverSide: true,
      searching: true,
      // searchDelay: 1000,
      ajax: this.ajaxSend.bind(this),
      order: [[0, 'desc']],
      pageLength: 25,
      columns: this.getAndGenerateColumns(),
      bStateSave: true,
      stateSaveCallback(oSettings, oData) {
        const storeData = {};
        if (oData.search) {
          oData.search.search = '';
        }

        storeData[moduleName] = oData;

        localStorage.setItem(SEARCH_DATATABLE_STATE_KEY, JSON.stringify(storeData));
      },
      language: {
        search: '',
        searchPlaceholder: $trans('__dashboard.datatable.search_input_placeholder'),
      },
    };

    const savedDatatableState = JSON.parse(localStorage.getItem(SEARCH_DATATABLE_STATE_KEY));
    if (savedDatatableState && savedDatatableState[moduleName]) {
      options.stateLoadCallback = () => savedDatatableState[moduleName];
    }

    this.options = { ...options, ...this.options };
  }

  tableReload() {
    this.table.ajax.reload();
  }

  eventListener() {
    window.addEventListener('deleteDataTableRow', () => {
      this.tableReload();
    });
  }

  searchFromLoading(is) {
    const searchFormBtn = this.searchFormEl.find('button.search__form__btn');
    const searchFormResetBtn = this.searchFormEl.find('button.reset__form__btn');
    const spinner = this.searchFormEl.find('.loading__form__icon');
    if (is) {
      spinner.addClass('d-inline-block ');
      searchFormBtn.prop('disabled', true);
      searchFormResetBtn.prop('disabled', true);
    } else {
      spinner.removeClass('d-inline-block');
      searchFormBtn.prop('disabled', false);
      searchFormResetBtn.prop('disabled', false);
    }
  }

  searchFormReset() {
    this.searchFormEl.find('.reset__form__btn').click(() => {
      this.searchFromLoading(true);
      this.searchData = {};
      this.searchFormEl[0].reset();
      this.table.search('');
      this.clearSearchedDataFromLocalstorage();
      this.searchFormEl.find('.select2').val(null).trigger('change');
      this.tableReload();
      this.getSearchCollapse().removeClass('has-default-values');
    });
  }

  searchFormSubmit(event) {
    event.preventDefault();
    const el = event.target;
    this.searchData = {};

    this.searchFromLoading(true);
    const searchData = $(el).serializeArray();

    searchData.map((item, id) => {
      let addArrayBrackets = '';
      if ($(el).find(`select[name="${item.name}"][multiple]`).length) {
        addArrayBrackets = `[${id}]`;
      }

      this.searchData[`f[${item.name}]${addArrayBrackets}`] = item.value;
    });

    if (this.table.search().length) {
      this.searchData['f[search]'] = this.table.search();
    }

    this.storeSearchedData();
    this.tableReload();
  }

  storeSearchedData() {
    if (this.storeSearchData()) {
      const searchData = this.searchFormEl.serializeArray();
      const searchDataValues = searchData.filter((item) => item.value !== '' || item.name === 'show_status');
      const moduleName = this.getCurrentModuleName();

      const storeData = {};
     /* if (this.table.search()) {
        searchDataValues.push({ name: 'search', value: this.table.search() });
      }*/

      storeData[moduleName] = searchDataValues;

      localStorage.setItem(SEARCH_LOCALSTORAGE_KEY, JSON.stringify(storeData));

      if (this.options.afterStoreSearchedData) {
        this.options.afterStoreSearchedData(storeData);
      }
    }
  }

  setSearchStoredData() {
    if (this.storeSearchData()) {
      const localStoredData = JSON.parse(localStorage.getItem(SEARCH_LOCALSTORAGE_KEY));
      const moduleName = this.getCurrentModuleName();

      if (
        localStoredData
        && localStoredData[moduleName]
        && localStoredData[moduleName].length
      ) {
        const self = this;
        const storedData = localStoredData[moduleName];

        // Collapse Show Part
        let showCollapse = true;

        if (storedData.length === 1 && $.inArray(storedData[0].name, ['search', 'show_status']) !== -1) {
          showCollapse = false;
        }

        if (storedData.length === 2 && storedData[0].name === 'show_status' && storedData[1].name === 'search') {
          showCollapse = false;
        }

        if (showCollapse) {
          this.showSearchCollapse();
        }

        const storedDataModified = storedData.reduce((prev, current) => {
          if (typeof prev[current.name] === 'undefined') {
            prev[current.name] = current.value;
          } else if (Array.isArray(prev[current.name])) {
            prev[current.name].push(current.value);
          } else {
            prev[current.name] = [prev[current.name], current.value];
          }
          return prev;
        }, {});

        $.each(storedDataModified, (key, value) => {
          const searchInput = self.searchFormEl.find(`:input[name='${key}']`);

       /*   // Checkbox
          if (searchInput.is(':checkbox')) {
            searchInput.prop('checked', true);
          }

          // Date
          if (searchInput.hasClass('backend-date-value')) {
            const closestDiv = searchInput.closest('div');
            if (closestDiv.find('.datepicker').length) {
              closestDiv.find('.datepicker')
                .val(moment(value, $dashboardDates.js.date_format)
                  .format($dashboardDates.js.date_format_front));
            }

            if (closestDiv.find('.datetimepicker').length) {
              closestDiv.find('.datetimepicker')
                .val(moment(value, $dashboardDates.js.date_time_format)
                  .format($dashboardDates.js.date_time_format_front));
            }
          }
*/
          //
          let addArrayBrackets = '';
          if (!Array.isArray(value) && searchInput.attr('multiple')) {
            addArrayBrackets = '[]';
          }

          // Search Input of Datatable
          /*if (key === 'search') {
            setTimeout(function () {
              $('.dataTables_filter').find('input').val(value);
            }, 200);
          }*/

          //
          // searchInput.val(value);
          // self.searchData[`f[${key}]${addArrayBrackets}`] = value;
        });

        if (this.options.afterSetSearchStoredData) {
          this.options.afterSetSearchStoredData(storedData);
        }
      }
    }
  }

  getSearchCollapse() {
    return this.searchFormEl.closest('.datatable-search-collapse')
      .find('.collapse');
  }

  showSearchCollapse() {
    this.getSearchCollapse()
      .addClass('has-default-values show');
  }

  getCurrentModuleName() {
    return location.pathname.split('/')[3];
  }

  clearSearchedDataFromLocalstorage() {
    localStorage.removeItem(SEARCH_LOCALSTORAGE_KEY);
    localStorage.removeItem(SEARCH_DATATABLE_STATE_KEY);
  }

  defaultSearchData() {
    const self = this;
    $.each(this.searchFormEl.find('.default-search'), (k, item) => {
      self.searchData[`f[${$(item).attr('name')}]`] = $(item).val();
    });
  }

  createDataTable() {
    this.table = this.tableEl.DataTable(this.options);
  }

  defaultSearchInput() {
    const self = this;
    let timer;

    this.getDefaultSearchInputField()
      .unbind()
      .bind('keyup input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => {
          self.searchData['f[search]'] = $(this).val();
          self.table.search($(this).val());

          self.tableReload();
          self.storeSearchedData();
        }, 330);
      });
  }

  getDefaultSearchInputField() {
    return $(`#${this.tableId.substring(1)}_filter`).find('input');
  }

  $_functions() {
    this.createDataTable();
    this.searchFormEl.submit(this.searchFormSubmit.bind(this));
  }

  init() {
    this.actions();
    this.defaultSearchData();
    this.generateOptions();
    this.eventListener();
    this.$_functions();
    this.searchFormReset();
    this.defaultSearchInput();
  }
}
