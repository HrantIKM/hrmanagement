class MultipleInputs {
  constructor() {
    this.coreParentClass = 'core-multiple-inputs';
  }

  _addMultipleItem() {
    const $this = this;
    $('.core-multiple-button').click(function () {
      const parentGroupDiv = $(this).closest(`.${$this.coreParentClass}`);

      if (parentGroupDiv.find('select.select2').length) {
        parentGroupDiv.find('select.select2').select2('destroy');
      }

      const clonedGroup = parentGroupDiv.find('.group-item:first').clone();
      const clonedGroupInputLength = clonedGroup.find('input').length;
      const groupItemsLength = parentGroupDiv.find('.group-item').length;

      if (clonedGroupInputLength) {
        $.each(clonedGroup.find(':input').not("input[type='hidden']").not('button'), (k, input) => {
          const formGroup = $(input).closest('.form-group');
          const errorSpan = formGroup.find('.validation-error');

          if ($(input).attr('type') === 'file') {
            const fileId = $(input).attr('id');

            const replacedName = $(input).attr('data-name').replace('0', groupItemsLength);
            $(input).val('').attr('data-name', replacedName);
            const replacedId = fileId.replace('0', groupItemsLength);
            $(input).attr('id', replacedId);

            const labels = clonedGroup.find(`[for="${fileId}"]`);
            $(input).closest('.__uploaded__files').remove();

            if (labels.length) {
              const label = labels[0];
              const labelReplacedFor = $(label).attr('for').replace('0', groupItemsLength);
              const labelReplacedDataInput = $(label).attr('data-input').replace('0', groupItemsLength);
              const labelReplacedDataPreview = $(label).attr('data-preview').replace('0', groupItemsLength);

              $(label).attr('for', labelReplacedFor);
              $(label).attr('data-input', labelReplacedDataInput);
              $(label).attr('data-preview', labelReplacedDataPreview);
            }

            clonedGroup.find('.hidden-file-inputs').html('');
            clonedGroup.find('.__uploaded__files').html('');
            clonedGroup.find('.__file__list__default').html('');
          } else {
            const currentInput = $(input);
            const replacedName = currentInput.attr('name').replace('0', groupItemsLength);
            const currentLabel = clonedGroup.find(`label[for="${currentInput.attr('id')}"]`);

            // input
            currentInput.val('').attr('name', replacedName);
            const newInputId = `${currentInput.attr('data-name')}_${new Date().getTime()}`;

            // Add New I'd
            if (currentLabel.length) {
              currentLabel.attr('for', newInputId);
            }
            currentInput.attr('id', newInputId);
          }

          const replacedErrorName = errorSpan.attr('data-name').replace('0', groupItemsLength);

          errorSpan.attr('data-name', replacedErrorName).removeClass('has-error').text('');
          formGroup.removeClass('is-invalid');
        });

        if (clonedGroupInputLength > 1) {
          clonedGroup.addClass('grouped');
        }

        clonedGroup.attr('data-index', groupItemsLength);
        clonedGroup.append($this._getRemoveIcon());

        parentGroupDiv.find('.multiple-group-content').append(clonedGroup);
      }

      if (parentGroupDiv.find('select.select2').length) {
        select2Init(parentGroupDiv);
      }
    });
  }

  _deleteMultipleItem() {
    const $this = this;
    $(document).on('click', '.remove-multiple-item', function () {
      const parentData = $(this).closest('.core-multiple-inputs');

      $(this).closest('.group-item').remove();
      $this._resetInputName(parentData);
    });
  }

  _resetInputName(parentData) {
    let i = 1;
    parentData.find('.group-item:not(:first)').each((groupKey, groupItem) => {
      $(groupItem).find(':input').each((k, input) => {
        const dataNum = $(groupItem).attr('data-index');
        const formGroup = $(input).closest('.form-group');
        const errorSpan = formGroup.find('.validation-error');

        if ($(input).attr('name')) {
          const replacedName = $(input).attr('name').replace(dataNum, i);
          const replacedErrorName = errorSpan.attr('data-name').replace(dataNum, i);

          $(input).attr('name', replacedName);
          errorSpan.attr('data-name', replacedErrorName);
        }
      });

      $(groupItem).attr('data-index', i);

      i++;
    });
  }

  _getRemoveIcon() {
    return '<i class="flaticon-circle remove-multiple-item"></i>';
  }

  init() {
    if ($(`.${this.coreParentClass}`).length) {
      this._addMultipleItem();
      this._deleteMultipleItem();
    }
  }
}

new MultipleInputs().init();
