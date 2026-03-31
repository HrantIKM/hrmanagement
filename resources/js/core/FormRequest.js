// eslint-disable-next-line no-unused-vars
class FormRequest {
  constructor(options, formId = '__form__request') {
    this.options = options;
    this.formId = formId;
    this.formEl = $(`#${formId}`);
    this.cardBody = this.formEl.find('.card-body');
    this.init();
  }

  async formSubmit(event) {
    event.preventDefault();
    const form = event.target;

    const method = $(form).attr('method');
    const url = $(form).attr('action');
    const formData = new FormData(form);
    await this.sendData(method, url, formData);
  }

  async sendData(method, url, formData) {
    this.formEl.find('.validation-error').html('');
    this.formRequestLoader();

    if (this.__checkOptionMethodsExist() && this.options.methods.beforeSendRequest) {
      this.options.methods.beforeSendRequest();
    }

    this.formLoad();

    // eslint-disable-next-line no-undef
    await axios.post(url, formData)
      .then(this.successHandler.bind(this))
      .catch(this.errorHandler.bind(this));
  }

  // eslint-disable-next-line class-methods-use-this
  successHandler(resp) {
    resp = resp.data;

    if (this.__checkOptionMethodsExist() && this.options.methods.afterSuccess) {
      this.formLoad(false);
      this.formRequestLoader(false);
      return this.options.methods.afterSuccess(resp);
    }

    if (resp.redirectUrl) {
      localStorage.setItem('_message', resp.message);
      location.href = resp.redirectUrl;
    } else {
      this.formRequestLoader(false);
      this.formLoad(false);
    }
  }

  errorHandler(error) {
    if (error.response && error.response.data.errors) {
      error = error.response.data;

      this.formEl.find('.validation-error').removeClass('has-error');
      $('.form-group').removeClass('is-invalid');
      // eslint-disable-next-line no-restricted-syntax
      for (const [key, value] of Object.entries(error.errors)) {
        let hasErrorSpan = this.formEl.find(`.validation-error[data-name="${key}"]`);

        if (!hasErrorSpan.length) {
          hasErrorSpan = this.formEl.find(`.validation-error[data-name="${key}[]"]`);
        }

        hasErrorSpan.closest('.form-group').addClass('is-invalid');
        hasErrorSpan.html(value).addClass('has-error');
      }

      this.scrollToFirstError();

      if (this.__checkOptionMethodsExist() && this.options.methods.afterError) {
        this.options.methods.afterError(error);
      }
    }

    this.formRequestLoader(false);
    this.formLoad(false);
  }

  scrollToFirstError() {
    const firstError = this.formEl.find('.validation-error.has-error').first();

    if (firstError.closest('.tab-pane')) {
      $(`.nav-tabs a[href="#${firstError.closest('.tab-pane').attr('id')}"]`).tab('show');
    }

    if (firstError.length) {
      setTimeout(() => {
        $('html, body').animate({
          scrollTop: firstError.offset().top - 300,
        }, 200);
      }, 150);
    }
  }

  formRequestLoader(is = true) {
    const spinner = this.formEl.find('button.form__request__send__btn .spinner-border');
    const btn = this.formEl.find('button.form__request__send__btn');
    if (is) {
      spinner.css({ display: 'inline-block' });
      btn.prop('disabled', true);
    } else {
      spinner.hide();
      btn.prop('disabled', false);
    }
  }

  formLoad(is = true) {
    if (is) {
      this.cardBody.addClass('loading-content');
    } else {
      this.cardBody.removeClass('loading-content');
    }
  }

  showMode() {
    this.formEl.find(':input').prop('disabled', true);
  }

  clickSubmit() {
    this.formEl.submit(this.formSubmit.bind(this));
  }

  init() {
    if (this.formEl.hasClass('show-mode')) {
      this.showMode();
    }

    this.clickSubmit();
    this.formRequestLoader(false);
    this.formLoad(false);
  }

  __checkOptionMethodsExist() {
    return typeof this.options != 'undefined' && typeof this.options.methods != 'undefined';
  }
}
