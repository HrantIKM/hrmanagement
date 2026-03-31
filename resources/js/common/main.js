$(function () {
  select2Init();

  datepickerInit();

  datetimePickerInit();

  ckEditorInit();

  openMenu();

  copyMlInfo();

  responseMessage();

  minimizeMenu();
});

showSuccessMessage = function (message) {
  $.toast({
    text: message,
    hideAfter: 2000,
    position: 'top-right',
    stack: false,
    loader: false,
    icon: 'success',
    allowToastClose: false,
  });
};

showErrorMessage = function (message) {
  $.toast({
    heading: 'Error',
    hideAfter: 2000,
    text: message,
    position: 'top-right',
    stack: false,
    loader: false,
    icon: 'error',
    allowToastClose: false,
  });
};

select2Init = function (div = undefined, className = 'select2') {
  let select2 = $(`select.${className}`);
  if (typeof div !== 'undefined') {
    select2 = div.find(`select.${className}`);
  }

  $.each(select2, function () {
    $(this).select2({
      placeholder: $trans('__dashboard.select.option.default'),
      minimumResultsForSearch: 10,
      tags: $(this).data('allow-tags') || false,
      allowClear: $(this).data('allow-clear') || false,
    });
  });
};

select2Reset = function (select) {
  if (select.length) {
    select.select2('val', '');
    select.find('option').not(':first-child').remove();
  }
};

select2SetValues = function (select, data) {
  $.each(data, (key, value) => {
    select.append(new Option(value, key, false, false)).trigger('change.select2');
  });
};

disableField = function (field, disable) {
  if (typeof disable === 'undefined') {
    disable = true;
  }

  field.prop('disabled', disable);
};

loadContent = function (content, removeLoad) {
  if (typeof removeLoad === 'undefined') {
    content.addClass('loading-content position-relative');
  } else {
    content.removeClass('loading-content position-relative');
  }
};

function minimizeMenu() {
  $('.brand-toggle').click(() => {
    $('.page').toggleClass('minimaize-menu');
  });
  $('.open-menu').click(() => {
    $('.page').toggleClass('left-menu-opened');
    $('.open-menu').toggleClass('open-menu-opened');
  });
}

// ClassicEditor
function ckEditorInit() {
  const ckeditorEls = document.querySelectorAll('.ckeditor5');

  ckeditorEls.forEach((item) => {
    ClassicEditor.create(item, {
      cloudServices: {
        tokenUrl: 'https://33333.cke-cs.com/token/dev/ijrDsqFix838Gh3wGO3F77FSW94BwcLXprJ4APSp3XQ26xsUHTi0jcb1hoBt',
        uploadUrl: 'https://33333.cke-cs.com/easyimage/upload/',
      },
    })
      .then((editor) => {
        if ($(item).attr('disabled')) {
          editor.isReadOnly = true;
        }

        editor.model.document.on('change:data', (evt, data) => {
          editor.updateSourceElement();
        });
      })
      .catch((error) => {
        console.error(error);
      });
  });
}

function datepickerInit() {
  const datepickerInputs = $('.datepicker');

  if (datepickerInputs.length) {
    $.each(datepickerInputs, function () {
      const self = $(this);

      self.datetimepicker({
        timepicker: false,
        format: 'd.m.Y',
        scrollMonth: false,
        scrollInput: false,
        onChangeDateTime(dp, $input) {
          let backendVal = '';
          if ($input.val()) {
            backendVal = moment($input.val(), 'DD.MM.YYYY').format($dashboardDates.js.date_format);
          }
          self.siblings('.backend-date-value').val(backendVal);
        },
      });
    });
  }
}

function datetimePickerInit() {
  const datetimePickerInputs = $('.datetimepicker');

  if (datetimePickerInputs.length) {
    $.each(datetimePickerInputs, function () {
      const self = $(this);

      self.datetimepicker({
        format: 'd.m.Y H:i',
        onChangeDateTime(dp, $input) {
          let backendVal = '';
          if ($input.val()) {
            backendVal = moment($input.val(), 'DD.MM.YYYY HH:mm').format($dashboardDates.js.date_time_format);
          }
          self.siblings('.backend-date-value').val(backendVal);
        },
      });
    });
  }
}

function openMenu() {
  $('.open-menu').click(() => {
    $('.page').toggleClass('left-menu-opened');
    $('.open-menu').toggleClass('open-menu-opened');
  });
}

function copyMlInfo() {
  const copyMlButton = $('.copy-ml-info-btn');

  if (copyMlButton.length) {
    copyMlButton.click(function () {
      const self = $(this);
      const currentTabData = self.closest('.tab-pane').find(':input');
      const toTabData = $(`#__mls__tab__${self.data('to-lang-code')}`);

      self.prop('disabled', true);

      $.each(currentTabData, function () {
        const inputName = $(this).attr('name');

        if (inputName) {
          const toInputName = inputName.replace(self.data('current-lang-code'), self.data('to-lang-code'));
          const toInput = toTabData.find(`*[name="${toInputName}"]`);

          if (toInput.length) {
            // @todo ckeditor set val
            if (toInput.hasClass('ckeditor5')) {
              // toInput.ckeditor5().setData($(this).val());
            } else {
              toInput.val($(this).val());
            }
          }

          setTimeout(() => {
            self.prop('disabled', false);
          }, 120);
        }
      });
    });
  }
}

function responseMessage() {
  const responseMessageStorage = localStorage.getItem('_message');
  if (responseMessageStorage) {
    // eslint-disable-next-line no-undef
    showSuccessMessage(responseMessageStorage);
  }
  localStorage.removeItem('_message');
}
