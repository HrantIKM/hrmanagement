// eslint-disable-next-line no-underscore-dangle
let _selfConfirmModal;

// eslint-disable-next-line no-unused-vars
class ConfirmModal {
  constructor() {
    this.init();
  }

  // eslint-disable-next-line class-methods-use-this
  modalVisible(type = 'show') {
    $('#d_confirm__modal').modal(type);
  }

  // eslint-disable-next-line class-methods-use-this
  deleteEvent() {
    const event = new CustomEvent(_selfConfirmModal.deleteEventName, {
      detail: {
        element: _selfConfirmModal.deletableItem,
        status: 'OK',
      },
    });

    window.dispatchEvent(event);
  }

  // eslint-disable-next-line class-methods-use-this
  loader(is = false) {
    const spinner = $('#d_confirm__modal button.delete-btn .spinner-border');
    if (is) spinner.css({ display: 'inline-block' });
    else spinner.hide();
  }

  // eslint-disable-next-line class-methods-use-this
  async deleteHandle() {
    _selfConfirmModal.loader(true);
    if (_selfConfirmModal.deleteUrl) {
      // eslint-disable-next-line no-undef
      await axios.delete(_selfConfirmModal.deleteUrl)
        .then((resp) => {
          resp = resp.data;
          _selfConfirmModal.deleteEvent();
          // eslint-disable-next-line no-undef
          if (resp.message) showSuccessMessage(resp.message);
        });
    } else {
      _selfConfirmModal.deleteEvent();
    }
    _selfConfirmModal.modalVisible('hide');
    _selfConfirmModal.loader();
  }

  clickDeletableItem() {
    _selfConfirmModal.deletableItem = this;
    _selfConfirmModal.modalVisible();
    _selfConfirmModal.deleteUrl = $(this).attr('data-url');
    _selfConfirmModal.deleteEventName = $(this).attr('data-event-name');
  }

  $_clicks() {
    $('body').on('click', 'button.__confirm__delete__btn', this.clickDeletableItem);
    $('#d_confirm__modal').on('click', 'button.delete-btn', this.deleteHandle);
  }

  init() {
    _selfConfirmModal = this;
    _selfConfirmModal.deleteEventName = 'confirmModalEvent';
    this.$_clicks();
  }
}

// eslint-disable-next-line no-new
new ConfirmModal();
